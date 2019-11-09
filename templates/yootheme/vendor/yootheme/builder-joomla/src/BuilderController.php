<?php

namespace YOOtheme\Builder\Joomla;

use Exception;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Helper\MediaHelper;
use Joomla\CMS\Http\HttpFactory;
use YOOtheme\ContainerTrait;
use YOOtheme\Http\Uri;

class BuilderController
{
    use ContainerTrait;

    public function loadImage($src, $md5 = null, $response)
    {
        $app = Factory::getApplication();
        $http = HttpFactory::getHttp();
        $params = ComponentHelper::getParams('com_media');

        try {

            $uri = Uri::fromString($src);
            $file = basename($uri->getPath());

            if ($uri->getHost() === 'images.unsplash.com') {
                $file .= ".{$uri->getQueryParam('fm', 'jpg')}";
            }

            $file = File::makeSafe($file);
            $path = Path::check(rtrim(implode('/', [JPATH_ROOT, $params->get('image_path'), $this->theme->get('media_folder')]), '/\\'));

            // file already exists?
            while ($iterate = @md5_file("{$path}/{$file}")) {

                if ($iterate === $md5 || is_null($md5)) {
                    return $response->withJson(strtr(substr("{$path}/{$file}", strlen(JPATH_ROOT) + 1), '\\', '/'));
                }

                $file = preg_replace_callback('/-?(\d*)(\.[^.]+)?$/', function ($match) {
                    return sprintf('-%02d%s', intval($match[1]) + 1, isset($match[2]) ? $match[2] : '');
                }, $file, 1);
            }

            // create file
            File::write("{$path}/{$file}", '');

            // download file
            $tmp = "{$path}/" . uniqid();
            $res = $http->get($src);

            if ($res->code != 200) {
                throw new Exception('Download failed.');
            } elseif (!File::write($tmp, $res->body)) {
                throw new Exception('Error writing file.');
            }

            // allow .svg files
            $params->set('upload_extensions', "{$params->get('upload_extensions')},svg");

            // ignore MIME-type check for .svg files
            $params->set('ignore_extensions', $params->get('ignore_extensions') ? "{$params->get('ignore_extensions')},svg" : 'svg');

            if (!(new MediaHelper())->canUpload(['name' => $file, 'tmp_name' => $tmp, 'size' => filesize($tmp)])) {

                File::delete($tmp);

                $queue = $app->getMessageQueue();
                $message = count($queue) ? "{$file}: {$queue[0]['message']}" : '';

                throw new Exception($message);
            }

            // move file
            if (!File::move($tmp, "{$path}/{$file}")) {
                throw new Exception('Error writing file.');
            }

            return $response->withJson(strtr(substr("{$path}/{$file}", strlen(JPATH_ROOT) + 1), '\\', '/'));

        } catch (Exception $e) {

            // delete incomplete file
            File::delete("{$path}/{$file}");

            $this->app->abort(500, $e->getMessage());
        }
    }
}

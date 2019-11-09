<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Registry\Registry;
use YOOtheme\EventSubscriber;

class CustomizerListener extends EventSubscriber
{
    protected $cookie;

    protected $inject = [
        'db' => 'app.db',
        'url' => 'app.url',
        'apikey' => 'app.apikey',
        'config' => 'app.config',
        'secret' => 'app.secret',
        'styles' => 'app.styles',
        'scripts' => 'app.scripts',
    ];

    public function onInit($theme)
    {
        $app = Factory::getApplication();
        $session = Factory::getSession();

        $this->cookie = hash_hmac('md5', $theme->template, $this->secret);
        $theme->isCustomizer = $app->input->get('p') == 'customizer';

        $active = $theme->isCustomizer || $app->input->cookie->get($this->cookie);

        $this->config->add('theme', [
            'cookie' => $this->cookie,
            'customizer' => $active,
        ]);

        // override params
        if ($active) {

            $custom = $app->input->getBase64('customizer');
            $params = $session->get($this->cookie) ?: [];

            foreach ($params as $key => $value) {
                $theme->params->set($key, $value);
            }

            if ($custom && $data = json_decode(base64_decode($custom), true)) {

                foreach ($data as $key => $value) {

                    if (in_array($key, ['config', 'admin', 'user_id'])) {
                        $params[$key] = $value;
                    }

                    $theme->params->set($key, $value);
                }

                $session->set($this->cookie, $params);
            }

        }
    }

    public function onSave($config)
    {
        $user = Factory::getUser();
        $plugin = PluginHelper::getPlugin('installer', 'yootheme');

        if (isset($config['yootheme_apikey'])) {

            if ($plugin && $user->authorise('core.admin', 'com_plugins')) {
                $reg = new Registry($plugin->params);
                $reg->set('apikey', $config['yootheme_apikey']);
                $this->db->executeQuery("UPDATE @extensions SET params = :params WHERE element = 'yootheme' AND folder = 'installer'", ['params' => $reg->toString()]);
            }

            unset($config['yootheme_apikey']);
         }
    }

    public function onSite($theme)
    {
        // is active?
        if (!$this->config->get('theme.customizer')) {
            return;
        }

        // add config
        $this->config->add('customizer', ['id' => $theme->id]);
    }

    public function onAdmin($theme)
    {
        // add config
        $this->config->add('customizer', [
            'template' => basename($theme->path),
            'config' => ['yootheme_apikey' => $this->apikey],
        ]);

        // add assets
        $this->styles->add('customizer', '@assets/css/admin.css');
        $this->scripts->add('customizer', '@app/customizer.min.js', ['uikit', 'config']);
    }

    public function onView($event)
    {
        $app = Factory::getApplication();

        // add data
        if ($app->get('themeFile') !== 'offline.php' && $this->config->get('theme.customizer') && $data = $this->config->get('customizer')) {
            $this->scripts->add('customizer-data', sprintf('var $customizer = %s;', json_encode($data)), 'customizer', 'string');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.init' => ['onInit', 10],
            'theme.site' => ['onSite', -5],
            'theme.admin' => 'onAdmin',
            'theme.save' => 'onSave',
            'view' => 'onView',
        ];
    }
}

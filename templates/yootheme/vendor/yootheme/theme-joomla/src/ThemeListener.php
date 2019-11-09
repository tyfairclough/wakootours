<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;
use YOOtheme\EventSubscriber;
use YOOtheme\Util\Str;

class ThemeListener extends EventSubscriber
{
    /**
     * @var string
     */
    public $path;

    /**
     * @var Collection
     */
    public $config;

    /**
     * @var array
     */
    public $inject = [
        'url' => 'app.url',
        'view' => 'app.view',
        'scripts' => 'app.scripts',
    ];

    /**
     * Constructor.
     *
     * @param string     $path
     * @param Collection $config
     */
    public function __construct($path, $config)
    {
        $this->path = $path;
        $this->config = $config;
    }

    public function onStart($app)
    {
        $app['config']->add('app', [
            'platform' => 'joomla',
            'root' => Uri::root(true),
            'base' => Uri::base(true),
            'user' => Factory::getUser(),
            'token' => Session::getFormToken(),
        ]);

        $app['config']->addFile('theme', "{$this->path}/config/theme.json");

        $app['kernel']->addMiddleware(function ($request, $response, $next) {

            $user = Factory::getUser();

            // no cache
            $response = $response->withHeader('Expires', 'Mon, 1 Jan 2001 00:00:00 GMT');
            $response = $response->withHeader('Cache-Control', 'no-cache, must-revalidate, max-age=0');

            // check user permissions
            if (!$request->getAttribute('allowed') && !$user->authorise('core.edit', 'com_templates') && !$user->authorise('core.edit', 'com_content')) {
                $this->app->abort(403, 'Insufficient User Rights.');
            }

            return $next($request, $response);
        });

        $app->trigger('theme.init', [$this->theme]);
    }

    public function onInit($theme)
    {
        $theme['updater']->add("{$this->path}/updates.php");
        $config = $theme['updater']->update($theme->params->get('config', []), ['theme' => $theme, 'app' => $this->app]);

        // set defaults and config
        $theme->merge($this->config['defaults'], true);
        $theme->merge($config, true);
    }

    public function onInitLate($theme)
    {
        $language = Factory::getLanguage();
        $document = Factory::getDocument();
        $input = Factory::getApplication()->input;

        $language->load('tpl_yootheme', $theme->path);
        $document->setBase(htmlspecialchars(Uri::current()));

        require "{$theme->path}/html/helpers.php";

        $this->url->addResolver(function ($path, $parameters, $secure, $next) {

            $uri = $next($path, $parameters, $secure, $next);

            if (Str::startsWith($uri->getQueryParam('p'), 'theme/')) {

                $query = $uri->getQueryParams();
                $query['option'] = 'com_ajax';
                $query['style'] = $this->theme->id;

                $uri = $uri->withQueryParams($query);
            }

            return $uri;
        });

        if (!$this->app['admin'] && !$theme->isCustomizer && $document->getType() == 'html' && $input->getCmd('tmpl') !== 'component') {
            $this->app->trigger('theme.site', [$theme]);
        }
    }

    public function onSite($theme)
    {
        $app = Factory::getApplication();
        $config = Factory::getConfig();
        $document = Factory::getDocument();

        $theme->set('direction', $document->direction);
        $theme->set('site_url', rtrim(Uri::root(), '/'));
        $theme->set('page_class', $app->getParams()->get('pageclass_sfx'));

        if ($this->app['config']->get('theme.customizer')) {
            HTMLHelper::_('behavior.keepalive');
            $config->set('caching', 0);
        }
    }

    public function onAdmin($theme)
    {
        $user = Factory::getUser();

        if (!$user->authorise('core.admin', 'com_plugins')) {
            $this->app['config']->remove('customizer.sections.settings.fields.settings.items.api-key');
        }
    }

    public function onViewSite()
    {
        $custom = $this->theme->get('custom_js') ?: '';
        $document = Factory::getDocument();

        if ($this->theme->get('jquery') || strpos($custom, 'jQuery') !== false) {
            HTMLHelper::_('jquery.framework');
        }

        if ($custom) {
            if (stripos(trim($custom), '<script') === 0) {
                $document->addCustomTag($custom);
            } else {
                $document->addCustomTag("<script>try { {$custom} } catch (e) { console.error('Custom Theme JS Code: ', e); }</script>");
            }
        }

        // fix markup after email cloaking
        if (PluginHelper::isEnabled('content', 'emailcloak')) {

            $cloak = <<<'EOD'
document.addEventListener('DOMContentLoaded', function() {
Array.prototype.slice.call(document.querySelectorAll('a span[id^="cloak"]')).forEach(function(span) {
    span.innerText = span.textContent;
});
});
EOD;

            $this->scripts->add('emailcloak', $cloak, 'theme-style', 'string');
        }
    }

    public function onContentData($context, $data)
    {
        if ($context != 'com_templates.style') {
            return;
        }

        $this->scripts->add('$customizer-data', sprintf('var $customizer = %s;', json_encode([
            'context' => $context,
            'apikey' => $this->app['apikey'],
            'url' => $this->app->url(($this->app['admin'] ? 'administrator/' : '') . 'index.php?p=customizer&option=com_ajax', ['style' => $data->id]),
        ])), [], 'string');
    }

    public static function getSubscribedEvents()
    {
        return [
            'init' => 'onStart',
            'theme.init' => [['onInit', -5], ['onInitLate', -15]],
            'theme.site' => ['onSite', 10],
            'theme.admin' => 'onAdmin',
            'view.site' => 'onViewSite',
            'content.data' => 'onContentData',
        ];
    }
}

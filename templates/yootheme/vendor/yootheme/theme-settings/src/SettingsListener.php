<?php

namespace YOOtheme\Theme;

use YOOtheme\EventSubscriber;

class SettingsListener extends EventSubscriber
{
    /**
     * @var Collection
     */
    public $config;

    /**
     * @var array
     */
    public $inject = [
        'scripts' => 'app.scripts',
    ];

    /**
     * Constructor.
     *
     * @param string $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function onInit($theme)
    {
        // set defaults
        $theme->merge($this->config['defaults'], true);
    }

    public function onSite($theme)
    {
        // set config
        $theme->merge([
            'body_class' => [$theme->get('page_class')],
            'favicon' => $this->app->url($theme->get('favicon') ?: '@assets/images/favicon.png'),
            'touchicon' => $this->app->url($theme->get('touchicon') ?: '@assets/images/apple-touch-icon.png'),
        ]);
    }

    public function onView($app)
    {
        // combine assets
        if ($this->theme->get('compression') && !$app['config']->get('theme.customizer')) {
            $this->scripts->combine('scripts', '{theme-*,uikit*}');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.init' => 'onInit',
            'theme.site' => ['onSite', 5],
            'view.site' => ['onView', -5],
        ];
    }
}

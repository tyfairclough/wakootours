<?php

namespace YOOtheme\Theme;

use YOOtheme\EventSubscriber;

class CookieListener extends EventSubscriber
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

    public function onInit($theme)
    {
        // set defaults
        $theme->merge($this->config['defaults'], true);
    }

    public function onSite($theme)
    {
        if (!$theme->get('cookie.mode')) {
            return;
        }

        $theme->data->merge([
            'cookie' => [
                'mode' => $theme->get('cookie.mode'),
                'template' => trim($this->view->render('cookie')),
            ],
        ]);

        if (!$this->app['config']->get('theme.customizer')) {

            if ($custom = $theme->get('cookie.custom_js')) {
                $this->scripts->add('cookie-custom_js', "(window.\$load = window.\$load || []).push(function(c,n) {try {{$custom}} catch (e) {console.error(e)} n()});\n", 'theme-data', 'string');
            }

            $this->scripts->add('cookie', "{$this->path}/app/cookie.min.js", 'theme-script', ['defer' => true]);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.init' => 'onInit',
            'theme.site' => 'onSite',
        ];
    }
}

<?php

namespace YOOtheme\Theme;

use YOOtheme\EventSubscriber;

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
        'assets' => 'app.assets',
        'scripts' => 'app.scripts',
        'builder' => 'app.builder',
        'translator' => 'app.translator',
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
        // add assets
        $this->assets->setVersion($theme->options['version']);
        $this->scripts->register('uikit', 'vendor/assets/uikit/dist/js/uikit' . (!$theme->debug ? '.min' : '') . '.js');
        $this->scripts->register('uikit-icons', 'vendor/assets/uikit/dist/js/uikit-icons.min.js', '~uikit');

        // set defaults
        $theme->merge($this->config['defaults'], true);
    }

    public function onAdmin($theme)
    {
        // load Footer builder
        $footer = $theme->get('footer.content');
        $theme->set('footer.content', $this->builder->load(
            $footer ? json_encode($footer->all()) : '{}'
        ));

        // add config
        $platform = $this->app['config']->get('app.platform');

        $this->app['config']->addFile('customizer', "{$this->path}/../theme-{$platform}/config/customizer.json", compact('theme'));
        $this->app['config']->addFile('customizer', "{$this->path}/config/customizer.json", compact('theme'));

        // add locale
        $this->translator->addResource("{$this->path}/languages/{locale}.json");
    }

    public function onSite($theme)
    {
        $this->app->on('view', function () {
            $this->app->trigger('view.site', [$this->app]);
        });
    }

    public function onViewSite($theme)
    {
        if ($this->app['config']->get('theme.customizer')) {
            $this->scripts->add('customizer-site', "{$this->path}/assets/js/customizer.min.js", 'theme-uikit');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.init' => 'onInit',
            'theme.admin' => 'onAdmin',
            'theme.site' => 'onSite',
            'view.site' => ['onViewSite', -5],
        ];
    }
}

<?php

namespace YOOtheme\Theme;

use YOOtheme\EventSubscriber;
use YOOtheme\Util\Collection;

class StylerListener extends EventSubscriber
{
    /**
     * @var string
     */
    public $path;

    /**
     * @var Collection
     */
    public $data;

    /**
     * @var array
     */
    public $inject = [
        'view' => 'app.view',
        'config' => 'app.config',
        'option' => 'app.option',
        'styler' => 'app.styler',
        'styles' => 'app.styles',
        'scripts' => 'app.scripts',
        'locator' => 'app.locator',
    ];

    public function __construct($path)
    {
        $this->path = $path;
        $this->data = new Collection();
    }

    public function onInit($theme)
    {
        // set defaults
        $theme->merge($this->styler->config['defaults'], true);
    }

    public function onSite($theme)
    {
        // set fonts, deprecated in v1.5
        if ($fonts = $theme->config->get('fonts', [])) {
            $this->styles->add('google-fonts', $this->app->url('https://fonts.googleapis.com/css', [
                'family' => implode('|', array_map(function ($font) {
                    return trim($font['name'], "'") . ($font['variants'] ? ':' . $font['variants'] : '');
                }, $fonts)),
                'subset' => rtrim(implode(',', array_unique(array_map('trim', explode(',', implode(',', array_map(function ($font) {
                    return $font['subsets'];
                }, $fonts)))))), ',') ?: null,
            ]));
        }
    }

    public function onAdmin($theme)
    {
        // check if theme css needs to be updated
        $style = $this->locator->find("@theme/css/theme.{$theme->id}.css");
        $update = !$style || filemtime(__FILE__) >= filemtime($style);

        $themes = $this->styler->getThemes();
        $styler = [
            'route' => $this->app->route('theme/styles'),
            'worker' => $this->app->url("{$this->path}/app/worker.min.js", ['ver' => $theme->options['version']]),
            'styles' => array_map(function ($theme, $id) {
                $theme['id'] = $id;
                unset($theme['file']);
                return $theme;
            }, $themes, array_keys($themes)),
            'update' => $update,
        ];

        // add config
        $this->config->add('customizer', [
            'sections' => compact('styler'),
        ]);
    }

    public function onAdminLibrary()
    {
        $this->data->set('library', new Collection($this->option->get('styler.library')));
    }

    public function onView()
    {
        if ($data = $this->data->all()) {
            $this->scripts->add('styler-data', sprintf('var $styler = %s;', json_encode($data)), 'customizer-styler', 'string');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.init' => 'onInit',
            'theme.site' => ['onSite', -5],
            'theme.admin' => [['onAdmin', 0], ['onAdminLibrary', -10]],
            'view' => 'onView',
        ];
    }
}

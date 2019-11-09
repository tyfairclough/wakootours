<?php

use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Registry\Registry;
use YOOtheme\Theme\Joomla\ChildThemeListener;
use YOOtheme\Theme\Joomla\CustomizerListener;
use YOOtheme\Theme\Joomla\SystemCheck;
use YOOtheme\Theme\Joomla\ThemeListener;
use YOOtheme\Theme\Joomla\UrlListener;

$config = [

    'name' => 'yootheme/joomla-theme',

    'main' => function ($app) {

        $app['trans'] = $app->protect(function ($id) {
            return Text::_($id);
        });

        $app['apikey'] = function () {

            $plugin = PluginHelper::getPlugin('installer', 'yootheme');

            return $plugin ? (new Registry($plugin->params))->get('apikey') : false;
        };

        $app['systemcheck'] = function () {
            return new SystemCheck();
        };

        $app['locator']
            ->addPath("{$this->path}/app", 'app')
            ->addPath("{$this->path}/assets", 'assets');

        $app->subscribe(new ThemeListener($this->path, $this->config))
            ->subscribe(new ChildThemeListener())
            ->subscribe(new CustomizerListener())
            ->subscribe(new UrlListener());
    },

    'routes' => function ($routes) {

        $routes->get('/customizer', 'YOOtheme\Theme\Joomla\CustomizerController:index');
        $routes->post('/customizer', 'YOOtheme\Theme\Joomla\CustomizerController:save');

    },

    'config' => [

        'defaults' => [

            'menu' => [
                'positions' => [
                    'navbar' => 'mainmenu',
                    'mobile' => 'mainmenu',
                ],
            ],

            'mobile' => [
                'toggle' => 'left',
            ],

            'post' => [
                'width' => '',
                'padding' => '',
                'content_width' => '',
                'image_margin' => 'medium',
                'image_width' => '',
                'image_height' => '',
                'header_align' => 0,
                'title_margin' => 'default',
                'meta_margin' => 'default',
                'meta_style' => 'sentence',
                'content_margin' => 'medium',
                'content_dropcap' => 0,
            ],

            'blog' => [
                'width' => '',
                'padding' => '',
                'column_gutter' => 0,
                'column_breakpoint' => 'm',
                'image_margin' => 'medium',
                'image_width' => '',
                'image_height' => '',
                'header_align' => 0,
                'title_style' => '',
                'title_margin' => 'default',
                'meta_margin' => 'default',
                'content_excerpt' => 0,
                'content_length' => '',
                'content_margin' => 'medium',
                'content_align' => 0,
                'button_style' => 'default',
                'button_margin' => 'medium',
                'navigation' => 'pagination',
            ],

            'media_folder' => 'yootheme',
            'search_module' => 'mod_search',

        ],

    ],

];

return defined('_JEXEC') ? $config : false;

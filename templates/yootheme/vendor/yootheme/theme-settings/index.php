<?php

use YOOtheme\Theme\SettingsListener;

return [

    'name' => 'yootheme/settings',

    'main' => function ($app) {

        $app->subscribe(new SettingsListener($this->config));

    },

    'routes' => function ($routes) {

        $routes->get('/cache', 'YOOtheme\Theme\CacheController:index');
        $routes->post('/cache/clear', 'YOOtheme\Theme\CacheController:clear');
        $routes->get('/systemcheck', 'YOOtheme\Theme\SystemCheckController:index');

    },

    'config' => [

        'defaults' => [
            'lazyload' => true,
        ],

    ],

];

<?php

use YOOtheme\Theme\Styler;
use YOOtheme\Theme\StylerListener;

return [

    'name' => 'yootheme/styler',

    'main' => function ($app) {

        $app['styler'] = function () {
            return new Styler($this->config);
        };

        $app->subscribe(new StylerListener($this->path));
    },

    'routes' => function ($routes) {

        $routes->get('/theme/styles', 'YOOtheme\Theme\StylerController:loadStyle');
        $routes->post('/theme/styles', 'YOOtheme\Theme\StylerController:saveStyle');
        $routes->post('/styler/library', 'YOOtheme\Theme\StylerController:addStyle');
        $routes->delete('/styler/library', 'YOOtheme\Theme\StylerController:removeStyle');

    },

    'config' => [

        'defaults' => [
            'custom_less' => '',
            'less' => [],
        ],

        'ignore_less' => [],

    ],

];

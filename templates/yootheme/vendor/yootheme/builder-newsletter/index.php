<?php

use YOOtheme\Encryption;

return [

    'name' => 'yootheme/builder-newsletter',

    'main' => function ($app) {

        $app['encryption'] = function ($app) {
            return new Encryption($app['secret'], $app['csrf']->generate());
        };

    },

    'routes' => function ($routes) {

        $routes->post('theme/newsletter/list', 'YOOtheme\Builder\Newsletter\NewsletterController:lists');
        $routes->post('theme/newsletter/subscribe', 'YOOtheme\Builder\Newsletter\NewsletterController:subscribe', ['csrf' => false, 'allowed' => true]);

    },

    'events' => [

        'builder.init' => function ($builder) {
            $builder->addTypePath("{$this->path}/elements/*/element.json");
        },

    ],

];

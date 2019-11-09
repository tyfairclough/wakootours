<?php

use YOOtheme\Builder;
use YOOtheme\Builder\BuilderListener;

return [

    'name' => 'yootheme/builder',

    'main' => function ($app) {

        $this['builder'] = function () use ($app) {

            $builder = new Builder([$app['config'], 'load'], [$app['view'], 'render'], [
                'app' => $app,
                'view' => $app['view'],
                'theme' => $app->theme,
            ]);

            $app['config']->addFile('builder', "{$this->path}/config/builder.json");
            $app['config']->addFilter('builder', function ($value, $file, $config) {
                return $config->get("builder.{$value}");
            });

            $app->trigger('builder.init', [$builder]);

            return $builder;
        };

        $app['builder'] = function () {
            return $this['builder'];
        };

        $app->subscribe(new BuilderListener($this->path));
    },

    'routes' => function ($routes) {

        $routes->post('/builder/encode', 'YOOtheme\Builder\BuilderController:encodeLayout');
        $routes->post('/builder/library', 'YOOtheme\Builder\BuilderController:addElement');
        $routes->delete('/builder/library', 'YOOtheme\Builder\BuilderController:removeElement');

    },

];

<?php

use YOOtheme\Builder\Joomla\BuilderListener;
use YOOtheme\Builder\Joomla\ContentListener;

$config = [

    'name' => 'yootheme/builder-joomla',

    'main' => function ($app) {

        $app->subscribe(new ContentListener());
        $app->subscribe(new BuilderListener($this->path));

    },

    'routes' => function ($routes) {

        $routes->post('/builder/image', 'YOOtheme\Builder\Joomla\BuilderController:loadImage');

    },

];

return defined('_JEXEC') ? $config : false;

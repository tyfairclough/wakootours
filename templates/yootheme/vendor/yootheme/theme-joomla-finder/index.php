<?php

use YOOtheme\Theme\Joomla\FinderListener;

$config = [

    'name' => 'yootheme/joomla-finder',

    'main' => function ($app) {

        $app->subscribe(new FinderListener());

    },

    'routes' => function ($routes) {

        $routes->get('/finder', 'YOOtheme\Theme\Joomla\FinderController:index');
        $routes->post('/finder/rename', 'YOOtheme\Theme\Joomla\FinderController:rename');

    },

];

return defined('_JEXEC') ? $config : false;

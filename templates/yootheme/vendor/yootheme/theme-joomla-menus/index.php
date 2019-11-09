<?php

use YOOtheme\Theme\Joomla\MenusListener;

$config = [

    'name' => 'yootheme/joomla-menus',

    'main' => function ($app) {

        $app->subscribe(new MenusListener($this->path));

    },

    'routes' => function ($routes) {

        $routes->get('/items', 'YOOtheme\Theme\Joomla\MenusController:getItems');

    },

];

return defined('_JEXEC') ? $config : false;

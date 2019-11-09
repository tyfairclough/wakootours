<?php

use Joomla\CMS\Factory;
use YOOtheme\Theme\Joomla\ModulesListener;

$config = [

    'name' => 'yootheme/joomla-modules',

    'main' => function ($app) {

        $app->extend('view', function ($view) {
            $view->addFunction('countModules', function ($condition) {
                return Factory::getDocument()->countModules($condition);
            });
        });

        $app->subscribe(new ModulesListener($this->path));
    },

    'routes' => function ($routes) {

        $routes->get('/module', 'YOOtheme\Theme\Joomla\ModulesController:getModule');
        $routes->post('/module', 'YOOtheme\Theme\Joomla\ModulesController:saveModule');
        $routes->get('/modules', 'YOOtheme\Theme\Joomla\ModulesController:getModules');
        $routes->get('/positions', 'YOOtheme\Theme\Joomla\ModulesController:getPositions');

    },

];

return defined('_JEXEC') ? $config : false;

<?php

use YOOtheme\Theme\Joomla\ArticlesListener;

$config = [

    'name' => 'yootheme/joomla-articles',

    'main' => function ($app) {

        $app->subscribe(new ArticlesListener($this->path));

    },

];

return defined('_JEXEC') ? $config : false;

<?php

use YOOtheme\Theme\Joomla\EditorListener;

$config = [

    'name' => 'yootheme/joomla-editor',

    'main' => function ($app) {

        $app->subscribe(new EditorListener());

    },

];

return defined('_JEXEC') ? $config : false;

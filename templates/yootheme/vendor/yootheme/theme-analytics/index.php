<?php

use YOOtheme\Theme\AnalyticsListener;

return [

    'name' => 'yootheme/theme-analytics',

    'main' => function ($app) {

        $app->subscribe(new AnalyticsListener($this->path));

    },

];

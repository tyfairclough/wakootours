<?php

use YOOtheme\Application;

$autoloader = require __DIR__.'/vendor/autoload.php';

return new Application(compact('autoloader'));

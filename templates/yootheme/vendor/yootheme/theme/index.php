<?php

use YOOtheme\ConfigManager;
use YOOtheme\Theme\ThemeListener;
use YOOtheme\Util\File;

return [

    'name' => 'yootheme/theme',

    'main' => function ($app) {

        $app['config'] = function ($app) {

            $config = new ConfigManager($app['path.cache'], compact('app'));
            $config->addFilter('url', function ($value, $file) use ($app) {

                if ($value[0] !== '@') {
                    $value = File::resolvePath(dirname($file), $value);
                }

                return $app->url($value);
            });

            return $config;
        };

        $app['locator']
            ->addPath("{$this->path}/builder", 'builder')
            ->addPath("{$this->path}/assets", 'assets');

        $app->extend('image', function ($image) use ($app) {

            $convert = $this->theme->get('webp') && is_callable('imagewebp') && strpos($app['request']->getHeaderLine('Accept'), 'image/webp') !== false
                ? ['png' => 'webp,100', 'jpeg' => 'webp,85']
                : false;

            $image->addLoader(function ($image) use ($convert) {

                $params = $image->getAttribute('params', []);

                // convert image type?
                if ($convert && !isset($params['type'])) {

                    $type = $image->getType();

                    if (isset($convert[$type])) {
                        $params['type'] = $convert[$type];
                    }

                }

                // image covers
                if (isset($params['covers']) && $params['covers'] && !isset($params['sizes'])) {
                    $img = $image->apply($params);
                    $ratio = round($img->width / $img->height * 100);
                    $params['sizes'] = "(max-aspect-ratio: {$img->width}/{$img->height}) {$ratio}vh";
                }

                // set default srcset
                if (isset($params['srcset']) && $params['srcset'] === '1') {
                    $params['srcset'] = '768,1024,1366,1600,1920,200%';
                }

                $image->setAttribute('params', $params);

            });

        });

        $app->extend('routes', function ($routes) use ($app) {
            $routes->getRoute('@image')->setAttribute('save', !$app['config']->get('theme.customizer'));
        });

        $app->subscribe(new ThemeListener($this->path, $this->config));
    },

    'require' => 'yootheme/framework',

    'include' => [
        '../builder*/index.php',
        '../styler/index.php',
        '../theme-*/index.php',
    ],

    'config' => [

        'defaults' => [

            'menu' => [
                'items' => [],
                'positions' => [],
            ],

            'site' => [
                'layout' => 'full',
                'boxed' => [
                    'alignment' => 1,
                ],
                'image_size' => 'cover',
                'image_position' => 'center-center',
                'image_effect' => 'fixed',
                'toolbar_width' => 'default',
            ],

            'header' => [
                'layout' => 'horizontal-right',
                'width' => 'default',
            ],

            'navbar' => [
                'dropdown_align' => 'left',
                'toggle_menu_style' => 'default',
                'offcanvas' => [
                    'mode' => 'slide',
                ],
            ],

            'mobile' => [
                'breakpoint' => 'm',
                'logo' => 'center',
                'toggle' => 'left',
                'search' => 'right',
                'menu_style' => 'default',
                'animation' => 'offcanvas',
                'offcanvas' => [
                    'mode' => 'slide',
                ],
                'dropdown' => 'slide',
            ],

            'top' => [
                'style' => 'default',
                'width' => 'default',
                'breakpoint' => 'm',
                'image_position' => 'center-center',
            ],

            'sidebar' => [
                'width' => '1-4',
                'min_width' => '200',
                'breakpoint' => 'm',
                'first' => 0,
                'gutter' => '',
                'divider' => 0,
            ],

            'bottom' => [
                'style' => 'default',
                'width' => 'default',
                'breakpoint' => 'm',
                'image_position' => 'center-center',
            ],

            'footer' => [
                'content' => '',
            ],

        ],

    ],

];

<?php

use YOOtheme\Util\Arr;
use YOOtheme\Util\Str;

return [

    '1.20.4.1' => function ($config, array $params) {

        // Less
        if (Arr::has($config, 'less.@theme-toolbar-padding-vertical')) {

            Arr::set($config, 'less.@theme-toolbar-padding-top', Arr::get($config, 'less.@theme-toolbar-padding-vertical'));
            Arr::set($config, 'less.@theme-toolbar-padding-bottom', Arr::get($config, 'less.@theme-toolbar-padding-vertical'));

            Arr::remove($config, 'less.@theme-toolbar-padding-vertical');
        }

        // Header settings
        if (Arr::has($config, 'site.toolbar_fullwidth')) {

            if (Arr::get($config, 'site.toolbar_fullwidth')) {
                Arr::set($config, 'site.toolbar_width', 'expand');
            }

            Arr::remove($config, 'site.toolbar_fullwidth');
        }

        return $config;
    },

    '1.20.0-beta.7' => function ($config, array $params) {

        // Remove empty menu items
        if (Arr::has($config, 'menu.items')) {
            Arr::set($config, 'menu.items', array_filter((array) Arr::get($config, 'menu.items', [])));
        }

        return $config;
    },

    '1.20.0-beta.6' => function ($config, array $params) {

        // Header settings
        if (Arr::has($config, 'header.fullwidth')) {

            if (Arr::get($config, 'header.fullwidth')) {
                Arr::set($config, 'header.width', 'expand');
            }

            Arr::remove($config, 'header.fullwidth');
        }

        if (Arr::get($config, 'header.layout') == 'toggle-offcanvas') {
            Arr::set($config, 'header.layout', 'offcanvas-top-a');
        }

        if (Arr::get($config, 'header.layout') == 'toggle-modal') {
            Arr::set($config, 'header.layout', 'modal-center-a');
            Arr::set($config, 'navbar.toggle_menu_style', 'primary');
            Arr::set($config, 'navbar.toggle_menu_center', true);
        }

        if (Arr::get($config, 'mobile.animation') == 'modal' && !Arr::has($config, 'mobile.menu_center')) {
            Arr::set($config, 'mobile.menu_style', 'primary');
            Arr::set($config, 'mobile.menu_center', true);
            Arr::set($config, 'mobile.menu_center_vertical', true);
        }

        if (Arr::get($config, 'site.boxed.padding') && (!Arr::has($config, 'site.boxed.margin_top') || !Arr::has($config, 'site.boxed.margin_bottom'))) {
            Arr::set($config, 'site.boxed.margin_top', true);
            Arr::set($config, 'site.boxed.margin_bottom', true);
        }

        if (!Arr::has($config, 'cookie.mode') && Arr::get($config, 'cookie.active')) {
            Arr::set($config, 'cookie.mode', 'notification');
        }
        if (!Arr::has($config, 'cookie.button_consent_style')) {
            Arr::set($config, 'cookie.button_consent_style', Arr::get($config, 'cookie.button_style'));
        }

        foreach (['top', 'bottom'] as $position) {

            if (Arr::get($config, "{$position}.vertical_align") === true) {
                Arr::set($config, "{$position}.vertical_align", 'middle');
            }

            if (Arr::get($config, "{$position}.style") === 'video') {
                Arr::set($config, "{$position}.style", 'default');
            }

            if (Arr::get($config, "{$position}.width") == '1') {
                Arr::set($config, "{$position}.width", 'default');
            }

            if (Arr::get($config, "{$position}.width") == '2') {
                Arr::set($config, "{$position}.width", 'small');
            }

            if (Arr::get($config, "{$position}.width") == '3') {
                Arr::set($config, "{$position}.width", 'expand');
            }
        }

        foreach (Arr::get($config, 'less', []) as $key => $value) {

            if (in_array($key, ['@heading-primary-line-height', '@heading-hero-line-height-m', '@heading-hero-line-height'])) {
                Arr::remove($config, "less.{$key}");
            } elseif (Str::contains($key, ['heading-primary-', 'heading-hero-'])) {
                Arr::set($config, 'less.' . strtr($key, [
                    'heading-primary-line-height-l' => 'heading-medium-line-height',
                    'heading-primary-' => 'heading-medium-',
                    'heading-hero-line-height-l' => 'heading-xlarge-line-height',
                    'heading-hero-' => 'heading-xlarge-',
                ]), $value);
                Arr::remove($config, "less.{$key}");
            }

        }

        list($style) = explode(':', Arr::get($config, 'style'));
        $less = Arr::get($config, 'less', []);

        foreach ([
            [['fuse', 'horizon', 'joline', 'juno', 'lilian', 'vibe', 'yard'], ['medium', 'small']],
            [['trek', 'fjord'], ['medium', 'large']],
            [['juno', 'vibe', 'yard'], ['xlarge', 'medium']],
            [['district', 'florence', 'flow', 'nioh-studio', 'summit', 'vision'], ['xlarge', 'large']],
            [['lilian'], ['xlarge', '2xlarge']],
        ] as $change) {

            list($styles, $transform) = $change;
            if (in_array($style, $styles)) {
                foreach ($less as $key => $value) {
                    if (Str::contains($key, "heading-{$transform[0]}")) {
                        Arr::set($config, 'less.' . str_replace("heading-{$transform[0]}", "heading-{$transform[1]}", $key), $value);
                    }
                }
            }

        }

        return $config;
    },

];

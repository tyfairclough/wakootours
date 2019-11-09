<?php

use YOOtheme\Util\Arr;

return [

    'transforms' => [

        'render' => function ($node, array $params) use ($file) {

            /**
             * @var $app
             * @var $theme
             * @var $builder
             */
            extract($params);

            $center = [];
            $markers = [];

            foreach ($node->children as $child) {

                if (empty($child->props['location'])) {
                    continue;
                }

                list($lat, $lng) = explode(',', $child->props['location']);

                if (empty($center)) {
                    $center = ['lat' => (float) $lat, 'lng' => (float) $lng];
                }

                if (!empty($child->props['hide'])) {
                    continue;
                }

                $markers[] = [
                    'lat' => (float) $lat,
                    'lng' => (float) $lng,
                    'title' => $child->props['title'],
                    'content' => $builder->render($child, ['element' => $node->props]),
                    'show_popup' => !empty($child->props['show_popup']),
                ];
            }

            // map options
            $node->options = Arr::pick($node->props, ['type', 'zoom', 'zooming', 'dragging', 'controls', 'styler_invert_lightness', 'styler_hue', 'styler_saturation', 'styler_lightness', 'styler_gamma', 'popup_max_width']);
            $node->options['center'] = $center ?: ['lat' => 53.5503, 'lng' => 10.0006];
            $node->options['markers'] = $markers;
            $node->options['lazyload'] = $theme->get('lazyload', false);
            $node->options = array_filter($node->options, function ($value) { return isset($value); });

            if ($key = $theme->get('google_maps')) {
                $app['scripts']->add('google-api', 'https://www.google.com/jsapi', [], ['defer' => true]);
                $app['scripts']->add('google-maps', "var \$google_maps = '{$key}';", [], ['defer' => true, 'type' => 'string']);
            } else {

                // add scripts, styles
                $baseUrl = 'https://cdn.jsdelivr.net/npm/leaflet@1.5.1/dist';
                $node->options['baseUrl'] = $baseUrl;
                $app['scripts']->add('leaflet', "{$baseUrl}/leaflet.js", [], ['defer' => true]);

            }

            $app['scripts']->add('builder-map', "{$file['dirname']}/app/map.min.js", [], ['defer' => true]);
        },

    ],

    'updates' => [

        '1.20.0-beta.1.1' => function ($node, array $params) {

            if (isset($node->props['maxwidth_align'])) {
                $node->props['block_align'] = $node->props['maxwidth_align'];
                unset($node->props['maxwidth_align']);
            }

        },

        '1.18.0' => function ($node, array $params) {

            if (!isset($node->props['width_breakpoint']) && @$node->props['width_max'] === false) {
                $node->props['width_breakpoint'] = true;
            }

        },

    ],

];

<?php

return [

    'transforms' => [

        'render' => function ($node, array $params) {

            if (empty($node->props['image']) && empty($node->props['hover_image'])) {
                $node->props['image'] = $params['app']->url('@assets/images/element-image-placeholder.png');
            }

        },

    ],

    'updates' => [

        '1.20.0-beta.1.1' => function ($node, array $params) {

            if (isset($node->props['maxwidth_align'])) {
                $node->props['block_align'] = $node->props['maxwidth_align'];
                unset($node->props['maxwidth_align']);
            }

        },

        '1.20.0-beta.0.1' => function ($node, array $params) {

            if (@$node->props['title_style'] === 'heading-primary') {
                $node->props['title_style'] = 'heading-medium';
            }

            /**
             * @var $theme
             */
            extract($params);

            $style = explode(':', $theme->config->get('style'))[0];

            if (in_array($style, ['craft', 'district', 'jack-backer', 'tomsen-brody', 'vision', 'florence', 'max', 'nioh-studio', 'sonic', 'summit', 'trek'])) {

                if (@$node->props['title_style'] === 'h1' || (empty($node->props['title_style']) && @$node->props['title_element'] === 'h1')) {
                    $node->props['title_style'] = 'heading-small';
                }

            }

            if (in_array($style, ['florence', 'max', 'nioh-studio', 'sonic', 'summit', 'trek'])) {

                if (@$node->props['title_style'] === 'h2') {
                    $node->props['title_style'] = @$node->props['title_element'] === 'h1' ? '' : 'h1';
                } elseif (empty($node->props['title_style']) && @$node->props['title_element'] === 'h2') {
                    $node->props['title_style'] = 'h1';
                }

            }

            if (in_array($style, ['fuse', 'horizon', 'joline', 'juno', 'lilian', 'vibe', 'yard'])) {

                if (@$node->props['title_style'] === 'heading-medium') {
                    $node->props['title_style'] = 'heading-small';
                }

            }

            if (in_array($style, ['copper-hill'])) {

                if (@$node->props['title_style'] === 'heading-medium') {
                    $node->props['title_style'] = @$node->props['title_element'] === 'h1' ? '' : 'h1';
                } elseif (@$node->props['title_style'] === 'h1') {
                    $node->props['title_style'] = @$node->props['title_element'] === 'h2' ? '' : 'h2';
                } elseif (empty($node->props['title_style']) && @$node->props['title_element'] === 'h1') {
                    $node->props['title_style'] = 'h2';
                }

            }

            if (in_array($style, ['trek', 'fjord'])) {

                if (@$node->props['title_style'] === 'heading-medium') {
                    $node->props['title_style'] = 'heading-large';
                }

            }

        },

        '1.19.0-beta.0.1' => function ($node, array $params) {

            if (@$node->props['meta_align'] === 'top') {
                $node->props['meta_align'] = 'above-title';
            }

            if (@$node->props['meta_align'] === 'bottom') {
                $node->props['meta_align'] = 'below-title';
            }

            $node->props['link_type'] = 'element';

        },

        '1.18.10.3' => function ($node, array $params) {

            if (@$node->props['meta_align'] === 'top') {
                if (!empty($node->props['meta_margin'])) {
                    $node->props['title_margin'] = $node->props['meta_margin'];
                }
                $node->props['meta_margin'] = '';
            }

        },

        '1.18.0' => function ($node, array $params) {

            if (!isset($node->props['overlay_image'])) {
                $node->props['overlay_image'] = @$node->props['image2'];
            }

            if (!isset($node->props['image_box_decoration']) && @$node->props['image_box_shadow_bottom'] === true) {
                $node->props['image_box_decoration'] = 'shadow';
            }

            if (!isset($node->props['meta_color']) && @$node->props['meta_style'] === 'muted') {
                $node->props['meta_color'] = 'muted';
                $node->props['meta_style'] = '';
            }

        },

    ],

];

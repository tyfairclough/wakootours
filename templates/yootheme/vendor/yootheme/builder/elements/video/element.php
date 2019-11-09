<?php

return [

    'transforms' => [

        'render' => function ($node, array $params) {

            if (empty($node->props['video'])) {
                $node->props['video_poster'] = $params['app']->url('@assets/images/element-video-placeholder.png');
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

        '1.18.0' => function ($node, array $params) {

            if (!isset($node->props['video_box_decoration']) && @$node->props['video_box_shadow_bottom'] === true) {
                $node->props['video_box_decoration'] = 'shadow';
            }

        },

    ],

];

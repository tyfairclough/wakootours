<?php

return [

    'updates' => [

        '1.20.0-beta.1.1' => function ($node, array $params) {

            if (isset($node->props['maxwidth_align'])) {
                $node->props['block_align'] = $node->props['maxwidth_align'];
                unset($node->props['maxwidth_align']);
            }

        },

        '1.18.10.1' => function ($node, array $params) {

            if (isset($node->props['image_inline_svg'])) {
                $node->props['image_svg_inline'] = $node->props['image_inline_svg'];
                unset($node->props['image_inline_svg']);
            }

        },

    ],

];

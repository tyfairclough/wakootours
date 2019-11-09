<?php

return [

    'transforms' => [

        'render' => function ($node, array $params) use ($file) {

            /**
             * @var $app
             */
            extract($params);

            $provider = (array) $node->props['provider'];

            $node->form = [
                'action' => $app->route('theme/newsletter/subscribe'),
            ];

            $node->settings = $app['encryption']->encrypt(array_merge(
                $provider,
                (array) $node->props[$provider['name']]
            ));

            $app['scripts']->add('newsletter', "{$file['dirname']}/../../app/newsletter.min.js", [], ['defer' => true]);
        },

    ],

    'updates' => [

        '1.20.0-beta.1.1' => function ($node, array $params) {

            if (isset($node->props['maxwidth_align'])) {
                $node->props['block_align'] = $node->props['maxwidth_align'];
                unset($node->props['maxwidth_align']);
            }

        },

    ],

];

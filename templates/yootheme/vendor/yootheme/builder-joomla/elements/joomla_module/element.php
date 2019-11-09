<?php

use Joomla\CMS\Helper\ModuleHelper;

return [

    'transforms' => [

        'render' => function ($node, array $params) {

            $load = new ReflectionMethod('Joomla\CMS\Helper\ModuleHelper', 'load');
            $load->setAccessible(true);

            foreach ($load->invoke(null) as $module) {

                if ($node->props['module'] !== $module->id) {
                    continue;
                }

                $node->attrs['class'] = array_merge($node->attrs['class'], $module->config->get('class', []));
                $node->props = $module->config->merge($node->props, true)->all();
                $node->module = (object) [
                    'title' => $module->title,
                    'content' => ModuleHelper::renderModule($module),
                ];

                break;
            }

            // return false, if no module content was found
            if (empty($node->props['module']) || empty($node->module->content)) {
                return false;
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

    ],

];

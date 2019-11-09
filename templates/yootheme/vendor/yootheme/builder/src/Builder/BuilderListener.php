<?php

namespace YOOtheme\Builder;

use YOOtheme\EventSubscriber;

class BuilderListener extends EventSubscriber
{
    /**
     * @var string
     */
    public $path;

    /**
     * @var array
     */
    public $inject = [
        'config' => 'app.config',
        'option' => 'app.option',
        'builder' => 'app.builder',
        'scripts' => 'app.scripts',
    ];

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function onInit($builder)
    {
        $builder->addTypePath("{$this->path}/elements/*/element.json");
        $builder->addTransform('preload', new UpdateTransform($this->theme->options['version']));
        $builder->addTransform('preload', new DefaultTransform());
        $builder->addTransform('precontent', new NormalizeTransform());
    }

    public function onSite($theme)
    {
        $this->builder->addTransform('preload', function ($node, $params) {

            static $id;

            /**
             * @var $type
             * @var $prefix
             */
            extract($params);

            if ($node->type == 'layout') {
                $id = 0;
            }

            if (($type->element || $type->container) && isset($prefix)) {

                $node->id = "{$prefix}#" . $id++;

                if ($this->config->get('theme.customizer') && $type->element) {
                    $node->attrs['data-id'] = $node->id;
                }
            }

        });

        $this->builder->addTransform('prerender', function ($node, array $params) {
            /**
             * @var $type
             */
            extract($params);

            if ($type->container) {
                $node->parent = !empty($node->children);
            }
        });

        $this->builder->addTransform('render', function ($node) {
            if (empty($node->children) && !empty($node->parent)) {
                return false;
            }
        });

        if (!$this->config->get('theme.customizer')) {
            $this->builder->addTransform('prerender', function ($node, $params) {
                if (isset($node->props['status']) && $node->props['status'] === 'disabled') {
                    return false;
                }
            });
        }

        $this->builder->addTransform('prerender', new NormalizeTransform());
        $this->builder->addTransform('prerender', new PlaceholderTransform());
        $this->builder->addTransform('render', new ElementTransform());
    }

    public function onAdmin()
    {
        $library = array_map('json_encode', $this->option->get('library', []));

        $data = json_encode([
            'elements' => $this->builder->types,
            'library' => array_map([$this->builder, 'load'], $library),
        ]);

        $this->scripts->add('builder-data', "var \$builder = {$data};", [], 'string');
    }

    public static function getSubscribedEvents()
    {
        return [
            'builder.init' => 'onInit',
            'theme.site' => 'onSite',
            'theme.admin' => ['onAdmin', -10],
        ];
    }
}

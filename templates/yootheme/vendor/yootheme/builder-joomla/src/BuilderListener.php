<?php

namespace YOOtheme\Builder\Joomla;

use Joomla\CMS\HTML\HTMLHelper;
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
        'view' => 'app.view',
        'builder' => 'app.builder',
    ];

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function onInit($builder)
    {
        $builder->addTypePath("{$this->path}/elements/*/element.json");

        // load child theme elements
        if ($childTheme = $this->theme->get('child_theme')) {
            $builder->addTypePath("{$this->theme->path}_{$childTheme}/builder/*/element.json");
        }
    }

    public function onSite()
    {
        HTMLHelper::register('builder', function ($node, $params = []) {

            // support old builder arguments
            if (!is_string($node)) {
                $node = json_encode($node);
            }

            if (is_string($params)) {
                $params = ['prefix' => $params];
            }

            return $this->builder->render($node, $params);
        });

        $this->view->addLoader(function ($name, $parameters, $next) {
            $content = $next($name, $parameters);
            return empty($parameters['prefix']) || $parameters['prefix'] !== 'page' ? \JHtmlContent::prepare($content) : $content;
        }, '*/builder/elements/layout/templates/template.php');
    }

    public static function getSubscribedEvents()
    {
        return [
            'builder.init' => 'onInit',
            'theme.site' => 'onSite',
        ];
    }
}

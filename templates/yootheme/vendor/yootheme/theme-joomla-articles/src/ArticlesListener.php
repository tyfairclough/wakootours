<?php

namespace YOOtheme\Theme\Joomla;

use YOOtheme\EventSubscriber;
use YOOtheme\Joomla\ArticleHelper;

class ArticlesListener extends EventSubscriber
{
    /**
     * @var string
     */
    public $path;

    /**
     * @var array
     */
    public $inject = [
        'admin' => 'app.admin',
        'apikey' => 'app.apikey',
        'scripts' => 'app.scripts',
    ];

    /**
     * Constructor.
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    public function onData($context, $data)
    {
        if ($context != 'com_content.article' || !$data->id) {
            return;
        }

        $this->scripts
            ->add('$articles', "{$this->path}/app/articles.min.js", '$articles-data')
            ->add('$articles-data', sprintf('var $articles = %s;', json_encode([
                'context' => $context,
                'apikey' => $this->apikey,
                'url' => $this->app->url(($this->admin ? 'administrator/' : '') . 'index.php?p=customizer&option=com_ajax', [
                    'section' => 'builder',
                    'site' => ArticleHelper::getRoute($data, 0),
                ]),
            ])), [], 'string');
    }

    public function onBeforeSave($context, $article, $input)
    {
        if ($context != 'com_content.form' && $context != 'com_content.article') {
            return;
        }

        $form = $input->get('jform', null, 'RAW');

        // keep builder data, when JText filters are active
        if (isset($form['articletext']) && preg_match('/<!--\s{.*}\s-->\s*$/', $form['articletext'], $matches)) {
            $article->fulltext = $matches[0];
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'content.data' => 'onData',
            'content.beforeSave' => 'onBeforeSave',
        ];
    }
}

<?php

namespace YOOtheme\Theme\Joomla;

use YOOtheme\ContainerTrait;

class ModulesController
{
    use ContainerTrait;

    /**
     * @var ModulesHelper
     */
    public $helper;

    /**
     * @var array
     */
    public $inject = [
        'db' => 'app.db',
        'builder' => 'app.builder',
    ];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->helper = new ModulesHelper();
    }

    public function getModule($id, $response)
    {
        $query = 'SELECT id, content FROM @modules WHERE id = :id';
        $module = $this->db->fetchObject($query, ['id' => $id]);
        $module->content = $this->builder->load($module->content);

        return $response->withJson($module);
    }

    public function saveModule($id, $content, $response)
    {
        $this->db->update('@modules', [
            'content' => json_encode($content),
        ], ['id' => $id]);

        return $response->withJson(['message' => 'success']);
    }

    public function getModules($response)
    {
        return $response->withJson($this->helper->getModules());
    }

    public function getPositions($response)
    {
        return $response->withJson($this->helper->getPositions());
    }
}

<?php

namespace YOOtheme\Builder;

use YOOtheme\ContainerTrait;

class BuilderController
{
    use ContainerTrait;

    public $inject = [
        'option' => 'app.option',
        'builder' => 'app.builder',
    ];

    public function encodeLayout($layout, $response)
    {
        return $response->withJson($this->builder->load(json_encode($layout)));
    }

    public function addElement($id, $element, $response)
    {
        $this->option->set("library.{$id}", $element);

        return $response->withJson(['message' => 'success']);
    }

    public function removeElement($id, $response)
    {
        $this->option->remove("library.{$id}");

        return $response->withJson(['message' => 'success']);
    }
}

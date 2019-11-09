<?php

namespace YOOtheme\Theme\Joomla;

class MenusController
{
    /**
     * @var MenusHelper
     */
    public $helper;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->helper = new MenusHelper();
    }

    public function getItems($response)
    {
        return $response->withJson($this->helper->getItems());
    }
}

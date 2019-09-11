<?php
/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jonas <labasas@gmail.com> - http://www.modernjoomla.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Moderntime controller class.
 */
class Modern_toursControllerModerntime extends JControllerForm
{

    function __construct() {
        $this->view_list = 'moderntimes';
        parent::__construct();
    }

}
<?php
/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   Copyright (C) 2015. All rights reserved. Unikalus team.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jonas <jonasjov2@gmail.com> - http://modernjoomla.com
 */
// No direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.controllerform');
include(JPATH_ADMINISTRATOR . '/components/com_modern_tours/helpers/simple_html_dom.php');

/**
 * Modernform controller class.
 */
class Modern_toursControllerModernform extends JControllerForm
{

    function __construct()
    {
        $this->view_list = 'modernforms';
        parent::__construct();
    }

    /**
     * Method to save a record.
     *
     * @param   string  $key     The name of the primary key of the URL variable.
     * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
     *
     * @return  boolean  True if successful, false otherwise.
     *
     * @since   1.6
     */
    public function save($key = null, $urlVar = null) {
        // Check for request forgeries.
        JSession::checkToken() or jexit( JText::_( 'JINVALID_TOKEN' ) );

        parent::save();
    }

    public function getElementNames($elements = array())
    {
        $in = $this->input->post->get('jform', array(), 'array');
        $this->html = str_get_html($in['formdata']);
        if ($this->html) {
            foreach ($this->html->find('field') as $element) {
                if($element->type != 'paragraph') {
                    if($element->type != 'header') {
                        $elements[] = $element->name;
                    }
                }
            }
        }

        return $elements;
    }
}


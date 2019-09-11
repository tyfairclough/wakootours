<?php

/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   Copyright (C) 2015. All rights reserved. Unikalus team.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jonas Jovaisas <jonasjov2@gmail.com> - http://modernjoomla.com
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
require_once(JPATH_SITE . '/components/com_modern_tours/models/reservations.php');

/**
 * View class for a list of Modern_tours.
 */
class Modern_toursViewInvoice extends JViewLegacy
{

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $this->template = $this->getTemplate();
        $this->addToolbar();
	    $this->variables = Modern_toursHelper::getVariables(JFactory::getApplication()->input->getInt('id'), false);

	    parent::display($tpl);
    }
    
    public function getTemplate()
    {
	    $template = JFactory::getDbo()->setQuery('SELECT template, currency FROM #__modern_tours_invoice WHERE id = ' . JFactory::getApplication()->input->get('id'))->loadObject();

	    if(!$template) {
		    $template = JFactory::getDbo()->setQuery('SELECT template, currency FROM #__modern_tours_invoice WHERE id = 9999')->loadObject();
	    }

	    
	    return $template;
    }

    protected function addToolbar()
    {
        JToolBarHelper::title(JText::_('invoice'), 'coupon.png');
        JToolBarHelper::apply('invoice.save', 'JTOOLBAR_APPLY');
	    JToolBarHelper::cancel('invoice.cancel', 'JTOOLBAR_CLOSE');
	    JToolBarHelper::custom('', 'show.png', 'show.png', JText::_('SHOW_VARIABLES'));

    }
}
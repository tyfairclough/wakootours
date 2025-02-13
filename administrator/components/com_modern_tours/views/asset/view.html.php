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

jimport('joomla.application.component.view');

/**
 * View to edit
 */
class Modern_toursViewAsset extends JViewLegacy {

    protected $state;
    protected $item;
    protected $form;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->get('State');
        $this->item = $this->get('Item');
        $this->form = $this->get('Form');
        $this->userGroups = $this->get('UserGroups');

        Modern_toursHelper::getSidebar();

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        $this->addToolbar();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     */
    protected function addToolbar() {
        JFactory::getApplication()->input->set('hidemainmenu', true);

        $user = JFactory::getUser();
        $isNew = ($this->item->id == 0);
        if (isset($this->item->checked_out)) {
            $checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
        } else {
            $checkedOut = false;
        }
        $canDo = Modern_toursHelper::getActions();


        $id = JFactory::getApplication()->input->get('id');

        if($id)
        {
	        JToolBarHelper::title(JText::sprintf('COM_MODERN_TOURS_TITLE_EDIT', $this->item->title), 'asset.png');
        }
        else
        {
	        JToolBarHelper::title(JText::_('COM_MODERN_TOURS_NEW_TOUR'), 'asset.png');
        }

        // If not checked out, can save the item.
        if (!$checkedOut && ($canDo->get('core.edit') || ($canDo->get('core.create')))) {

            JToolBarHelper::apply('asset.apply', 'JTOOLBAR_APPLY');
            JToolBarHelper::save('asset.save', 'JTOOLBAR_SAVE');
        }
        // If an existing item, can save to a copy.
        if (!$isNew && $canDo->get('core.create')) {
            JToolBarHelper::custom('asset.save2copy', 'save-copy.png', 'save-copy_f2.png', 'Copy', false);
        }
        if (empty($this->item->id)) {
            JToolBarHelper::cancel('asset.cancel', 'JTOOLBAR_CANCEL');
        } else {
            JToolBarHelper::cancel('asset.cancel', 'JTOOLBAR_CLOSE');
        }
	    JToolBarHelper::custom('', 'availability.png', '', JText::_('AVAILABILITY_INTERVAL'), false);
	    JToolBarHelper::custom('', 'hour-days.png', 'hour-days.png', JText::_('HOUR_GENERATOR'), false);
        JToolBarHelper::custom('', 'disable-days.png', 'disable-days.png', JText::_('OPEN_CALENDAR'), false);

    }

}

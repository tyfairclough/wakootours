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

jimport('joomla.application.component.view');

/**
 * View to edit
 */
class Modern_toursViewModernform extends JViewLegacy {

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
        Modern_toursHelper::getSidebar();
        $this->additions = $this->get('Additions');

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

        JToolBarHelper::title(JText::_('COM_MODERN_TOURS_TITLE_MODERNFORM'), 'modernform.png');

        // If not checked out, can save the item.
        if (!$checkedOut && ($canDo->get('core.edit') || ($canDo->get('core.create')))) {
            JToolBarHelper::apply('modernform.apply', 'JTOOLBAR_APPLY');
            JToolBarHelper::save('modernform.save', 'JTOOLBAR_SAVE');
            JToolbarHelper::save2copy('modernform.save2copy', 'Clone & translate');
        }

        if (empty($this->item->id)) {
            JToolBarHelper::cancel('modernform.cancel', 'JTOOLBAR_CANCEL');
        } else {
            JToolBarHelper::cancel('modernform.cancel', 'JTOOLBAR_CLOSE');
        }
        JToolBarHelper::custom('', 'change-title.png', 'change-title.png', JText::_('CHANGE_TITLE'), false);
    }

}

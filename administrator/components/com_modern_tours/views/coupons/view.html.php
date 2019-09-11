<?php

/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jonas <jonasjov2@gmail.com> - http://www.modernjoomla.com
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Modern_tours.
 */
class Modern_toursViewCoupons extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        Modern_toursHelper::getSidebar();

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        Modern_toursHelper::addSubmenu('coupons');

        $this->addToolbar();

        $this->sidebar = JHtmlSidebar::render();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar() {
        require_once JPATH_COMPONENT . '/helpers/modern_tours.php';

        $state = $this->get('State');
        $canDo = Modern_toursHelper::getActions($state->get('filter.category_id'));

        JToolBarHelper::title(JText::_('COM_MODERN_TOURS_TITLE_COUPONS'), 'coupons.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/coupon';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('coupon.add', 'JTOOLBAR_NEW');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('coupon.edit', 'JTOOLBAR_EDIT');
            }
        }

        if ($canDo->get('core.edit.state')) {
            JToolBarHelper::divider();
            JToolBarHelper::custom('coupons.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
            JToolBarHelper::custom('coupons.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            JToolBarHelper::deleteList('', 'coupons.delete', 'JTOOLBAR_DELETE');
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_modern_tours');
        }

        //Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_modern_tours&view=coupons');


    }

	protected function getSortFields()
	{
		return array(
		'a.`id`' => JText::_('JGRID_HEADING_ID'),
		'a.`ordering`' => JText::_('JGRID_HEADING_ORDERING'),
		'a.`state`' => JText::_('JSTATUS'),
		'a.`title`' => JText::_('COM_MODERN_TOURS_COUPONS_TITLE'),
		'a.`code`' => JText::_('COM_MODERN_TOURS_COUPONS_CODE'),
		'a.`start`' => JText::_('COM_MODERN_TOURS_COUPONS_START'),
		'a.`end`' => JText::_('COM_MODERN_TOURS_COUPONS_END'),
		'a.`couponsnumber`' => JText::_('COM_MODERN_TOURS_COUPONS_COUPONSNUMBER'),
		'a.`pricepercent`' => JText::_('COM_MODERN_TOURS_COUPONS_PRICEPERCENT'),
		'a.`pricetype`' => JText::_('COM_MODERN_TOURS_COUPONS_PRICETYPE'),
		);
	}

}

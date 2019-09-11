<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Modern_tours
 * @author     Jonas <jonasjov2@gmail.com>
 * @copyright  2016 Jonas
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Modern_tours.
 *
 * @since  1.6
 */
class Modern_toursViewCategories extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		Modern_toursHelper::getSidebar();


		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		Modern_toursHelper::addSubmenu('categories');

		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	protected function addToolbar()
	{
		$state = $this->get('State');
		$canDo = Modern_toursHelper::getActions();

		JToolBarHelper::title(JText::_('COM_MODERN_TOURS_TITLE_CATEGORIES'), 'categories.png');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/category';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('category.add', 'JTOOLBAR_NEW');
			}
			if ($canDo->get('core.edit') && isset($this->items[0]))
			{
				JToolBarHelper::editList('review.edit', 'JTOOLBAR_EDIT');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('categories.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('categories.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{
			if ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::deleteList('', 'categories.delete', 'JTOOLBAR_DELETE');
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_modern_tours');
		}

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_modern_tours&view=categories');
	}

	/**
	 * Method to order fields 
	 *
	 * @return void 
	 */
	protected function getSortFields()
	{
		return array(
			'a.`id`' => JText::_('JGRID_HEADING_ID'),
			'a.`ordering`' => JText::_('JGRID_HEADING_ORDERING'),
			'a.`state`' => JText::_('JSTATUS'),
			'a.`title`' => JText::_('COM_MODERN_TOURS_CATEGORIES_TITLE'),
			'a.`image`' => JText::_('COM_MODERN_TOURS_CATEGORIES_IMAGE'),
		);
	}

    /**
     * Check if state is set
     *
     * @param   mixed  $state  State
     *
     * @return bool
     */
    public function getState($state)
    {
        return isset($this->state->{$state}) ? $this->state->{$state} : false;
    }
}

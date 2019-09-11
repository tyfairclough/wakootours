<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Modern_tours
 * @author     Modernjoomla.com <support@modernjoomla.com>
 * @copyright  modernjoomla.com
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
class Modern_toursViewReservations extends JViewLegacy
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
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');
		Modern_toursHelper::getSidebar();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		Modern_toursHelper::addSubmenu('reservations');

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

		JToolBarHelper::title(JText::_('COM_MODERN_TOURS_TITLE_RESERVATIONS'), 'reservations.png');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/reservation';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
//				JToolBarHelper::addNew('reservation.add', 'JTOOLBAR_NEW');

			}

			if ($canDo->get('core.edit') && isset($this->items[0]))
			{
				JToolBarHelper::editList('reservation.edit', 'JTOOLBAR_EDIT');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('reservations.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('reservations.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			elseif (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'reservations.delete', 'JTOOLBAR_DELETE');
			}

			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('reservations.archive', 'JTOOLBAR_ARCHIVE');
			}
		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'reservations.delete', 'JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			}
			elseif ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::trash('reservations.trash', 'JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_modern_tours');
		}

		JToolBarHelper::custom('reservations.csv', 'csv','', JText::_('EXPORT_TO_CSV'), false);

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_modern_tours&view=reservations');
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
			'a.`status`' => JText::_('COM_MODERN_TOURS_RESERVATIONS_STATUS'),
			'a.`name`' => JText::_('COM_MODERN_TOURS_RESERVATIONS_NAME'),
			'a.`surname`' => JText::_('COM_MODERN_TOURS_RESERVATIONS_SURNAME'),
			'a.`phone`' => JText::_('COM_MODERN_TOURS_RESERVATIONS_PHONE'),
			'a.`address`' => JText::_('COM_MODERN_TOURS_RESERVATIONS_ADDRESS'),
			'a.`date`' => JText::_('COM_MODERN_TOURS_RESERVATIONS_DATE'),
			'a.`price`' => JText::_('COM_MODERN_TOURS_RESERVATIONS_PRICE'),
			'a.`people`' => JText::_('COM_MODERN_TOURS_RESERVATIONS_PEOPLE'),
			'a.`registered`' => JText::_('COM_MODERN_TOURS_RESERVATIONS_REGISTERED'),
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

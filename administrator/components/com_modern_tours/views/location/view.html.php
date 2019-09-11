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
 * View to edit
 *
 * @since  1.6
 */
class Modern_toursViewLocation extends JViewLegacy
{
	protected $state;

	protected $item;

	protected $form;

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
		$this->item  = $this->get('Item');
		$this->form  = $this->get('Form');
		Modern_toursHelper::getSidebar();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user  = JFactory::getUser();
		$isNew = ($this->item->id == 0);

		if (isset($this->item->checked_out))
		{
			$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		}
		else
		{
			$checkedOut = false;
		}

		$canDo = Modern_toursHelper::getActions();

		JToolBarHelper::title(JText::_('COM_MODERN_TOURS_TITLE_LOCATION'), 'location.png');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit') || ($canDo->get('core.create'))))
		{
			JToolBarHelper::apply('location.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('location.save', 'JTOOLBAR_SAVE');
		}

		if (!$checkedOut && ($canDo->get('core.create')))
		{
			JToolBarHelper::custom('location.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}

		// Button for version control
//		if ($this->state->params->get('save_history', 1) && $user->authorise('core.edit')) {
//			JToolbarHelper::versions('com_modern_tours.location', $this->item->id);
//		}

		if (empty($this->item->id))
		{
			JToolBarHelper::cancel('location.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			JToolBarHelper::cancel('location.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}

<?php
/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   Copyright (C) 2015. All rights reserved. Unikalus team.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jonas Jovaisas <jonasjov2@gmail.com> - http://modernjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Fields list controller class.
 */
class Modern_toursControllerForms extends JControllerAdmin
{
	public function __construct()
	{
		$this->id = JFactory::getApplication()->input->get('id');
		$inputCookie = JFactory::getApplication()->input->cookie;
		if ($this->id) {
			$inputCookie->set('category', $this->id, $expire = 0);
		} else {
			$this->id = $inputCookie->get($name = 'category', $defaultValue = null);
		}

		parent::__construct();
	}

	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'form', $prefix = 'Modern_toursModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
    

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function saveOrderAjax()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$pks = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		JArrayHelper::toInteger($pks);
		JArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}

	public function delete($count = array())
	{
		$input = JFactory::getApplication()->input;
		$cid = $input->get('cid',array(),'','array');

		foreach($cid as $item) {
			$count[] = JFactory::getDbo()->setQuery('DELETE FROM #__modern_tours_forms_' . $this->id . ' WHERE unique_id = "' . $item . '"')->query();
		}
		$url = JUri::base() . 'index.php?option=com_modern_tours&view=forms&id=' . $this->id;
		$message = count($count) . ' reservation(s) deleted.';
		$this->setRedirect($url, $message, 'Message');
	}

	public function changestatus()
	{
		$input = JFactory::getApplication()->input;
		$unique_id = JFactory::getApplication()->input->get('unique_id');
		$status = $input->get('status');
		$items = JFactory::getDbo()->setQuery('SELECT id FROM #__modern_tours_forms_' . $this->id . ' WHERE unique_id = "' . $unique_id . '"')->loadColumn();

		foreach($items as $item) {
			$object = new stdClass();
			$object->id = $item;
			$object->paid = $status;
			JFactory::getDbo()->updateObject('#__modern_tours_forms_' . $this->id, $object, 'id');
		}
		$url = JUri::base() . 'index.php?option=com_modern_tours&view=forms&id=' . $this->id;
		$message = 'Reservation successfully set to ' . $status . '.';
		$this->setRedirect($url, $message, 'Message');
	}

	public function getCSV()
	{
		require JPATH_COMPONENT_ADMINISTRATOR . '/models/forms.php';
		$model = JModelList::getInstance('Forms', 'Modern_toursModel');
		$model->generateCSV();
	}

	public function changeDate()
	{
		$unique_id = JFactory::getApplication()->input->get('unique_id');
		$date = trim(JFactory::getApplication()->input->getString('date'));

		$object = new stdClass();
		$object->unique_id = $unique_id;
		$object->start = $date;

		JFactory::getDbo()->updateObject('#__modern_tours_forms_' . $this->id, $object, 'unique_id');
	}

}
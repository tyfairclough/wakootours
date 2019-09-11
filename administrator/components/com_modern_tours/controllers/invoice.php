<?php
/**
 * @version     2.0.0
 * @package     com_modern_tours
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jonas <jonasjov2@gmail.com> - http://www.modernjoomla.com
 */
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');
include_once(JPATH_ADMINISTRATOR . '/components/com_modern_tours/helpers/modern_tours.php');


/**
 * Invoice controller class.
 *
 * @since  1.6
 */
class Modern_toursControllerInvoice extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'invoices';
		parent::__construct();
	}

	public function save($key = NULL, $urlVar = NULL)
	{
		$this->data = $this->input->post->get('jform', array(), 'array');
		$data = (object)$this->data;
		$exists = JFactory::getDbo()->setQuery('SELECT id FROM #__modern_tours_invoice WHERE id = ' . $this->data['id'])->loadResult();

		if (!$exists) {
			JFactory::getDbo()->insertObject('#__modern_tours_invoice', $data);
		} else {
			JFactory::getDbo()->updateObject('#__modern_tours_invoice', $data, 'id');
		}

		$this->setRedirect('index.php?option=com_modern_tours&view=invoice&layout=edit&id=' . $this->data['id'], 'Invoice template saved successfully.', 'success');
	}

	public function pdf()
	{
		require_once(JPATH_SITE . '/components/com_modern_tours/models/reservations.php');
		$model = JModelList::getInstance('Reservations', 'modern_toursModel');
		$model->save = false;
		$model->pdf();
	}

	public function cancel()
	{
		$this->setRedirect('index.php?option=com_modern_tours&view=modernforms');
	}
}

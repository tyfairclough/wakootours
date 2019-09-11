<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Modern_tours
 * @author     Modernjoomla.com <support@modernjoomla.com>
 * @copyright  modernjoomla.com
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

use Joomla\Utilities\ArrayHelper;

/**
 * Reservations list controller class.
 *
 * @since  1.6
 */
class Modern_toursControllerReservations extends JControllerAdmin
{
	/**
	 * Method to clone existing Reservations
	 *
	 * @return void
	 */
	public function duplicate()
	{
		// Check for request forgeries
		Jsession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Get id(s)
		$pks = $this->input->post->get('cid', array(), 'array');

		try
		{
			if (empty($pks))
			{
				throw new Exception(JText::_('COM_MODERN_TOURS_NO_ELEMENT_SELECTED'));
			}

			ArrayHelper::toInteger($pks);
			$model = $this->getModel();
			$model->duplicate($pks);
			$this->setMessage(Jtext::_('COM_MODERN_TOURS_ITEMS_SUCCESS_DUPLICATED'));
		}
		catch (Exception $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
		}

		$this->setRedirect('index.php?option=com_modern_tours&view=reservations');
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    Optional. Model name
	 * @param   string  $prefix  Optional. Class prefix
	 * @param   array   $config  Optional. Configuration array for model
	 *
	 * @return  object	The Model
	 *
	 * @since    1.6
	 */
	public function getModel($name = 'reservation', $prefix = 'Modern_toursModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}

	public function csvColumns()
	{
		$columns = array(
			'id' => 'COM_MODERN_TOURS_RESERVATIONS_ID',
			'status' => 'COM_MODERN_TOURS_RESERVATIONS_STATUS',
			'assets_id' => 'COM_MODERN_TOURS_ASSET_NAME',
			'userData' => 'COM_MODERN_TOURS_RESERVATIONS_USERDATA',
			'email' => 'COM_MODERN_TOURS_RESERVATIONS_EMAIL',
			'travellersData' => 'COM_MODERN_TOURS_RESERVATIONS_TRAVELLERSDATA',
			'adults' => 'COM_MODERN_TOURS_RESERVATIONS_ADULTS',
			'children' => 'COM_MODERN_TOURS_RESERVATIONS_CHILDREN',
			'price' => 'COM_MODERN_TOURS_RESERVATIONS_PRICE',
			'paid_deposit' => 'COM_MODERN_TOURS_RESERVATIONS_PAID_SUM',
			'user_id' => 'COM_MODERN_TOURS_RESERVATIONS_USER',
			'date' => 'COM_MODERN_TOURS_RESERVATIONS_DATE',
			'registered' => 'COM_MODERN_TOURS_RESERVATIONS_REGISTERED'
		);
		
		return $columns;
	}

	public function getIDs()
	{
		$columns = $this->csvColumns();
		return array_keys($columns);
	}

	public function getColumns()
	{
		$columns = $this->csvColumns();
		$values = array_values($columns);

		return array_map(function($a) {
			return JText::_($a);
		}, $values);
	}

	public function getReservations()
	{
		$items = array();
		$model = JModelList::getInstance('Reservations', 'Modern_toursModel');
		$model->setState('list.limit', 9999);
		$reservations = $model->getItems(true);
		$columns = $this->getIDs();

		foreach($reservations as $i => $reservation)
		{

			foreach($columns as $column)
			{
				$items[$i][$column] =  $reservation->{$column};

			}
		}

		return $items;
	}

	public function csv()
	{
		$file = 'export_' . date('Y_m_d_H_i_s') . '.csv';
		$path = __DIR__ . '../../csv/';
		$fullpath = $path . $file;

		ob_get_clean();
		$fp = fopen($path . $file, 'w');

		fputcsv($fp, array('SEP=,'));
		fputcsv($fp, $this->getColumns());

		$reservations = $this->getReservations();

		foreach ($reservations as $fields) {
			fputcsv($fp, $fields, ',', chr(0));
		}

		fclose($fp);

		header('Content-disposition: attachment; filename=' . $file);
		header('Content-type: application/csv'); // works for all extensions except txt
		readfile($fullpath);
		JFactory::getApplication()->close();
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
		$pks   = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		ArrayHelper::toInteger($pks);
		ArrayHelper::toInteger($order);

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

	public function export()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$pks   = $input->post->get('cid', array(), 'array');
	}

}

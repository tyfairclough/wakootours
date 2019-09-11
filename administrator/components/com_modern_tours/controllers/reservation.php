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

jimport('joomla.application.component.controllerform');

/**
 * Reservation controller class.
 *
 * @since  1.6
 */
class Modern_toursControllerReservation extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'reservations';
		parent::__construct();
	}

	public function changestatus()
	{
		$input = JFactory::getApplication()->input;
		$unique_id = $input->get('unique_id');
		$status = $input->getVar('status');
		$items = JFactory::getDbo()->setQuery('SELECT id FROM #__modern_tours_reservations WHERE id = "' . $unique_id . '"')->loadColumn();

		foreach($items as $item) {
			$object = new stdClass();
			$object->id = $item;
			$object->status = str_replace(' ', '_', $status);
			JFactory::getDbo()->updateObject('#__modern_tours_reservations', $object, 'id');
		}
		$url = JUri::base() . 'index.php?option=com_modern_tours&view=reservations';
		$message = 'Reservation successfully set to ' . JText::_($status) . '.';
		$this->setRedirect($url, $message, 'Message');
	}

}

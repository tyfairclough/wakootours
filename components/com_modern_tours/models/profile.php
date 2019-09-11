<?php

/**
 * @version    CVS: 1.0.0
 * @package    com_modern_tours
 * @author      modernjoomla.com <support@modernjoomla.com>
 * @copyright  modernjoomla.com
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');
jimport('joomla.event.dispatcher');

use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;

/**
 * Modern_tours model.
 *
 * @since  1.6
 */
class Modern_toursModelProfile extends JModelItem
{
	/*
	 * Get use bookings
	 */
	public function getReservations()
	{
		$items = JFactory::getDbo()->setQuery('SELECT t.*, r.*, r.id as order_id FROM #__modern_tours_reservations r JOIN  #__modern_tours_assets t ON r.assets_id = t.id WHERE r.user_id = ' . JFactory::getUser()->id . ' GROUP by r.id ORDER BY r.date DESC ')->loadObjectList();
		$items = $this->eachReservations($items);

		return $items;
	}

	/*
	 * Get use bookings
	 */
	public function getReviews()
	{
		$items = JFactory::getDbo()->setQuery('SELECT r.*, t.*, r.id as review_id FROM #__modern_tours_reviews r JOIN #__modern_tours_assets t ON r.assets_id = t.id WHERE r.user_id = ' . JFactory::getUser()->id)->loadObjectList();
		$items = $this->eachReviews($items);

		return $items;
	}

	public function eachReservations($items)
	{
		foreach($items as $item)
		{
			$item->date = date('Y-m-d H:i', strtotime($item->date));
			$item->registered = date('Y-m-d H:i', strtotime($item->registered));
			$item->expired = $item->date < date('Y-m-d H:i') ? 'expired' : '';
			$item->cancelLink = $item->date < date('Y-m-d H:i') ? '#' : JURI::base() . 'index.php?option=com_modern_tours&task=cancelBooking&id=' . $item->order_id;
		}

		return $items;
	}

	public function eachReviews($items)
	{
		foreach($items as $item)
		{

		}

		return $items;
	}
}

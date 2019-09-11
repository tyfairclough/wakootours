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
class Modern_toursModelAsset extends JModelItem
{
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return void
	 *
	 * @since    1.6
	 *
	 */
	protected function populateState()
	{
		$app  = JFactory::getApplication('com_modern_tours');
		$user = JFactory::getUser();

		// Check published state
		if ((!$user->authorise('core.edit.state', 'com_modern_tours')) && (!$user->authorise('core.edit', 'com_modern_tours')))
		{
			$this->setState('filter.published', 1);
			$this->setState('filter.archived', 2);
		}

		// Load state from the request userState on edit or from the passed variable on default
		if (JFactory::getApplication()->input->get('layout') == 'edit')
		{
			$id = JFactory::getApplication()->getUserState('com_modern_tours.edit.asset.id');
		}
		else
		{
			$id = JFactory::getApplication()->input->get('id');
			JFactory::getApplication()->setUserState('com_modern_tours.edit.asset.id', $id);
		}

		$this->setState('asset.id', $id);

		// Load the parameters.
		$params       = $app->getParams();
		$params_array = $params->toArray();

		if (isset($params_array['item_id']))
		{
			$this->setState('asset.alias', $params_array['item_id']);
		}


		$this->setState('params', $params);
	}

	public function getRelated($relatedItems)
	{
		if($relatedItems)
		{
			$items = array();
			$relatedItems = json_decode($relatedItems);

			if($relatedItems)
			{
				foreach($relatedItems as $alias)
				{
					if($alias)
					{
						$item = self::getRelatedItem($alias, true);

						if($item)
						{
							$items[] = $item;
						}
					}
				}

				return $items;
			}
		}
	}

	/**
	 * Method to get an object.
	 *
	 * @param   integer $id The id of the object to get.
	 *
	 * @return  mixed    Object on success, false on failure.
     *
     * @throws Exception
	 */
	public function getItem()
	{
		$alias = MTHelper::getAlias();
		return self::getRelatedItem($alias);
	}

	/**
	 * Get an instance of JTable class
	 *
	 * @param   string $type   Name of the JTable class to get an instance of.
	 * @param   string $prefix Prefix for the table class name. Optional.
	 * @param   array  $config Array of configuration values for the JTable object. Optional.
	 *
	 * @return  JTable|bool JTable if success, false on failure.
	 */
	public function getTable($type = 'Asset', $prefix = 'Modern_toursTable', $config = array())
	{
		$this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_modern_tours/tables');

		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Get the id of an item by alias
	 *
	 * @param   string $alias Item alias
	 *
	 * @return  mixed
	 */
	public function getItemIdByAlias($alias)
	{
		$table      = $this->getTable();
		$properties = $table->getProperties();
		$result     = null;

		if (key_exists('alias', $properties))
		{
            $table->load(array('alias' => $alias));
            $result = $table->id;
		}

		return $result;
	}

	public function ratingsSummary($id)
	{
		return JFactory::getDbo()->setQuery('SELECT COUNT(id) as count, ROUND( AVG(rating),1 ) AS rating FROM #__modern_tours_reviews WHERE state = 1 and assets_id = ' . $id)->loadObject();
	}

	/**
	 * Method to check in an item.
	 *
	 * @param   integer $id The id of the row to check out.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function checkin($id = null)
	{
		// Get the id.
		$id = (!empty($id)) ? $id : (int) $this->getState('asset.id');

		if ($id)
		{
			// Initialise the table
			$table = $this->getTable();

			// Attempt to check the row in.
			if (method_exists($table, 'checkin'))
			{
				if (!$table->checkin($id))
				{
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Method to check out an item for editing.
	 *
	 * @param   integer $id The id of the row to check out.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function checkout($id = null)
	{
		// Get the user id.
		$id = (!empty($id)) ? $id : (int) $this->getState('asset.id');

		if ($id)
		{
			// Initialise the table
			$table = $this->getTable();

			// Get the current user object.
			$user = JFactory::getUser();

			// Attempt to check the row out.
			if (method_exists($table, 'checkout'))
			{
				if (!$table->checkout($user->get('id'), $id))
				{
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Publish the element
	 *
	 * @param   int $id    Item id
	 * @param   int $state Publish state
	 *
	 * @return  boolean
	 */
	public function publish($id, $state)
	{
		$table = $this->getTable();
		$table->load($id);
		$table->state = $state;

		return $table->store();
	}

	/**
	 * Method to delete an item
	 *
	 * @param   int $id Element id
	 *
	 * @return  bool
	 */
	public function delete($id)
	{
		$table = $this->getTable();

		return $table->delete($id);
	}

	public function getReviews($asset_id)
	{
		$reviews = JFactory::getDbo()->setQuery('SELECT * FROM #__modern_tours_reviews WHERE state = 1 and assets_id = ' . $asset_id)->loadObjectList();
		return $this->eachReviews($reviews);
	}

	public function eachReviews($reviews)
	{
		foreach ($reviews as $review)
		{
			$user = $review->user_id != 0 ? JFactory::getUser($review->user_id)->name : JText::_('MB_GUEST');
			$review->author = $user;
			$review->starRating = MTHelper::generateStars($review->rating, 1, true);
			$review->emptyImageClass = 'empty';
			$review->userImage = '';
		}

		return $reviews;
	}

	/**
	 * get Stripe Keys
	 */
	public function getKey()
	{
		JPluginHelper::importPlugin( 'tourpayment' );
		$dispatcher = JEventDispatcher::getInstance();
		$keys       = $dispatcher->trigger( 'keys', 'stripe' );
		if ( $keys ) {
			return $keys[0];
		}
	}

	/**
	 * Get lowest price
	 */

	public function getLowestPrice($times)
	{
		$timeIntervals = $this->eachEveryDay(json_decode($times), 'newFunct');

		return $this->getMinPrice($timeIntervals);
	}

	function eachDay($day, $timeInterval, $custom)
	{
		return $this->{$custom}($day, $timeInterval);
	}

	function newFunct($day, $timeInterval) {
		return $timeInterval->{'adult-price'};
	}

	function eachEveryDay($timeIntervals, $function)
	{
		$newTimes = new stdClass();

		foreach($timeIntervals as $theDay => $date)
		{
			foreach($date as $timeStamp => $timeInterval)
			{
				$newTimes->{$theDay}{$timeStamp} = $this->eachDay($timeStamp, $timeInterval, $function);
			}
		}

		return $newTimes;
	}

	function getMinPrice($timeIntervals)
	{
		$prices = array();

		foreach($timeIntervals as $day)
		{
			$prices[end($day)] = end($day);
		}

		// @todo PRICE CHANGER
		return $prices ? min($prices) : 0;
	}


	/**
	 * Method to get an object.
	 *
	 * @param   integer $id The id of the object to get.
	 *
	 * @return  mixed    Object on success, false on failure.
	 *
	 * @throws Exception
	 */
	public function getRelatedItem($alias = null, $ignoreRelated = false)
	{
		if(isset($alias))
		{
			$item = JFactory::getDbo()->setQuery( 'SELECT * FROM #__modern_tours_assets WHERE alias = "' . $alias . '" AND state = 1' )->loadObject();

			if($item)
			{
				if ( isset( $item->created_by ) )
			{
				$item->created_by_name = JFactory::getUser( $item->created_by )->name;
			}

			if ( isset( $item->modified_by ) )
			{
				$item->modified_by_name = JFactory::getUser( $item->modified_by )->name;
			}

			if ( isset( $item->category ) && $item->category != '' )
			{
				$item->category_id = $item->category;

				if ( is_object( $item->category ) )
				{
					$item->category = ArrayHelper::fromObject( $item->category );
				}

				$values = ( is_array( $item->category ) ) ? $item->category : explode( ',', $item->category );

				$textValue = array();

				foreach ( $values as $value )
				{
					$db    = JFactory::getDbo();
					$query = "SELECT id, title FROM #__modern_tours_categories WHERE id LIKE '" . $value . "'";

					$db->setQuery( $query );
					$results = $db->loadObject();

					if ( $results )
					{
						$textValue[] = $results->title;
					}
				}

				$item->category = ! empty( $textValue ) ? implode( ', ', $textValue ) : $item->category;
			}

			if ( isset( $item->location ) && $item->location != '' )
			{
				if ( is_object( $item->location ) )
				{
					$item->location = ArrayHelper::fromObject( $item->location );
				}

				$values = ( is_array( $item->location ) ) ? $item->location : explode( ',', $item->location );

				$textValue = array();

				foreach ( $values as $value )
				{
					$db    = JFactory::getDbo();
					$query = "SELECT id, title FROM #__modern_tours_locations WHERE id LIKE '" . $value . "'";

					$db->setQuery( $query );
					$results = $db->loadObject();

					if ( $results )
					{
						$textValue[] = $results->title;
					}
				}

				$item->location = ! empty( $textValue ) ? implode( ', ', $textValue ) : $item->location;
			}

			$item->ratings = $this->ratingsSummary( $item->id );

			$item->reviews = $this->getReviews( $item->id );

			$reviewHTML = count($item->reviews) >= 1 ? MTHelper::displayRating( $item->ratings->rating, $item->ratings->count ) : MTHelper::displayRating(0,0);

			$item->reviewHTML = $reviewHTML;

			$item->lowestPrice = $this->getLowestPrice( $item->times );

			$item->currency = MTHelper::getComponentParams( 'currency' );

			$item->currencySymbol = MTHelper::addSymbol( $item->currency );

			$item->url = JUri::getInstance();

			if(!$ignoreRelated)
			{
				$item->relatedItems = json_encode($this->getRelated( $item->related ));
			}

			$item->params = json_decode( $item->params );

			return $item;
			}
		}
	}

	public function getImportReservations()
	{
		$bookings = array();
		$item = $this->getItem();

		if(isset($item->params->import_booking))
		{
			foreach($item->params->import_booking as $alias)
			{
				$id = JFactory::getDbo()->setQuery('SELECT id FROM #__modern_tours_assets WHERE alias = "' . $alias . '"')->loadResult();
				if($id)
				{
					$booking = JFactory::getDbo()->setQuery('SELECT date, people FROM #__modern_tours_reservations WHERE assets_id = ' . $id)->loadObjectList();
					if($booking)
					{
						$bookings[] = $booking;
					}
				}
			}
		}

		return $bookings;
	}

	public function createReservations($bookings, $reservations = array())
	{
		foreach($bookings as $booking)
		{
			$reserved = !isset($reservations[$booking->date]) ? $booking->people : $reservations[$booking->date] + $booking->people;
			$reservations[$booking->date] = $reserved;
		}
		return $reservations;
	}

	public function getReservations()
	{
		$reservations = array();
		$id = $this->getIdFromAlias();
		$bookings = JFactory::getDbo()->setQuery('SELECT date, people FROM #__modern_tours_reservations WHERE status != "canceled" and status != "waiting" and assets_id = ' . $id)->loadObjectList();
		$importReservations = $this->getImportReservations();
		$importReservations[] = $bookings;

		foreach($importReservations as $booking)
		{
			$reservations = $this->createReservations($booking, $reservations);
		}

		return $reservations;
	}

	public function getIdFromAlias()
	{
		$alias = MTHelper::getAlias();
		return JFactory::getDbo()->setQuery('SELECT id FROM #__modern_tours_assets WHERE alias = "' . $alias . '"')->loadResult();
	}

}

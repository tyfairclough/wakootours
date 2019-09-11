<?php

/**
 * @version    CVS: 1.0.0
 * @package    com_modern_tours
 * @author      modernjoomla.com <support@modernjoomla.com>
 * @copyright  modernjoomla.com
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Modern_tours records.
 *
 * @since  1.6
 */
class Modern_toursModelAssets extends JModelList
{
	public $context;
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'ordering', 'a.ordering',
				'state', 'a.state',
				'created_by', 'a.created_by',
				'modified_by', 'a.modified_by',
				'reviews_id', 'a.reviews_id',
				'title', 'a.title',
				'description', 'a.description',
				'small_description', 'a.small_description',
				'category', 'a.category',
				'location', 'a.location',
				'max_people', 'a.max_people',
				'parameters', 'a.parameters',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app  = JFactory::getApplication();
		$list = $app->getUserState($this->context . '.list');

		$ordering  = isset($list['filter_order'])     ? $list['filter_order']     : null;
		$direction = isset($list['filter_order_Dir']) ? $list['filter_order_Dir'] : null;

		$list['limit']     = (int) JFactory::getConfig()->get('list_limit', 20);
		$list['start']     = $app->input->getInt('start', 0);
		$list['ordering']  = $ordering;
		$list['direction'] = $direction;

		$app->setUserState($this->context . '.list', $list);
		$app->input->set('list', null);

		// List state information.
		parent::populateState($ordering, $direction);

        $app = JFactory::getApplication();

		$search = $app->input->getVar('search');

		if($search)
		{
			$this->setState('filter.search', $search);
		}

		$ordering  = $app->getUserStateFromRequest($this->context . '.ordercol', 'filter_order', $ordering);
		$direction = $app->getUserStateFromRequest($this->context . '.orderdirn', 'filter_order_Dir', $ordering);

		$this->setState('list.ordering', $ordering);
		$this->setState('list.direction', $direction);

		$start = 0;//$start = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0, 'int');
		$limit = $app->getUserStateFromRequest($this->context . '.limit', 'limit', 20, 'int');

		if ($limit == 0)
		{
			$limit = $app->get('list_limit', 0);
		}

		$this->setState('list.limit', $limit);
		$this->setState('list.start', $start);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
				$this->getState(
					'list.id', 'DISTINCT a.alias'
				)
			);

		$query->from('`#__modern_tours_assets` AS a');

		// Join over the users for the checked out user.
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

		// Join over the created by field 'modified_by'
		$query->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by');

		$query->where('a.state = 1');

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.title LIKE ' . $search . ' )');
			}
		}

		$category = $this->getState($this->context . 'assets.category');

		if($category)
		{
			if(!is_int($category))
			{
				$category = JFactory::getDbo()->setQuery('SELECT id FROM #__modern_tours_categories WHERE alias = "' . $category . '"')->loadResult();
			}

			$query->where('a.category = ' . $category);
		}

		$location = $this->getState($this->context . 'assets.location');

		if($location)
		{
			if(!is_int($location))
			{
				$location = JFactory::getDbo()->setQuery('SELECT id FROM #__modern_tours_locations WHERE alias = "' . $location . '"')->loadResult();
			}
			$query->where('a.location = ' . $location);
		}

		$language = MTHelper::getMenuLang();

		if($language && $language != '*')
		{
			$query->where('a.language = "' . $language . '"');
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering') ? $this->state->get('list.ordering') : $this->getState('list.ordering');
		$orderDirn  = $this->state->get('list.direction') ? $this->state->get('list.direction') : $this->getState('list.direction');

		$items = $this->getState($this->context . 'assets.items');

		if($items)
		{
			$searchString = implode('", "', $items);
			$query->where('alias IN ("' . $searchString . '")');
		}

		$moduleOrdering = MTHelper::getParams()->get('orderby');

		if($moduleOrdering)
		{
			$query->order($this->getOrdering($moduleOrdering));
		}

		return $query;
	}

	public function filterItem($item)
	{
		$input = JFactory::getApplication()->input;
		$from = $input->get('from');
		$to = $input->get('to');
		$rating = $input->get('rating');
		$duration = $input->getVar('duration');
		$category = $input->getVar('category');
		$location = $input->getVar('location');

		$itemRating = isset($item->ratings->rating) ? $item->ratings->rating : '';
		$itemDuration = $item->params->duration;

		$continue = true;

		if(isset($from) && isset($to))
		{
			if(!($item->lowestPrice >= $from) OR !($item->lowestPrice <= $to))
			{
				$continue = false;
			}
		}

		if($rating)
		{
			// round ratings 4.4 to 4, 4.5 to 5
			$itemRating = round($itemRating);

			if($rating != $itemRating)
			{
				$continue = false;
			}
			if(!$itemRating)
			{
				$continue = false;
			}
		}

		if($duration)
		{
			if(!$this->filterDuration($duration, $itemDuration))
			{
				$continue = false;
			}
		}

		if($category)
		{
			if($category != $item->category)
			{
				$continue = false;
			}
		}

		if($location)
		{
			if($location != $item->location)
			{
				$continue = false;
			}
		}

		return $continue;
	}

	public function filterDuration($duration, $itemDuration)
	{
		$continue = true;

		switch ($duration) {
			case (1):
				if($duration != $itemDuration) {
					$continue = false;
				}
				break;
			case ('2-3'):
				if($itemDuration != 2 && $itemDuration != 3) {
					$continue = false;
				}
				break;
			case ('4-5'):
				if($itemDuration != 4 && $itemDuration != 5) {
					$continue = false;
				}
				break;
			case ('6'):
				if($itemDuration < 6) {
					$continue = false;
				}
				break;
		}

		return $continue;
	}

	public function getIDs($items)
	{
		$model = JModelList::getInstance('Asset', 'Modern_toursModel');
		$assets = array();

		foreach($items as $item)
		{
			$item = $model->getRelatedItem($item->alias, true);

			if($item)
			{
				if ( $this->filterItem( $item ) )
				{
					$assets[] = $this->alterItem($item);
				}
			}
		}

		return $assets;
	}

	public function alterItem($item)
	{
		$truncateText = $this->getState($this->context . 'truncate.text');

		if($truncateText)
		{
			$item->small_description = substr($item->small_description, 0, $truncateText);
		}

		$truncateTitle = $this->getState($this->context . 'truncate.title');

		if($truncateTitle)
		{
			$item->title = substr($item->title, 0, $truncateTitle);
		}

		// @todo PRICE CHANGER
//		$item->lowestPrice = 1;

		return $item;
	}
	
	/**
	 * Method to get an array of data items
	 *
	 * @return  mixed An array of data on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();
		$items = $this->getIDs($items);

		foreach ($items as $i => $item)
		{
			if(!$this->isAvailable($item))
			{
				unset($items[$i]);
			}
		}

		return $items;
	}

	public function isAvailable($item)
	{
		$input  = JFactory::getApplication()->input;
		$start = $input->get('start');
		$end = $input->get('end');

		if($start && $end)
		{
			$searchableDates = $this->searchableDates($item);
			$availableDates = $this->availableDates($item);
			$slots = 0;

			foreach($searchableDates as $searchableDate)
			{
				foreach($availableDates as $date => $hour)
				{
					$dd = new DateTime($searchableDate);
					$weekDay = $dd->format("N");
					
					if($searchableDate == $date || $date == $weekDay)
					{
						$slots += $this->getSlots($availableDates, $searchableDate, $date);
					}
				}
			}

			return $slots > 0;
		}

		return 1;
	}

	public function getSlots($availableDates, $searchableDate, $date)
	{
		$dd = new DateTime($searchableDate);
		$weekDay = $dd->format("N");
		$slots = 0;

		if($searchableDate == $date || $date == $weekDay)
		{
			$slots = $this->countSlots($searchableDate == $date ? $availableDates->{$date} : $availableDates->{$weekDay});
		}

		return $slots;
	}

	public function countSlots($hours)
	{
		$slots = 0;
		foreach($hours as $hour)
		{
			$slots += $hour->slots;
		}
		return $slots;
	}

	public function availableDates($item)
	{
		return json_decode($item->times);
	}

	public function searchableDates($item)
	{
		$input  = JFactory::getApplication()->input;
		$startingDate = $input->get('start');
		$endDate = $input->get('end');

		$dStart = new DateTime($startingDate);
		$dEnd  = new DateTime($endDate);
		$daysDiff = $dStart->diff($dEnd)->format('%r%a');

		$dates = [];

		if(MTHelper::hasAvailability($item))
		{
			for($i=0; $i <= $daysDiff; $i++)
			{
				$due_dt = new DateTime($startingDate);
				$date = $due_dt->modify('+' . $i . ' day')->format('Y-m-d');

				if($startingDate <= $date && $endDate >= $date)
				{
					$dates[] = $date;
				}
			}
		}
		else
		{
			for($i=0; $i <= $daysDiff; $i++)
			{
				$due_dt = new DateTime($startingDate);
				$date = $due_dt->modify('+' . $i . ' day')->format('Y-m-d');
				$dates[] = $date;
			}
		}

		return $dates;
	}

	public function getRelated($IDs)
	{
//		if($IDs)
//		{
//			$items = JFactory::getDbo()->setQuery('SELECT * FROM #__modern_tours_assets WHERE id IN (' . $IDs . ')')->loadObjectList();
//			return $this->itemAdditions($items);
//		}
	}

	public function itemAdditions($items)
	{
		foreach ($items as $item)
		{
			if (isset($item->category))
			{
				$item->category_id = $item->category;
				$values    = explode(',', $item->category);
				$textValue = array();

				foreach ($values as $value)
				{
					if (!empty($value))
					{
						$db    = JFactory::getDbo();
						$query = "SELECT id, title FROM #__modern_tours_categories WHERE id LIKE '" . $value . "'";

						$db->setQuery($query);
						$results = $db->loadObject();

						if ($results)
						{
							$textValue[] = $results->title;
						}
					}
				}

				$item->category = !empty($textValue) ? implode(', ', $textValue) : $item->category;
			}

			if (isset($item->location))
			{
				$values    = explode(',', $item->location);
				$textValue = array();

				foreach ($values as $value)
				{
					if (!empty($value))
					{
						$db    = JFactory::getDbo();
						$query = "SELECT id, title FROM #__modern_tours_locations WHERE id LIKE '" . $value . "'";

						$db->setQuery($query);
						$results = $db->loadObject();

						if ($results)
						{
							$textValue[] = $results->title;
						}
					}
				}

				$item->location = !empty($textValue) ? implode(', ', $textValue) : $item->location;
			}

			$item->ratings = $this->ratingsSummary($item->id);
		}

		return $items;
	}

	public function ratingsSummary($id)
	{
		return JFactory::getDbo()->setQuery('SELECT COUNT(id) as count, ROUND( AVG(rating),1 ) AS rating FROM #__modern_tours_reviews WHERE assets_id = ' . $id)->loadObject();
	}

	/**
	 * Overrides the default function to check Date fields format, identified by
	 * "_dateformat" suffix, and erases the field if it's not correct.
	 *
	 * @return void
	 */
	protected function loadFormData()
	{
		$app              = JFactory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;

		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && $this->isValidDate($value) == null)
			{
				$filters[$key]    = '';
				$error_dateformat = true;
			}
		}

		if ($error_dateformat)
		{
			$app->enqueueMessage(JText::_("COM_MODERN_TOURS_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	/**
	 * Checks if a given date is valid and in a specified format (YYYY-MM-DD)
	 *
	 * @param   string  $date  Date to be checked
	 *
	 * @return bool
	 */
	private function isValidDate($date)
	{
		$date = str_replace('/', '-', $date);
		return (date_create($date)) ? JFactory::getDate($date)->format("Y-m-d") : null;
	}

	public function getOrdering($order)
	{
		switch ($order):
			case 'newest':
				$ordering = 'a.id DESC';
				break;
			case 'oldest':
				$ordering = 'a.id ASC';
				break;
			case 'random':
				$ordering = 'RAND()';
				break;
			case 'order':
				$ordering = 'a.ordering DESC';
				break;
			case 'rorder':
				$ordering = 'a.ordering ASC';
				break;
			default:
				$ordering = 'a.id DESC';
				break;
		endswitch;

		return $ordering;
	}
}

<?php

/**
 * @version    CVS: 1.0.0
 * @package    com_modern_tours
 * @author     modernjoomla.com <support@modernjoomla.com>
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
class Modern_toursModelLocations extends JModelList
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
				'title', 'a.title',
				'image', 'a.image',
				'alias', 'a.alias',
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

        $ordering  = $app->getUserStateFromRequest($this->context . '.ordercol', 'filter_order', $ordering);
        $direction = $app->getUserStateFromRequest($this->context . '.orderdirn', 'filter_order_Dir', $ordering);

        $this->setState('list.ordering', $ordering);
        $this->setState('list.direction', $direction);

        $start = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0, 'int');
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
		$query
			->select(
				$this->getState(
					'list.select', 'a.*'
				)
			);

		$query->from('`#__modern_tours_locations` AS a');
		$query->select('COUNT(asset.id) as tours_count, asset.id');
		$query->join('left', '#__modern_tours_assets AS asset ON asset.location=a.id');

		$appParams = JFactory::getApplication()->getParams('com_modern_tours');

		$locations = isset(MTHelper::getParams()->locations) ? MTHelper::getParams()->locations : $this->getState($this->context . 'items');
		$load_from = $appParams->get('load_from');

		if(!$locations && $load_from == 'locations')
		{
			$locations = $appParams->get('locations');
		}

		if(isset($locations))
		{
			if(is_array($locations))
			{
				$locations = implode('","', $locations);
			}
			$query->where('a.alias IN ("' . $locations . '")');
		}

		$language = MTHelper::getMenuLang();

		if($language != '*')
		{
			$query->where('a.language = "' . $language . '"');
		}

		$query->where('a.state = 1');
		$query->group('a.id');

		$moduleOrdering = $this->getState('ordering.module');

		if($moduleOrdering)
		{
			$query->order($this->getOrdering($moduleOrdering));
		}

		return $query;
	}

	/**
	 * Method to get an array of data items
	 *
	 * @return  mixed An array of data on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();

		return $items;
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

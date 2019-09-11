<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Modern_tours
 * @author     Modernjoomla.com <support@modernjoomla.com>
 * @copyright  modernjoomla.com
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Modern_tours records.
 *
 * @since  1.6
 */
class Modern_toursModelReservations extends JModelList
{
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
				'id', 'a.`id`',
				'ordering', 'a.`ordering`',
				'created_by', 'a.`created_by`',
				'modified_by', 'a.`modified_by`',
				'asset_id', 'a.`asset_id`',
				'status', 'a.`status`',
				'name', 'a.`name`',
				'surname', 'a.`surname`',
				'phone', 'a.`phone`',
				'email', 'a.`email`',
				'address', 'a.`address`',
				'date', 'a.`date`',
				'price', 'a.`price`',
				'people', 'a.`people`',
				'additional', 'a.`additional`',
				'registered', 'a.`registered`',
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
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);
		// Filtering status
		$this->setState('filter.status', $app->getUserStateFromRequest($this->context.'.filter.status', 'filter_status', '', 'string'));


		// Load the parameters.
		$params = JComponentHelper::getParams('com_modern_tours');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.id', 'desc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return   string A store id.
	 *
	 * @since    1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

		return parent::getStoreId($id);
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
				'list.select', 'DISTINCT a.*'
			)
		);
		$query->from('`#__modern_tours_reservations` AS a');


		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`');
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');

		// Join over the user field 'modified_by'
		$query->select('`modified_by`.name AS `modified_by`');
		$query->join('LEFT', '#__users AS `modified_by` ON `modified_by`.id = a.`modified_by`');

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
				$query->where('( a.name LIKE ' . $search . '  OR  a.surname LIKE ' . $search . '  OR  a.phone LIKE ' . $search . '  OR  a.address LIKE ' . $search . '  OR  a.date LIKE ' . $search . '  OR  a.registered LIKE ' . $search . ' )');
			}
		}


		// Filtering status
		$filter_status = $this->state->get("filter.status");

		if ($filter_status !== null && (is_numeric($filter_status) || !empty($filter_status)))
		{
			$query->where("a.`status` = '".$db->escape($filter_status)."'");
		}
		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 */
	public function getItems($forCSV = false)
	{
		$items = parent::getItems();

		if($forCSV)
		{
			foreach ($items as $oneItem)
			{
				$oneItem->status = JText::_($oneItem->status);
				$oneItem->assets_id = strip_tags($this->getAsset($oneItem->assets_id));
				$oneItem->userData = $this->JSONToCSV($oneItem->userData);
				$oneItem->user_id = $oneItem->user_id ? JFactory::getUser($oneItem->user_id)->name : 'Guest';
			}
		}
		else
		{
			foreach ($items as $oneItem)
			{
				$oneItem->real_status = JText::_($oneItem->status);
				$oneItem->status = $this->paidLink($oneItem);
				$oneItem->assets_id = $this->getAsset($oneItem->assets_id);
				$oneItem->userData = $this->toText(json_decode($oneItem->userData));
				$oneItem->user_id = $oneItem->user_id ? JFactory::getUser($oneItem->user_id)->name : 'Guest';
			}
		}


		return $items;
	}

	public function JSONToCSV($data)
	{
		$text = '';
		$data = json_decode($data);

		foreach($data as $name => $value)
		{
			$text .= $name . ' ' . $value . ' | ';
		}

		return $text;
	}

	public function toText($userData)
	{
		$info = '';

		if($userData)
		{
			foreach($userData as $field => $value)
			{
				$field = ucfirst(str_replace('-', ' ', $field));
				$info .= '<div class="info-line"><span class="high-text">' . $field . '</span> ' . $value . '</div>';
			}
		}

		if(!$info)
		{
			$info = '<i>' . JText::_( 'NO_DATA' ) . '</i>';
		}

		return $info;
	}

	public function getAsset($id)
	{
		$item = JFactory::getDbo()->setQuery('SELECT * FROM #__modern_tours_assets WHERE id = ' . $id)->loadObject();
		if($item) {
			return '<div class="asset"><a target="_blank" href="index.php?option=com_modern_tours&view=asset&layout=edit&id=' . $id . '">' . $item->title . '</a></div>';
		}
	}

	private function paidLink($item)
	{
		$status = JText::_($item->status);
		if (JFactory::getApplication()->isSite()) {
			return $status;
		}
		return '<div class="statuses">
            <div class="status ' . $status . '"><span class="state">' . $status . '</span></div>
            <div class="change-status">
                <div class="states-list">
                    <div class="paid states">' . JText::_('PAID') . '</div>
                    <div class="partially paid states">' . JText::_('PARTIALLY_PAID') . '</div>
                    <div class="reserved states">' . JText::_('RESERVED') . '</div>
                    <div class="waiting states">' . JText::_('WAITING') . '</div>
                    <div class="canceled states">' . JText::_('CANCELED') . '</div>
                </div>
            </div>
            </div>';
	}
}

<?php

/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jonas <labasas@gmail.com> - http://www.modernjoomla.com
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
require_once JPATH_SITE . '/components/com_modern_tours/helpers/modern_tours.php';

/**
 * Methods supporting a list of Modern_tours records.
 */
class Modern_toursModelAssets extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                                'id', 'a.`id`',
                'ordering', 'a.`ordering`',
                'state', 'a.`state`',
                'created_by', 'a.`created_by`',
                'times', 'a.`times`',

            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     */
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);

        // Load the parameters.
        $params = JComponentHelper::getParams('com_modern_tours');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.id', 'asc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param	string		$id	A prefix for the store id.
     * @return	string		A store id.
     * @since	1.6
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id.= ':' . $this->getState('filter.search');
        $id.= ':' . $this->getState('filter.state');

        return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'DISTINCT a.*'
                )
        );
        $query->from('`#__modern_tours_assets` AS a');

        
		// Join over the users for the checked out user
		$query->select("uc.name AS editor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");
		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`');
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');

	    $query->select('l.title AS language_title, l.image AS language_image')
	          ->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');

		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.state = ' . (int) $published);
		} else if ($published === '') {
			$query->where('(a.state IN (0, 1))');
		}

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
            }
        }

	    $user = JFactory::getUser();
	    $groups = $user->groups;

	    if (in_array(7, $groups)):
//		    $query->where('created_by = ' . $user->id);
	    endif;

        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }

        return $query;
    }

    public function each($items)
    {
    	foreach ($items as $item)
	    {
		    $item->location = $this->getLocation($item->location);
		    $item->category = $this->getCategory($item->category);
		    $item->field = $this->setFielsData($item, 'user_data_fields');
	    }

	    return $items;
    }

    public function getItems() {
	    $items = parent::getItems();
	    $items = $this->each($items);

        return $items;
    }

    public function getLocation($id)
    {
    	if($id)
	    {
		    $title = JFactory::getDbo()->setQuery('SELECT title FROM #__modern_tours_locations WHERE id = ' . $id)->loadResult();
		    return '<a target="_blank" href="index.php?option=com_modern_tours&view=location&layout=edit&id=' . $id . '">' . $title . '</a>';
	    }
	    return JText::_( 'NO_LOCATION' );
    }

	public function getCategory($id)
	{
		if($id)
		{
			$title = JFactory::getDbo()->setQuery('SELECT title FROM #__modern_tours_categories WHERE id = ' . $id)->loadResult();
			return '<a target="_blank" href="index.php?option=com_modern_tours&view=category&layout=edit&id=' . $id . '">' . $title . '</a>';
		}
		return JText::_( 'NO_CATEGORY' );
	}

	public function setFielsData($item, $field)
	{
		$id = isset($item->params->{$field}) ? $item->params->{$field} : MTHelper::getComponentParams($field);

		if($id)
		{
			$title = JFactory::getDbo()->setQuery('SELECT title FROM #__modern_tours_forms WHERE id = ' . $id)->loadResult();

			if(!$title)
			{
				return '<a target="_blank" href="index.php?option=com_modern_tours&view=modernform&layout=edit">' . JText::_('NO_FIELDS_CREATED') . '</a>';
			}
			else
			{
				return '<a target="_blank" href="index.php?option=com_modern_tours&view=modernform&layout=edit&id=' . $id . '">' . $title . '</a>';
			}
		}
		else
		{
			return '<a target="_blank" href="index.php?option=com_modern_tours&view=modernform&layout=edit">' . JText::_('NO_FIELDS_CREATED') . '</a>';
		}
	}
}

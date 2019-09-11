<?php

/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jonas <jonasjov2@gmail.com> - http://www.modernjoomla.com
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Modern_tours records.
 */
class Modern_toursModelCoupons extends JModelList {

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
                'title', 'a.`title`',
                'code', 'a.`code`',
                'start', 'a.`start`',
                'end', 'a.`end`',
                'couponsnumber', 'a.`couponsnumber`',
                'pricepercent', 'a.`pricepercent`',
                'pricetype', 'a.`pricetype`',

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


        //Filtering start
        $this->setState('filter.start.from', $app->getUserStateFromRequest($this->context.'.filter.start.from', 'filter_from_start', '', 'string'));
        $this->setState('filter.start.to', $app->getUserStateFromRequest($this->context.'.filter.start.to', 'filter_to_start', '', 'string'));

        //Filtering end
        $this->setState('filter.end.from', $app->getUserStateFromRequest($this->context.'.filter.end.from', 'filter_from_end', '', 'string'));
        $this->setState('filter.end.to', $app->getUserStateFromRequest($this->context.'.filter.end.to', 'filter_to_end', '', 'string'));

        //Filtering pricetype
        $this->setState('filter.pricetype', $app->getUserStateFromRequest($this->context.'.filter.pricetype', 'filter_pricetype', '', 'string'));


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
        $query->from('`#__modern_tours_coupons` AS a');


        // Join over the users for the checked out user
        $query->select("uc.name AS editor");
        $query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");
        // Join over the user field 'created_by'
        $query->select('`created_by`.name AS `created_by`');
        $query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');



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



        //Filtering start
        $filter_start_from = $this->state->get("filter.start.from");
        if ($filter_start_from) {
            $query->where("a.`start` >= '".$db->escape($filter_start_from)."'");
        }
        $filter_start_to = $this->state->get("filter.start.to");
        if ($filter_start_to) {
            $query->where("a.`start` <= '".$db->escape($filter_start_to)."'");
        }

        //Filtering end
        $filter_end_from = $this->state->get("filter.end.from");
        if ($filter_end_from) {
            $query->where("a.`end` >= '".$db->escape($filter_end_from)."'");
        }
        $filter_end_to = $this->state->get("filter.end.to");
        if ($filter_end_to) {
            $query->where("a.`end` <= '".$db->escape($filter_end_to)."'");
        }

        //Filtering pricetype
        $filter_pricetype = $this->state->get("filter.pricetype");
        if ($filter_pricetype != '') {
            $query->where("a.`pricetype` = '".$db->escape($filter_pricetype)."'");
        }


        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }

        return $query;
    }

    public function getItems() {
        $items = parent::getItems();

        foreach ($items as $oneItem) {
            $oneItem->pricetype = JText::_('COM_MODERN_TOURS_COUPONS_PRICETYPE_OPTION_' . strtoupper($oneItem->pricetype));
        }
        return $items;
    }

}

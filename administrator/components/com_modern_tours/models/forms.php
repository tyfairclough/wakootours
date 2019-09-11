<?php

/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   Copyright (C) 2015. All rights reserved. Unikalus team.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jonas Jovaisas <jonasjov2@gmail.com> - http://modernjoomla.com
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
require_once(JPATH_SITE . '/components/com_modern_tours/helpers/simple_html_dom.php');

/**
 * Methods supporting a list of Modern_tours records.
 */
class Modern_toursModelForms extends JModelList
{

    public $data;
    public $search;
    public $columnNames;

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array())
    {
        $category = JFactory::getApplication()->input->get('id');
        $inputCookie = JFactory::getApplication()->input->cookie;
        if ($category) {
            $inputCookie->set('category', $category, $expire = 0);
        } else {
            $category = $inputCookie->get($name = 'category', $defaultValue = null);
        }
        $this->columns = 'id,paid,start,registered,price';
        $this->category = $category;

        if (JFactory::getApplication()->isSite()) {
            $this->category = JFactory::getApplication()->input->get('category');
        }

        parent::__construct($config);
    }

    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     */
    protected function populateState($ordering = null, $direction = null)
    {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $paid = $app->getUserStateFromRequest($this->context . '.filter.paid', 'filter_paid');
        $this->setState('filter.paid', $paid);

        $published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);


        //Filtering category
        $this->setState('filter.category', $app->getUserStateFromRequest($this->context . '.filter.category', 'filter_category', '', 'string'));

        //Filtering type
        $this->setState('filter.type', $app->getUserStateFromRequest($this->context . '.filter.type', 'filter_type', '', 'string'));


        // Load the parameters.
        $params = JComponentHelper::getParams('com_modern_tours');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.name', 'asc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param    string $id A prefix for the store id.
     * @return    string        A store id.
     * @since    1.6
     */
    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.state');

        return parent::getStoreId($id);
    }

    public function getAllColumns()
    {
        return $this->getColumns('', '', true);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return    JDatabaseQuery
     * @since    1.6
     */

    public function getColumns($flip = false, $category = null, $hideAble = false)
    {
        if ($category) {
            $this->category = $category;
        }

	    if (JFactory::getApplication()->isSite())
	    {
            $exclude = MBHelper::getParams()->fields;
            $exludedFields = explode(',' , $exclude);
	    }
	    
        $columns = JFactory::getDbo()->setQuery('SHOW COLUMNS FROM #__modern_tours_forms_' . $this->category)->loadObjectList();
        foreach ($columns as $col) {
	        if (JFactory::getApplication()->isSite())
	        {
		        if(!array_search($col->Field, $exludedFields)) {
			        $fields[] = $col->Field;
		        }
	        } else {
		        $fields[] = $col->Field;
	        }
        }

        $hideCols = array('id', 'unique_id', 'userid', 'usergroup', 'end_time');

        if(!$hideAble) {
            $zz = array();
            $f = array_flip($fields);
            foreach($_COOKIE as $i => $val)
            {
                if(!is_array($val))
                {
                    $xx = explode('_', $val);
                    if(array_key_exists(1, $xx))
                    {
                        if ($this->category == $xx[1])
                        {
                            $zz[$xx[0]] = $xx[0];
                        }
                    }
                }

            }
            $mix = array_intersect_key($zz, $f);
            $hideCols += $mix;
        }

        $headings = array_diff($fields, $hideCols);

        if ($flip) {
            $headings = array_flip($headings);
        }

        return $headings;
    }

    public function getItems($search = null)
    {
        $input = JFactory::getApplication()->input;
        $listOrder = $input->get('filter_order');
        $listDirn = $input->get('filter_order_Dir');

        if ($listOrder AND $listDirn) {
            $order = 'ORDER BY `' . $listOrder . '` ' . $listDirn;
        } else {
            $order = 'ORDER BY registered DESC';
        }

        $this->createSearch();

        return $this->filter(JFactory::getDbo()->setQuery('SELECT *, price, GROUP_CONCAT(start) as start FROM #__modern_tours_forms_' . $this->category . ' ' . $this->search . ' GROUP BY unique_id  ' . $order)->loadObjectList());
    }

    public function filter($items)
    {
        foreach($items as $item) {

            $item->userid = JFactory::getUser($item->userid)->name;
            $usergroup = ($item->usergroup ? $item->usergroup : 0);

            $fields = array(
                'userid' => JFactory::getUser($item->userid)->name,
                'start' => $this->setTime($item->start, $item->end_time),
                'paid' => self::paidLink($item),
                'usergroup' => JFactory::getDbo()->setQuery('SELECT GROUP_CONCAT(title) as title FROM #__usergroups WHERE id IN (' . $usergroup . ') GROUP by title')->loadResult(),
                'registered' => str_replace('-', '.', $item->registered),
                'item_start' => self::transformStartDate($item->start),
                'item_end' => self::transformDate($item->start, $item->end_time),
                'paid_status' => JText::_($item->paid),
            );

            foreach($fields as $key => $value) {
                $item->{$key} = $value;
            }
        }

        return $items;
    }

    private function paidLink($item)
    {
        $status = JText::_($item->paid);
        if (JFactory::getApplication()->isSite()) {
            return $status;
        }
        return '<div class="statuses">
            <div class="status ' . $status . '"><span class="state">' . $status . '</span></div>
            <div class="change-status">
                <div class="states-list">
                    <div class="paid states">Paid</div>
                    <div class="partially paid states">' . JText::_('PARTIALLY_PAID') . '</div>
                    <div class="reserved states">Reserved</div>
                    <div class="waiting states">Waiting</div>
                    <div class="canceled states">Canceled</div>
                </div>
            </div>
            </div>';
    }

    public function getTimeslots($timeslot = null)
    {
        $items = JFactory::getDbo()->setQuery('SELECT id,title FROM #__modern_tours_assets')->loadObjectList();

        if (count($items) == 1) {
            $timeslot = $items[0]->id;
        }

        $timeslots[] = JHTML::_('select.option', '', 'Select timeslot');

        foreach ($items as $item) {
            $timeslots[] = JHTML::_('select.option', $item->id, $item->title);
        }

        $select = JHTML::_('select.genericlist', $timeslots, 'timecategory', 'class="inputbox"', 'value', 'text', $timeslot);

        return $select;
    }

    public function getSelects($selects = array())
    {
        $html = JFactory::getDbo()->setQuery('SELECT formdata FROM #__modern_tours_forms WHERE id = ' . $this->category)->loadResult();
        $html = str_get_html($html);
        foreach ($html->find('field') as $element) {
            if ($element->type == 'select' OR $element->type == 'agents') {
                $selects[] = $this->createSelect($element->find('option'), $element->name);
            }
        }

        if($this->isAdmin()) {
            $selects[] = $this->getUsers();
//            $selects[] = $this->getUserGroups();
        }

        return $selects;
    }

    private function createSearch()
    {
        $input = JFactory::getApplication()->input;
        $search_string = $input->getString('search');
        $columns = $this->getColumns(true);
        $columns['userid'] = '';
        $columns['usergroup'] = '';
        $search_values = $input->getArray($columns);
        $count = count($search_values);
        $and = '';
        if ($search_string) {
            $search_parts = explode(' ', $search_string);
            if(array_key_exists($search_parts[0], $search_values)) {
                $search_values[$search_parts[0]] = $search_parts[1];
            }
        }

        if ($search_values) {
            if(!empty($search_values)) {
                $this->search = 'WHERE `' . key($search_values) . '`' . self::whereFilter($search_values[key($search_values)], key($search_values));
            } else {
                $and = 'WHERE';
            }
        }
        if ($count > 1) {
            $and = 'AND ';
            array_shift($search_values);
            foreach ($search_values as $key => $val) {
                if(!empty($val)) {
                    $this->search .= 'AND `' . $key . '` ' . self::whereFilter($val, $key);
                }
            }
        }

        if(!$this->isAdmin() && !JFactory::getApplication()->isAdmin()) {
            $this->search .= $and . 'userid = ' . JFactory::getUser()->id . ' ';
        }

        if($input->getString('datefrom') AND $input->getString('dateto')) {
            $this->search .= $and . '(start BETWEEN "' . $input->getString('datefrom') . '" AND "' . $input->getString('dateto') . '")';
        }
    }

    public function isAdmin()
    {
        $user = JFactory::getUser();
        if(array_key_exists('7', $user->groups) OR array_key_exists('8', $user->groups)) {
            return true;
        }
    }

    protected function whereFilter($value, $column = null)
    {
        switch ($column) {
            case 'usergroup':
                if(!$value) { $value = 0; }
                $condition = ' IN (' . $value . ') ';
                break;
            case 'userid':
            $condition = ' = ' . $value . ' ';
            break;
            default:
                $condition = ' LIKE "%' . $value . '%" ';
                break;
        }

        return $condition;
    }

    public function getUsers()
    {
        $users = JFactory::getDbo()->setQuery('SELECT name, id FROM #__users')->loadObjectList();

        foreach($users as $user) {
            $user->value = $user->id;
            $user->innertext = $user->name;
        }

        return $this->createSelect($users, 'userid', 'User');
    }

    public function getUserGroups()
    {
        $userGroups = JFactory::getDbo()->setQuery('SELECT title, id FROM #__usergroups')->loadObjectList();

        foreach($userGroups as $user) {
            $user->value = $user->id;
            $user->innertext = $user->title;
        }

        return $this->createSelect($userGroups, 'usergroup', 'Group User');
    }

    private function createSelect($array = array(), $name, $anothername = '')
    {
        $thename = $anothername ? str_replace('-', ' ', $anothername) : str_replace('-', ' ', $name);
        $selectList[] = JHTML::_('select.option', '', JText::_('SELECT_BY') . $thename);

        foreach ($array as $opt) {
            $selectList[] = JHTML::_('select.option', $opt->value, $opt->innertext);
        }

        $value = JFactory::getApplication()->input->getStr($name);
        if (!$value) {
            $value = 'Select ' . $name;
        }

        return JHTML::_('select.genericlist', $selectList, $name, 'class="inputbox" onchange="form.submit();"', 'value', 'text', $value);
    }

    private function export()
    {
        $this->columns = '`' . implode('`,`', $this->getColumns()) . '`';

        $this->SQLcolumns = str_replace("`start`", "GROUP_CONCAT(start) as start", $this->columns);

        $this->createSearch();

        $this->data = $this->filter(JFactory::getDbo()->setQuery('SELECT ' . $this->SQLcolumns . ' FROM #__modern_tours_forms_' . $this->category . ' ' . $this->search . ' GROUP BY unique_id')->loadAssocList());
    }

    public function generateCSV()
    {
        $this->export();

        $file = 'export_' . date('Y_m_d_H_i_s') . '.csv';
        $path = __DIR__ . '../../csv/';
        $fullpath = $path . $file;

        $this->columns = str_replace('`', '', $this->columns);
        ob_get_clean();
        $fp = fopen($path . $file, 'w');

        fputcsv($fp, array('SEP=,'));
        fputcsv($fp, explode(',', $this->columns));

        foreach ($this->data as $fields) {
            if(is_array($fields)) {
                fputcsv($fp, $fields, ',', chr(0));
            }
        }

        fclose($fp);

        header('Content-disposition: attachment; filename=' . $file);
        header('Content-type: application/csv'); // works for all extensions except txt
        readfile($fullpath);
        JFactory::getApplication()->close();
    }

    public function setTime($value, $end_time)
    {
        $time_array = explode(',', $value);
        if(strlen($time_array[0]) != 10) {
            $start_time = substr($time_array[0], 0, -3);
        } else {
            $start_time = $time_array[0];
        }

        if(count($time_array) == 1) {
            $the_time = '<span class="blocks">' . $start_time . ' - ' . $end_time . '</span>';
        }
        else {
            if(count(array_unique($time_array)) == 1) {
                $times_reserved = count($time_array);
                $the_time = '<span class="blocks">' . $start_time . ' - ' . $end_time . '<br>Reservations: ' . $times_reserved . '</span>';
            }
            else {
                $the_time = '<span class="blocks">' . $start_time . ' - ' . $end_time . '</span>';
                $the_time .= '<div class="showed">Show reserved slots</div><div style="display: none;" class="all_times">';
                foreach ($time_array as $block) {
                    $the_time .= '<span class="blocks">' . substr($block, 0, -3) . '</span>';
                }
                $the_time .= '</div>';
            }
        }

        return $the_time;
    }

    private function transformDate($date, $dateEnd) {

        $dateParts = explode(' ', $date);

        return $dateParts[0] . ' ' . $dateEnd . ':00';
    }

    private function transformStartDate($date) {

        if(strpos($date, ',')) {
            $dateParts = explode(',', $date);
            return $dateParts[0];
        }

        return $date;
    }

}

?>


<?php
/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jonas <labasas@gmail.com> - http://www.modernjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Modern_tours model.
 */
class Modern_toursModelAsset extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_MODERN_TOURS';


	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Asset', $prefix = 'Modern_toursTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_modern_tours.asset', 'asset', array('control' => 'jform', 'load_data' => $loadData));
        
        
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_modern_tours.edit.asset.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		if(!is_array($data))
		{
			$data->params['additional'] = $this->loadParamsArray($data->params);
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{

		if ($item = parent::getItem($pk)) {

			//Do any procesing on fields here if needed

		}

		$item->related = (array) $item->related;

		return $item;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since	1.6
	 */
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');

		if (empty($table->id)) {

			// Set ordering to the last item if not set
			if (@$table->ordering === '') {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__modern_tours_assets');
				$max = $db->loadResult();
				$table->ordering = $max+1;
			}

		}
	}

	/**
	 *  Load params array
	 */
	public function loadParamsArray($params)
	{
		$js = 'jQuery(document).ready(function() { ';

		foreach($params as $name => $value)
		{
			if($name != 'itirenary')
			{
				if(is_array($value))
				{
					$value = (object) $value;
					$js .= 'var ' . $name . ' = \'' . json_encode($value) . '\'; $.reCreateFields("' . $name . '", "#' . $name . '-fields", ' . $name . ');';
				}
			}

		}
		$js .= ' });';

		return $js;
	}

	/**
	 *  Gets user groups
	 */
	public function getUserGroups()
	{
		$db = JFactory::getDbo();
		$query  = $db->getQuery(true);
		$query->select('id, title');
		$query->from('#__usergroups');
		$db->setQuery($query);
		$rows   = $db->loadObjectList();

		foreach($rows as $row) {
			$groups[$row->id] = $row->title;
		}

		return json_encode($groups);
	}
	
}
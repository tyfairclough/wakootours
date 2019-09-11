<?php

/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   Copyright (C) 2015. All rights reserved. Unikalus team.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jonas Jovaisas <jonasjov2@gmail.com> - http://modernjoomla.com
 */
defined('_JEXEC') or die;

class DB {

	public $db, $prefix, $newcols, $message = '';

	public function __construct( $config = array() ) {
		$this->db = JFactory::getDbo();
		$this->prefix = $this->db->getPrefix();
		$this->newcols = array(
			'modern_tours_assets' =>
				array('discount', 'times', 'params')
		);
	}

	public function getColumn($table, $name)
	{
		return $this->db->setQuery('SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = "' . $this->prefix . $table . '" AND COLUMN_NAME = "' . $name . '"')->loadObject();
	}

	public function getForms()
	{
		$ids = $this->db->setQuery('SELECT id FROM ' . $this->prefix . 'modern_tours_forms')->loadAssocList();
		return array_map(function($a) {
			return 'modern_tours_forms_' . $a['id'];
		}, $ids);
	}

	public function addField($table, $column, $type = 'varchar(255)')
	{
		if(!$type) {
			$type = 'varchar(255)';
		}
		$this->message .= $column . ' for table ' . $table . ' added.<br>';
		return $this->db->setQuery('ALTER TABLE ' . $this->prefix . $table . ' ADD COLUMN ' . $column . ' ' . $type)->execute();
	}

	public function modifyColumn($table, $column, $type = 'varchar(255)')
	{
		return $this->db->setQuery('ALTER TABLE ' . $this->prefix . $table . ' MODIFY COLUMN ' . $column . ' ' . $type)->execute();
	}

	public function addColumn($table, $column, $type = '')
	{
		if(!self::getColumn( $table, $column ))
		{
			self::addField($table, $column, $type);
		}
	}

	public function addReservations()
	{
		$tables  = self::getForms();

		foreach($tables as $table)
		{
			self::addColumn( $table, 'reservations', $type = '' );
			self::modifyColumn( $table, 'paid', 'VARCHAR(10)' );
		}


		foreach($this->newcols as $table => $columns)
		{
			foreach($columns as $column)
			{
				self::addColumn( $table, $column, 'MEDIUMTEXT' );
			}
		}

		echo $this->message;
	}

	public function checkColumns()
	{
		$update = false;
		$tables  = self::getForms();

		foreach($tables as $table)
		{
			$exist = self::getColumn( $table, 'reservations');
			if(!$exist) {
				$update = true;
			}
		}


		foreach($this->newcols as $table => $columns)
		{
			foreach($columns as $column)
			{
				$exist = self::getColumn( $table, $column);
				if(!$exist) {
					$update = true;
				}
			}
		}

		return $update;
	}

}
<?php
/**
 * @copyright    Copyright (c) 2015 mollie. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * tourpayment - Mollie Plugin
 *
 * @package        Joomla.Plugin
 * @subpakage    mollie.Mollie
 */
class FieldsHelper
{
	public $id, $html;

	public function setId($id)
	{
		$id = JFactory::getDbo()->setQuery('SELECT formdata FROM #__modern_tours_forms WHERE id = ' . $id)->loadResult();
		if($id)
		{
			$this->id = $id;
			$this->html = str_get_html($id);
		}
	}


	public function getUserFieldsID($field, $id)
	{
		if($id)
		{
			$params = JFactory::getDbo()->setQuery('SELECT params FROM #__modern_tours_assets WHERE id = ' . $id)->loadResult();

			if($params)
			{
				$item = json_decode($params);
				return $item->{$field} ? $item->{$field} : MTHelper::getComponentParams($field);
			}
		}
	}

}
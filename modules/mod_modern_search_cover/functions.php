<?php
/**
 * @copyright	Copyright © 2018 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @generator	http://xdsoft/joomla-module-generator/
 */
defined('_JEXEC') or die;

class SearchCover
{
    public static function countFields($params)
    {
	    $count = array();
	    $fields = array('search', 'calendar', 'categories', 'locations');

	    foreach($fields as $field)
	    {
		    if($params->get($field))
		    {
			    $count[] = 1;
		    }
	    }

	    return array_sum($count);
    }
}
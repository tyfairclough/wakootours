<?php
/**
 * @copyright	Copyright © 2018 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @generator	http://xdsoft/joomla-module-generator/
 */
defined('_JEXEC') or die;

class Helper
{
	public static function getDirectory()
	{
		$view = JFactory::getApplication()->input->get('view');
		$alias = MTHelper::getAlias();
		$views = array('location' => 'locations', 'category' => 'categories');
		$view = isset($views[$view]) ? $views[$view] : $view;

		return JFactory::getDbo()->setQuery('SELECT * FROM #__modern_tours_' . $view . ' WHERE alias = "' . $alias . '"')->loadObject();
	}

	public static function getData($params, $name)
	{
		$view = JFactory::getApplication()->input->get('view');

		if($view == 'location' OR $view == 'category')
		{
			$directoryAsset = Helper::getDirectory();
		}

		return isset($directoryAsset) ? $directoryAsset->{$name} : $params->get($name);
	}
}
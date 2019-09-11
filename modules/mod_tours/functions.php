<?php
/**
 * @copyright	Copyright © 2018 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @generator	http://xdsoft/joomla-module-generator/
 */
defined('_JEXEC') or die;

class TourList
{
	public static function getAssets($params, $id)
	{
		$model = JModelList::getInstance('Assets', 'modern_toursModel');
		$model->context = 'module' . $id . '.';

		$model->setState($model->context . 'assets.items', $params->get('aliases'));
		$model->setState($model->context . 'truncate.title', $params->get('truncate_title'));
		$model->setState($model->context . 'show.title', $params->get('show_title'));
		$model->setState($model->context . 'truncate.description', $params->get('truncate_description'));
		$model->setState($model->context . 'show.description', $params->get('show_description'));
		$model->setState($model->context . 'ordering.module', $params->get('list_ordering'));

		$loadFrom = $params->get('load_from');
		$category = $params->get('categories');

		if($category && $loadFrom == 'categories')
		{
			$model->setState($model->context . 'assets.category', $category);
		}

		$location = $params->get('locations');

		if($location && $loadFrom == 'locations')
		{
			$model->setState($model->context . 'assets.location', $location);
		}

		$items = $params->get('aliases');

		if($loadFrom == 'aliases')
		{
			$items = implode(',', $items);
			$model->setState($model->context . 'assets.items', $items);
		}

		$items = $model->getItems();
		$items = array_slice($items, 0, $params->get('max_items'));
		
		return $items;
	}

}
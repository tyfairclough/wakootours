<?php
/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   modernjoomla.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      modernjoomla.com <support@modernjoomla.com>
 */
// No direct access
defined('_JEXEC') or die;
/**
 * @param    array    A named array
 * @return    array
 */
function Modern_toursBuildRoute(&$query)
{
	$Itemid = getItemidT($query) ? getItemidT($query) : '';

	$view = isset($query['view']) ? $query['view'] : '';

	$itemidxx = findItemid($query, $id = '');
	if($itemidxx)
	{
		$parts = isAssetX($view, $query, $itemidxx);
		$segments = $parts[0];
		$query = $parts[1];
	}
	else
	{
		$parts = isAsset($view, $query, $Itemid);
		$segments = $parts[0];
		$query = $parts[1];
	}

	return $segments;
}

function isAssetX($view, $query, $itemid)
{
	$segments = array();
	if (isset($query['task'])) {
		$segments[] = implode('/', explode('.', $query['task']));
		unset($query['task']);
	}

	$query['Itemid'] = $itemid;
	unset($query['view']);


	if (isset($query['alias'])) {
		$segments[] = $query['alias'];
		unset($query['alias']);
	}

	return [$segments, $query];
}

function isAsset($view, $query, $itemid)
{
	$segments = array();
	if (isset($query['task'])) {
		$segments[] = implode('/', explode('.', $query['task']));
		unset($query['task']);
	}

	if (isset($query['view'])) {
		if($view == 'location')
		{
			unset($query['view']);
		}
		if($view == 'category')
		{
			unset($query['view']);
		}
		if($view == 'asset')
		{
			if(!$itemid)
			{
				$segments[] = $query['view'];
				unset($query['Itemid']);
			}
			else
			{
				$query['Itemid'] = $itemid;
			}
			unset($query['view']);
		}
	}

	if (isset($query['alias'])) {
		$segments[] = $query['alias'];
		unset($query['alias']);
	}

	return [$segments, $query];
}

/**
 * @param    array    A named array
 * @param    array
 * Formats:
 * index.php?/modern_tours/task/id/Itemid
 * index.php?/modern_tours/id/Itemid
 */
function Modern_toursParseRoute($segments)
{
	$vars = array();
	$hasActive = JFactory::getApplication()->getMenu()->getActive();
	
	if(isset($hasActive))
	{
		$menu = $hasActive->query['view'];
		if($menu == 'asset')
		{
			$vars['view'] = $menu;
			$vars['alias'] = array_shift($segments);
		}
		else
		{
			$vars = setRoute($hasActive, $segments[0], $vars);
		}
	}
	else
	{
		$vars['view'] = array_shift($segments);

		while (!empty($segments)) {
			$segment = array_pop($segments);
			if (isset($segment)) {
				$vars['alias'] = $segment;
			} else {
				$vars['task'] = $vars['view'] . '.' . $segment;
			}
		}
	}

	return $vars;
}

function setRoute($hasActive, $alias)
{
	$vars = array();
	$view = $hasActive->query['view'];
	$views = array(
		'locations' => 'location',
		'categories' => 'category'
	);

	if(isset($views[$view]) && $alias)
	{
		$vars['view'] = $views[$view];
		$vars['alias'] = $alias;
	}

	return $vars;
}

function getItemidT($query, $id = '')
{
	if(isset($query['view']))
	{
		$view = $query['view'];

		$menu = JFactory::getApplication()->getMenu();
		$items = $menu->getMenu();

		foreach($items as $item)
		{
			if(isset($item->query['view']))
			{

				if($item->query['view'] == $view && $item->query['option'] == 'com_modern_tours')
				{
					$id = $item->id;
					break;
				}
			}
		}

		return $id ? $id : '';
	}
}

function findItemid($query, $id = '')
{
	if(isset($query['view']))
	{
		$view = $query['view'];

		if($view == 'location')
		{
			$view = 'locations';
		}

		$menu = JFactory::getApplication()->getMenu();
		$items = $menu->getMenu();

		foreach($items as $item)
		{
			if(isset($item->query['view']))
			{
//				$item->language == 'en-GB' &&
				if($item->query['view'] == $view && $item->query['option'] == 'com_modern_tours')
				{
					$id = $item->id;
					break;
				}
			}
		}

		return $id ? $id : '';
	}
}

function getActiveMenu()
{
	return JFactory::getApplication()->getMenu()->getActive();
}

function getView()
{
	$activeMenu = getActiveMenu();

	if($activeMenu)
	{
		return $activeMenu->query['view'];
	}
}

function getActiveMenuView($view = '')
{
	$isActiveMenu = getActiveMenu();

	if($isActiveMenu)
	{
		$view = getView();
	}

	return $view;
}

function categorizeMenu($view, $newView = '')
{
	switch($view)
	{
		case $view == 'locations':
			$view = 'location';
			break;
		case $view == 'categories':
			$view = 'category';
			break;
	}
	return $view;
}
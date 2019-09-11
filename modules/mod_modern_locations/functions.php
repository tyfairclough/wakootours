<?php
/**
 * @copyright	Copyright © 2018 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @generator	http://xdsoft/joomla-module-generator/
 */
defined('_JEXEC') or die;

class TourData
{
	public static function getData($params, $id)
	{
		$loadFrom = TourData::defineSource($params->get('load_from'));
		$model = JModelList::getInstance($loadFrom, 'modern_toursModel');
		$context = 'module_' . $id . '.';
		$model->context = $context;
		$load = $params->get('load_from');

		if($load == 'locations' OR $load == 'categories')
		{
			$loadItems = $params->get($load);
			$model->setState($context . 'items', $loadItems );
		}

		$items = $model->getItems();
		$items = array_slice($items, 0, $params->get('max_items'));

		return $items;
	}

	public static function getSliderAssets($params, $module)
	{
		$id = $module->id;
		$doc = JFactory::getDocument();
		$doc->addStyleSheet("https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css");
		$doc->addStyleSheet("https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css");
		$doc->addScript(JURI::base(). 'media\com_modern_tours\js\owl.carousel.min.js');
		$doc->addScriptDeclaration('
				jQuery(function ($) {
		            $("#asset-list-' .$id . '").owlCarousel({
		                items: ' .$params->get("items") . ',
		                nav: ' .$params->get("nav") . ',
		                margin: ' .$params->get("margin") . ',
		                loop: ' .$params->get("loop") . ',
		                autoplay: ' .$params->get("autoplay") . ',
		                dots: false
		            });
		            $("#nav' .$id . ' .next").click(function(){
		                $("#asset-list-' .$id . ' .owl-next").click();
		            })
		            $("#nav' .$id . ' .am-prev").click(function(){
		                $("#asset-list-' .$id . ' .owl-prev").click();
		            })
		        });
			');
		$doc->addStyleSheet('#asset-list-' . $id . ' .owl-nav { display: none; }');
	}

	public static function defineSource($load)
	{
		if($load == 'all_categories')
		{
			$loadFrom = 'categories';
		}
		elseif($load == 'all_locations')
		{
			$loadFrom = 'locations';
		}
		if(!isset($loadFrom))
		{
			$loadFrom = $load;
		}
		return $loadFrom;
	}

}
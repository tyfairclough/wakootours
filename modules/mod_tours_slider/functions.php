<?php
/**
 * @copyright	Copyright © 2018 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @generator	http://xdsoft/joomla-module-generator/
 */
defined('_JEXEC') or die;

class Tour
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
		                nav: ' .$params->get("nav") . ',
		                margin: ' .$params->get("margin") . ',
		                loop: ' .$params->get("loop") . ',
		                autoplay: ' .$params->get("autoplay") . ',
		                dots: false,
						responsive:{
						        0:{
						            items:1,
						            nav:true
						        },
						        600:{
						            items:2,
						            nav:false
						        },
						        801:{
						            items:' .$params->get("items") . '
						        }
						    }
		            });
		            $("#nav' .$id . ' .next").click(function(){
		                $("#asset-list-' .$id . ' .owl-next").click();
		            })
		            $("#nav' .$id . ' .am-prev").click(function(){
		                $("#asset-list-' .$id . ' .owl-prev").click();
		            })
		        });
			');
		$doc->addStyleDeclaration('#asset-list-' . $id . ' .owl-nav { display: none; }');
	}

}
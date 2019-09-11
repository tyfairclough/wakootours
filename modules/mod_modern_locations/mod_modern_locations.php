<?php
/**
 * @copyright	Copyright © 2018 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @generator	http://xdsoft/joomla-module-generator/
 */
defined('_JEXEC') or die;

JHtml::_('jquery.framework');
require_once( dirname(__FILE__) . '/functions.php' );
require_once( 'components/com_modern_tours/helpers/modern_tours.php' );
require_once( 'components/com_modern_tours/models/' . TourData::defineSource($params->get('load_from')) . '.php' );

MTHelper::loadLanguage();

$id = $module->id;
$doc = JFactory::getDocument();
$doc->addStyleSheet( 'media/com_modern_tours/css/style.css' );

if($params->get('create_slider'))
{
	TourData::getSliderAssets($params, $module);
}

$assets = TourData::getData($params, $module->id);

require JModuleHelper::getLayoutPath('mod_modern_locations', $params->get('layout', 'default'));
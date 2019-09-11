<?php
/**
 * @copyright	Copyright © 2018 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @generator	http://xdsoft/joomla-module-generator/
 */
defined('_JEXEC') or die;

JHtml::_('jquery.framework');
require_once( dirname(__FILE__) . '/functions.php' );
require_once( 'components/com_modern_tours/models/assets.php' );
require_once( 'components/com_modern_tours/models/asset.php' );
require_once( 'components/com_modern_tours/helpers/modern_tours.php' );
MTHelper::loadLanguage();

$id = $module->id;
$doc = JFactory::getDocument();
$doc->addStyleSheet( 'media/com_modern_tours/css/style.css' );
$doc->addStyleSheet( 'media/com_modern_tours/css/gridonly.css' );
$doc->addStyleSheet( 'https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css' );

Tour::getSliderAssets($params, $module);
$assets = Tour::getAssets($params, $module->id);

require JModuleHelper::getLayoutPath('mod_tours_slider', $params->get('layout', 'default'));
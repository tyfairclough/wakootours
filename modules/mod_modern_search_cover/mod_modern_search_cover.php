<?php
/**
 * @copyright	Copyright © 2018 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @generator	http://xdsoft/joomla-module-generator/
 */
defined('_JEXEC') or die;

require_once( dirname(__FILE__) . '/functions.php' );

$doc = JFactory::getDocument();
// Include assets
$doc->addStyleSheet(JURI::root()."modules/mod_modern_search_cover/assets/css/style.css");
$doc->addScript(JURI::root()."modules/mod_modern_search_cover/assets/js/script.js");
// $width 			= $params->get("width");

/**
	$db = JFactory::getDBO();
	$db->setQuery("SELECT * FROM #__mod_modern_search_cover where del=0 and module_id=".$module->id);
	$objects = $db->loadAssocList();
*/
require JModuleHelper::getLayoutPath('mod_modern_search_cover', $params->get('layout', 'default'));
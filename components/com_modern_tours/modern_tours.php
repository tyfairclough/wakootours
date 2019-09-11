<?php
/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   modernjoomla.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      modernjoomla.com <support@modernjoomla.com>
 */
defined('_JEXEC') or die;
// Include dependancies
jimport('joomla.application.component.controller');
// Execute the task.
$controller = JControllerLegacy::getInstance('Modern_tours');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();


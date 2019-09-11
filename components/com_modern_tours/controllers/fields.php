<?php
/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   modernjoomla.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      modernjoomla.com <support@modernjoomla.com>
 */
// No direct access.
defined('_JEXEC') or die;
require_once JPATH_COMPONENT . '/controller.php';

/**
 * Fields list controller class.
 */
class Modern_toursControllerFields extends Modern_toursController
{

    /**
     * Proxy for getModel.
     * @since    1.6
     */
    public function &getModel($name = 'Fields', $prefix = 'Modern_toursModel', $config = array())
    {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));

        return $model;

    }
}
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
class Modern_toursControllerPayment extends Modern_toursController
{

    /**
     * Proxy for getModel.
     * @since    1.6
     */
    public function pay()
    {
        $payment = JFactory::getApplication()->input->get('method');
        $order_id = JFactory::getApplication()->input->get('order_id');

        $this->importPlugin('acceptPayment', array('payment' => $payment, 'table' => '#__modern_tours_reservations', 'order_id' => $order_id));
    }

    public function cancel()
    {
        $this->importPlugin('cancel');
    }

    public function accept()
    {
        $this->importPlugin('accept');
    }

    public function importPlugin($trigger, $options = NULL)
    {
        JPluginHelper::importPlugin('tourpayment');
        $dispatcher = JEventDispatcher::getInstance();
        $options = json_encode($options);
        $dispatcher->trigger($trigger, $options);
    }
}
<?php
/**
 * @copyright    Copyright (c) 2015 mollie. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

require_once JPATH_COMPONENT . '/helpers/modern_tours.php';
require_once JPATH_COMPONENT . '/models/reservations.php';

/**
 * tourpayment - Mollie Plugin
 *
 * @package        Joomla.Plugin
 * @subpakage    mollie.Mollie
 */
class MTPayment extends JPlugin
{
    public $order_id, $price, $order, $deposit;
    /**
     * Constructor.
     *
     * @param    $subject
     * @param    array $config
     */
    function __construct(&$subject, $config = array())
    {
        $this->table = '#__modern_tours_reservations';
        self::setLogs();

        parent::__construct($subject, $config);
    }

    public function callBack()
    {
    	$deposit = JFactory::getApplication()->input->get('deposit');
	    $order = MTHelper::getOrder($this->order_id);
	    $this->itemid = JFactory::getApplication()->input->get('Itemid');
        $this->currency = MTHelper::getParams()->get('currency');
        $this->price = $deposit ? MTHelper::getDeposit($order->price, $order->assets_id) : $order->price;
        $this->callbackURL = JURi::base() . "index.php?option=com_modern_tours&task=payment.pay&method=$this->payment&Itemid=$this->itemid&order_id=$this->order_id";

        return $this->callbackURL;
    }

    public function setOrder()
    {
        $this->order    = MTHelper::getOrder( $this->order_id );
        $this->price    = number_format( $this->order->price, 2 );
    }

    public function displayForm($details)
    {
        if($this->thisProcessor($details)) {
            $this->callBack();
            $this->details = json_decode($details);
            ob_start();
            include("plugins/tourpayment/".$this->payment."/view.php");
            $html = ob_get_clean();

            return $html;
        }
    }

    protected function returnURL()
    {
        $return_id = $this->params->get('return');
        $base = substr(JURi::base(), 0, -1);

        if ($return_id) {
            $app = JFactory::getApplication();
            $menu = $app->getMenu();
            $link = $menu->getItem($return_id)->link;
            return $base . JRoute::_($link);
        } else {
            return $base;
        }
    }

    protected function setStatus($order_id, $status, $paidSum = 0)
    {
        $items = JFactory::getDbo()->setQuery('SELECT * FROM #__modern_tours_reservations WHERE id = "' . $order_id . '"')->loadObjectList();

        foreach ($items as $item) {
            $object = new stdClass();
	        $object->id = $item->id;
	        $object->paid_deposit = $paidSum;
            $object->status = $status;
            JFactory::getDbo()->updateObject('#__modern_tours_reservations', $object, 'id');
        }
    }

    public function save($error = '')
    {
        if($error) {
            error_log( $error );
            echo $error;
        } else {
	        $this->approve( $this->order );
	        ob_clean();
            echo 'OK';
            JFactory::getApplication()->close();
        }
    }

    public function approve( $order )
    {
        $model = JModelList::getInstance('Reservations', 'Modern_toursModel');
	    $model->assets_id = $order->assets_id;
	    $model->fields_id = MTHelper::getComponentParams('user_data_fields');
	    $model->prepareParams( $order );

        $userEmail = MTHelper::getUserEmail( $order, $order->assets_id );

	    $status = $order->deposit ? 'partially_paid' : 'paid';
	    $paidSum = $order->deposit ? MTHelper::getDeposit($order->price, $model->assets_id) : 0;

        $adminEmail = $model->parseEmail(MTHelper::getParams()->get('admin_email'));
	    $emailParams = $model->getEmailParameters();
        $userMessage = $model->setMessage($emailParams->{'user_' . $status . '_message'});
        $adminMessage = $model->setMessage($emailParams->{'admin_ ' . $status . '_message'});

	    error_log($userMessage);
	    error_log($adminMessage);

        if($userMessage) { $model->sendEmail($userMessage, $userEmail, $status); }
        if($adminMessage) { $model->sendEmail($adminMessage, $adminEmail, $status); }

        self::setStatus($this->order_id, $status, $paidSum);
    }

    protected function denie()
    {
        self::setStatus($this->order_id, 'cancel');
    }

    public function getAction( $details = array() )
    {
        if($details) {
            if ( $this->thisProcessor( $details ) ) {
                return $this->action;
            }
        } else {
            return array( 'payment' => $this->payment, 'action' => $this->action );
        }
    }

    public function thisProcessor($details)
    {
        $details = json_decode($details);
        $this->table = $details->table;
        $this->order_id = $details->order_id;
        $this->setOrder();

        return $this->payment === strtolower($details->payment);
    }

    public function setLogs()
    {
        $payment = JFactory::getApplication()->input->get('method');
        if(!$payment) {
            $payment = JFactory::getApplication()->input->get('payment');
        }

        if($payment == $this->payment) {
            ini_set('log_errors', true);
            ini_set('error_log', 'plugins/tourpayment/' . $this->payment . '/log.log');
        }
    }
}
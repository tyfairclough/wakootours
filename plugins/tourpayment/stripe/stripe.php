<?php
/**
 * @copyright    Copyright (c) 2015 paypal. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

if (!class_exists('MTPayment')) {
    require_once('components/com_modern_tours/helpers/mtpayment.php');
}
require 'lib/Stripe.php';

/**
 * tourpayment - Stripe Plugin
 *
 * @package        Joomla.Plugin
 * @subpakage    stripe.Stripe
 */
class plgtourpaymentStripe extends MTPayment
{

    public $payment = 'stripe';
    public $action = 'popup';

    /**
     * Constructor.
     *
     * @param    $subject
     * @param    array $config
     */
    function __construct(&$subject, $config = array())
    {
        // call parent constructor
        $this->action = 'popup';
        parent::__construct($subject, $config);
    }

    public function acceptPayment($details)
    {
        if($this->thisProcessor($details)) {

            if ($_GET) {

                $this->price =  str_replace(",", "", str_replace(".", "", $this->price));
                $this->key = ($this->params->get('sandbox') ? $this->params->get('test_secret') : $this->params->get('live_secret'));
                Stripe::setApiKey($this->key);

	            try {
                    if (!isset($_GET['stripeToken'])) {
                        error_log("The Stripe Token was not generated correctly");
                    }
                    Stripe_Charge::create(array(
                        "amount" => $this->price,
                        "currency" => MTHelper::getComponentParams('currency'),
                        "card" => $_GET['stripeToken'],
                        "description" => 'Description'
                    ));
                    $this->save('');
                } catch (Exception $e) {
                    echo 'Error occured, please contact administartor.';
                    error_log($e->getMessage());
                }
            } else {
                echo 'No data';
            }

            JFactory::getApplication()->close();
        }
    }

    public function keys($payment)
    {
        if($payment == $this->payment) {
            return ($this->params->get('sandbox') ? $this->params->get('test_publish') : $this->params->get('live_publish'));
        }
    }
}
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

/**
 * tourpayment - Paypal Plugin
 *
 * @package        Joomla.Plugin
 * @subpakage    paypal.Paypal
 */
class plgtourpaymentPaypal extends MTPayment
{

	public $payment = 'paypal';
	public $action = 'redirect';

	/**
	 * Constructor.
	 *
	 * @param    $subject
	 * @param    array $config
	 */
	function __construct(&$subject, $config = array())
	{
		$this->payment = 'paypal';
		parent::__construct($subject, $config);
	}

	public function displayForm($details)
	{
		if($this->thisProcessor($details)) {
			$this->callBack();
			$this->details = json_decode($details);
			$this->return = self::returnURL();
			ob_start();
			include("plugins/tourpayment/".$this->payment."/view.php");
			$html = ob_get_clean();

			return $html;
		}
	}

	public function returnURL()
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

	public function acceptPayment($details)
	{
		if($this->thisProcessor($details)) {

			$this->merchant = $this->params->get( 'merchant' );
			$this->order_id = JFactory::getApplication()->input->get('order_id');
			$this->order    = MTHelper::getOrder( $this->order_id );

			$this->save('');
			JFactory::getApplication()->close();
		}
	}

}
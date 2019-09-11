<?php
/**
 * @copyright      Copyright (c) 2015 system. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// Check to ensure this file is included in Joomla!

defined( '_JEXEC' ) or die( 'Restricted access' );
$url    = ( $this->params->get( 'sandbox' ) == 1 ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr' );
?>
<form id="payment" name="paymentForm" action="<?php echo $url; ?>" method="post">
    <input type="hidden" name="option" value="com_modern_tours"/>
    <input type="hidden" name="business" value="<?php echo $this->params->get( 'merchant' ); ?>"/>
    <input type="hidden" name="orderid" value="<?php echo $this->order_id; ?>"/>
    <input type="hidden" name="Itemid" value="<?php echo JFactory::getApplication()->input->get( 'Itemid' ); ?>"/>
    <input id="_xclick-element-0" name="cmd" type="hidden" value="_xclick">
    <input id="_xclick-element-4" name="amount" type="hidden" value="<?php echo $this->price; ?>">
    <input id="_xclick-element-2" name="currency_code" type="hidden" value="<?php echo $this->currency; ?>">
    <input id="_xclick-element-3" name="item_name" type="hidden" value="Reservation">
    <input id="_xclick-element-5" name="return" type="hidden" value="<?php echo $this->return; ?>">
    <input id="_xclick-element-6" name="notify_url" type="hidden" value="<?php echo $this->callbackURL; ?>">
    <input type="hidden" name="custom" value="<?php echo $this->order_id; ?>" id="_xclick-element-15">
    <input class="btn btn-primary" id="_xclick-element-8" type="submit" value="Pay<?php //echo JText::_('BB_PAY'); ?>">
</form>

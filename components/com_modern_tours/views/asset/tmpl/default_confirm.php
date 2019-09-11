<?php
/**
 * @version    CVS: 1.0.0
 * @package    com_modern_tours
 * @author      modernjoomla.com <support@modernjoomla.com>
 * @copyright  modernjoomla.com
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined( '_JEXEC' ) or die;

?>
<div id="confirm-booking">
    <div class="choose-payment-method">
        <h2 class="checkout-h2"><?php echo JText::_( 'CHOOSE_PAYMENT_METHOD' ); ?></h2>
        <div class="pay-text">
	        <?php echo JText::_( 'CHOOSE_PAYMENT_DESCR' ); ?>
        </div>
    </div>

    <select name="payment" id="payment">
        <option value=""><?php echo JText::_( 'SELECT_PAYMENT_METHOD' ); ?></option>
	    <?php foreach ($this->payments as $payment) { ?>
            <option value="<?php echo $payment; ?>"><?php echo JText::_(strtoupper($payment) . '_PAYMENT'); ?></option>
	    <?php } ?>
    </select>

    <div class="coupon-text">
        <div class="coupon-code-text">
	        <?php echo JText::_( 'HAVE_COUPON' ); ?>
        </div>
        <input id="coupon-input" type="text" name="coupon" placeholder="<?php echo JText::_( 'ENTER_COUPON_CODE' ); ?>"/>
        <div class="coupon_message"></div>
    </div>

    <?php if ( $this->deposit->deposit_booking ): ?>
        <div id="deposit" <?php if(!$this->deposit->deposit_booking_choose): ?>class="hide"<?php endif; ?>>
            <div class="coupon-code-text">
                <?php echo JText::_( 'PAY_DEPOSIT_TEXT' ); ?>

            </div>
            <div class="deposit-buttons">
                <div class="full-price deposit-button <?php if($this->deposit->deposit_booking_choose): ?>active<?php endif; ?>">
                    <i class="fa fa-check"></i> <?php echo JText::_( 'PAY_FULL_PRICE' ); ?>
                </div>
                <div class="deposit-price deposit-button <?php if(!$this->deposit->deposit_booking_choose): ?>active<?php endif; ?>">
                    <i class="fa fa-check"></i> <?php echo JText::sprintf( 'DEPOSIT_PRICE', $this->deposit->deposit_percentage ); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="btns">
        <div class="next-btn background-bg confirm-btn">
			<?php echo JText::_( 'CONFIRM_BOOKING' ); ?>
        </div>
        <div class="prev-btn" data-step="2">
			<?php echo JText::_( 'BACK_BUTTON' ); ?>
        </div>
    </div>
</div>

<div id="loading-block" style="display: none;">
    <div id="stripe-text" style="display: none;">
	    <?php echo JText::_( 'STRIPE_TEXT' ); ?>
    </div>
    <div class="wait-loading">
        <div class="loading-text"><?php echo JText::_( 'LOADING_TEXT' ); ?></div>
        <img class="loading-gif" src="<?php echo JURI::base() . 'media/com_modern_tours/img/ajax-loading.gif'; ?>"/>
    </div>
</div>
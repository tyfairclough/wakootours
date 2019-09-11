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
<div class="asset-info-box">
    <div class="checkout-box-line title-box">
        <img class="asset-img-checkout" width="60" height="50" src="<?php echo $this->item->cover; ?>"/>
        <h4 class="asset-name">
			<?php echo $this->item->title; ?>
        </h4>
    </div>
    <hr>
    <!-- <div class="review-line"></?php echo $this->item->reviewHTML; ?></div> -->
    <!-- <hr> -->
    <div class="checkout-box-line">
      <span uk-icon="icon: calendar; ratio: 1;" class="uk-icon">
  <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="calendar"><path d="M 2,3 2,17 18,17 18,3 2,3 Z M 17,16 3,16 3,8 17,8 17,16 Z M 17,7 3,7 3,4 17,4 17,7 Z"></path><rect width="1" height="3" x="6" y="2"></rect><rect width="1" height="3" x="13" y="2"></rect></svg>
</span>
        <span class="tour-length"></span>
    </div>
    <hr>
    <div class="checkout-box-line">
        <span class="singular-participant">
<span uk-icon="icon: user; ratio: 1;" class="uk-icon">
  <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="user"><circle fill="none" stroke="#000" stroke-width="1.1" cx="9.9" cy="6.4" r="4.4"></circle><path fill="none" stroke="#000" stroke-width="1.1" d="M1.5,19 C2.3,14.5 5.8,11.2 10,11.2 C14.2,11.2 17.7,14.6 18.5,19.2"></path></svg>
</span>
            <?php echo MTHelper::defineTranslate(1, 'PARTICIPANT'); ?>
        </span>
        <span class="plural-participant">

          <span uk-icon="icon: users; ratio: 1;" class="uk-icon">
  <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="users"><circle fill="none" stroke="#000" stroke-width="1.1" cx="7.7" cy="8.6" r="3.5"></circle><path fill="none" stroke="#000" stroke-width="1.1" d="M1,18.1 C1.7,14.6 4.4,12.1 7.6,12.1 C10.9,12.1 13.7,14.8 14.3,18.3"></path><path fill="none" stroke="#000" stroke-width="1.1" d="M11.4,4 C12.8,2.4 15.4,2.8 16.3,4.7 C17.2,6.6 15.7,8.9 13.6,8.9 C16.5,8.9 18.8,11.3 19.2,14.1"></path></svg>
</span>
            <?php echo MTHelper::defineTranslate(2, 'PARTICIPANT'); ?>
        </span>
    </div>
    <div class="checkout-box-line participant-line-adult">
        <span class="singular-adult">
            <?php echo MTHelper::defineTranslate(1, 'ADULT'); ?>
        </span>
        <span class="plural-adult">
            <?php echo MTHelper::defineTranslate(2, 'ADULT'); ?>
        </span>
        <small> ( <?php echo $this->item->currencySymbol;?> <span class="single-adult-sum"></span> / <?php echo JText::_( 'ADULT' ); ?> )</small>
        <span class="move-right"><?php echo $this->item->currencySymbol;?> <span class="total-adult-price"></span></span>
    </div>
    <div class="checkout-box-line participant-line-child">
        <span class="singular-child">
            <?php echo MTHelper::defineTranslate(1, 'CHILDREN'); ?>
        </span>
        <span class="plural-child">
            <?php echo MTHelper::defineTranslate(2, 'CHILDREN'); ?>
        </span>
        <small>( <?php echo $this->item->currencySymbol;?> <span class="single-child-sum"></span> / <?php echo JText::_( 'CHILD' ); ?> )</small>
        <span class="move-right"><?php echo $this->item->currencySymbol;?> <span class="total-child-price"></span></span>
    </div>
    <hr>
    <div class="checkout-box-line total-price">
		<?php echo JText::_( 'TOTAL' ); ?>
        <div class="move-right"><b><?php echo $this->item->currencySymbol;?> <span class="total-order-sum">0.00</span></b></div>
    </div>

    <div class="deposit-line">
		<?php echo JText::_( 'DEPOSIT_BOOKING' ); ?>
        <small>( <?php echo JText::sprintf( 'DEPOSIT_PERCENTAGE_TEXT', $this->deposit->deposit_percentage ); ?> )</small>
        <div class="move-right"><b><?php echo $this->item->currencySymbol;?> <span class="deposit-total-order-sum">0.00</span></b></div>
    </div>

    <div class="discount-line">
        <?php echo JText::_( 'COUPON_DISCOUNT_PRICE' ); ?>
        <div class="move-right"><b><?php echo $this->item->currencySymbol;?> <span class="discount-total-order-sum">0.00</span></b></div>
    </div>

    <!-- <hr> -->
    <!-- <div class="checkout-box-line">
        <small>
			</?php echo JText::_( 'HAVE_QUESTIONS' ); ?>
        </small>
    </div> -->
</div>

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
<div id="user-data">
    <h2 class="checkout-h2"><?php echo JText::_( 'FILL_PERSONAL_DATA' ); ?></h2>
    <div class="booking-review-text">
	    <?php echo JText::_( 'PAYMENT_TEXT_DESC' ); ?>
    </div>

    <div id="user-form">
        <div id="user-data-form"></div>
    </div>

    <div class="btns">
        <div class="next-btn background-bg" data-step="3" validate="#user-form">
			<?php echo JText::_( 'CONFIRM_YOUR_DATA' ); ?>
        </div>
        <div class="prev-btn" data-step="1">
			<?php echo JText::_( 'BACK_BUTTON' ); ?>
        </div>
    </div>
</div>
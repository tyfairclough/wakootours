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
<h2 class="checkout-h2"><?php echo JText::_( 'ADD_TRAVELERS_DATA' ); ?></h2>
<div class="booking-review-text">
	<?php echo JText::_( 'TRAVELLERS_VIEW_TEXT' ); ?>
</div>

<ul class="travellers-list">
    <div id="travellers-form">
        <div class="rendered-form"></div>
    </div>
</ul>

<div class="btns">
<div class="next-btn background-bg" data-step="2" validate="#travellers-form">
	<?php echo JText::_( 'CONFIRM_TRAVELLERS_DATA' ); ?>
</div>
</div>
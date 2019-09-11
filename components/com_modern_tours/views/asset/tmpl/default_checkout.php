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
<div class="checkout-modal">
    <div class="close">x</div>
    <div class="container checkout-container">
        <div class="asset-checkout">
            <div>
                <ul class="asset-steps">
                    <li class="active" step="1">1. <?php echo JText::_( 'ADD_TRAVELERS_DATA' ); ?></li>
                    <li step="2">
                        <svg viewBox="0 0 18 18" role="presentation" aria-hidden="true" focusable="false" style="height: 10px; width: 10px; fill: rgb(118, 118, 118);"><path d="m4.29 1.71a1 1 0 1 1 1.42-1.41l8 8a1 1 0 0 1 0 1.41l-8 8a1 1 0 1 1 -1.42-1.41l7.29-7.29z" fill-rule="evenodd"></path></svg>
                        2. <?php echo JText::_( 'FILL_USER_INFORMATION' ); ?></li>
                    <li step="3"><svg viewBox="0 0 18 18" role="presentation" aria-hidden="true" focusable="false" style="height: 10px; width: 10px; fill: rgb(118, 118, 118);"><path d="m4.29 1.71a1 1 0 1 1 1.42-1.41l8 8a1 1 0 0 1 0 1.41l-8 8a1 1 0 1 1 -1.42-1.41l7.29-7.29z" fill-rule="evenodd"></path></svg>
                        3. <?php echo JText::_( 'CONFIRM_AND_PAY' ); ?></li>
                </ul>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <?php echo $this->loadTemplate( 'checkout_box' ); ?>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-8 pad-30">
                    <form id="booking-data">
                        <div class="checkout-step step1">
                            <?php echo $this->loadTemplate( 'travellers_data' ); ?>
                        </div>
                        <div class="checkout-step step2">
                            <?php echo $this->loadTemplate( 'userdata' ); ?>
                        </div>
                        <div class="checkout-step step3">
                            <?php echo $this->loadTemplate( 'confirm' ); ?>
                        </div>
                        <input type="hidden" name="endtime" id="endtime"/>
                        <input type="hidden" name="adults" id="adults-form"/>
                        <input type="hidden" name="children" id="children-form"/>
                        <input type="hidden" name="assets_id" value="<?php echo $this->item->id; ?>" />
                        <input type="hidden" name="task" value="saveReservation" />
                        <input type="hidden" name="option" value="com_modern_tours" />
                        <input type="hidden" name="deposit" value="0" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="payment-data" class="secret"></div>
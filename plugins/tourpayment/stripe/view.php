<?php
/**
 * @copyright	Copyright (c) 2015 system. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die( 'Restricted access' );
$year = date('Y');
$html = '';
for($i=0; $i < 9; $i++) {
	$html .= '<option value="' . ($year+$i) . '">' . ($year+$i) . '</option>';
}
$card_number = $this->params->get('sandbox') ? '4242424242424242' : '';
$user_name = $this->params->get('sandbox') ? 'John Doe' : '';
?>
<form action="" method="POST" id="payment-form" class="form-horizontal stripe-fields">
	<div>
		<div>
			<div class="alert alert-danger" id="a_x200" style="display: none;"> <strong>Error!</strong> <span class="payment-errors"></span> </div>
			<fieldset>
				<div class="form-group">
					<div class="col-sm-12">
						<input type="text" name="cardholdername" value="<?php echo $user_name; ?>" maxlength="70" placeholder="<?php echo JText::_( 'CARD_HOLDER_NAME' ); ?>" class="card-holder-name form-control">
                        <input type="text" id="cardnumber" value="<?php echo $card_number; ?>" maxlength="19" placeholder="<?php echo JText::_( 'CARD_NUMBER' ); ?>" class="card-number form-control">

                        <div>
                        <select name="select2" data-stripe="exp-month"
                                class="card-expiry-month stripe-sensitive required form-control">
                            <option value=""><?php echo JText::_( 'SELECT_EXPIRY_MONTH' ); ?></option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                        </div>
                        <div>
                        <select name="select2" data-stripe="exp-year"
                                class="card-expiry-year stripe-sensitive required form-control">
                            <option value=""><?php echo JText::_( 'SELECT_EXPIRY_YEAR' ); ?></option>
                            <?php echo $html; ?>
                        </select>
                        </div>
                        <input type="text" id="cvv" placeholder="CVV" maxlength="4" class="card-cvc form-control">
                        <input type='hidden' id="token" name='stripeToken' value='' />
                        <input type='hidden' id="order_id" name="order_id" value="<?php echo $this->details->order_id; ?>" />
                        <button class="btn btn-success" id="stripe-pay" type="submit"><?php echo JText::_( 'PAY_NOW' ); ?></button>
                    </div>
				</div>
		</fieldset>
	</div>
</form>

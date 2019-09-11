<?php
/**
 * @version     CVS: 1.0.0
 * @package     com_modern_tours
 * @author      modernjoomla.com <support@modernjoomla.com>
 * @copyright   modernjoomla.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined( '_JEXEC' ) or die;
?>
<div class="col-lg-4 col-md-5 col-sm-12 right-tour-column">
    <div class="extra-sizer">
    <div class="ticket" id="elem" data-sticky_columm>
    <span class="tickets-title"><?php echo JText::_( 'BUY_TICKETS' ); ?></span>
        <div class="circle-block">
            <?php echo JText::_( 'PRICE_FORM' ); ?>
            <span class="circle-price">
            <?php echo $this->item->currencySymbol . ' ' . $this->item->lowestPrice; ?>
        </span>
        </div>
        <div class="form-ticket">
            <div id="select-date">
                <div id="open-calendar">
                    <i class="fa fa-calendar"></i>
                    <span class="placeholder">
                    <?php echo JText::_( 'SELECT_DATE_PLACEHOLDER' ); ?>
                </span>
                </div>
            </div>
            <div class="asset-timeslots"></div>

            <div class="participants-keeper">
                <div class="isDisabled" title="Please first select date and time"></div>
                <select name="adults" class="box-input " id="adults">
                    <option value="0"><?php echo JText::_( 'SELECT_ADULTS' ); ?></option>
                    <option value="1"><?php echo JText::_( '1_ADULT' ); ?></option>
                    <?php for($i=2;$i<301;$i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo JText::sprintf( 'ADULTS_COUNT', $i ); ?></option>
                    <?php endfor; ?>
                </select>

                <select name="children" class="box-input " id="children">
                    <option value="0"><?php echo JText::_( 'SELECT_CHILDREN' ); ?></option>
                    <option value="1"><?php echo JText::_( '1_CHILD' ); ?></option>
	                <?php for($i=2;$i<301;$i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo JText::sprintf( 'CHILDREN_COUNT', $i ); ?></option>
	                <?php endfor; ?>
                </select>
            </div>
            <div class="box-price-total">
                <?php echo JText::_( 'TOTAL_PRICE_BOX' ); ?>
                <?php echo $this->item->currencySymbol; ?><span id="total-price">0.00</span>
            </div>
            <button class="btn background-bg" id="book-now">
                <?php echo JText::_( 'BOOK_NOW' ); ?>
            </button>

        </div>
    </div>

	<?php echo $renderer = JFactory::getDocument()->loadRenderer('modules')->render('tour-sidebar',  array('style' => 'raw'), null);?>

    </div>
</div>

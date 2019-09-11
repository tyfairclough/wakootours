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


<div class="uk-card uk-card-default uk-card-large uk-card-body uk-margin-remove-first-child uk-width-xlarge@l uk-text-left" uk-parallax="x: -50,-50; y: -200,0; media: @m;" style="transform: translateX(-50px) translateY(-189px);">
  <div class="pricesFrom">
      <div><?php echo JText::_( 'PRICE_FORM' ); ?></div>
      <div><?php echo $this->item->currencySymbol . ' ' . $this->item->lowestPrice; ?></div>
  </div>
    <h2 class="el-title uk-h1 uk-margin-top uk-margin-remove-bottom"><?php echo JText::_( 'BUY_TICKETS' ); ?></h2>
    <div class="el-content uk-panel uk-margin-top"><div class="uk-divider-small uk-margin-medium-top uk-margin-medium-bottom"></div>



    <form class="form-ticket">
        <div id="select-date">
            <div id="open-calendar">
              <legend>
<span uk-icon="icon: calendar; ratio: 1;" class="uk-icon">
  <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="calendar"><path d="M 2,3 2,17 18,17 18,3 2,3 Z M 17,16 3,16 3,8 17,8 17,16 Z M 17,7 3,7 3,4 17,4 17,7 Z"></path><rect width="1" height="3" x="6" y="2"></rect><rect width="1" height="3" x="13" y="2"></rect></svg>
</span>
                <span class="placeholder"><?php echo JText::_( 'SELECT_DATE_PLACEHOLDER' ); ?></span></legend>
            </div>
        </div>
        <div class="asset-timeslots">
          <legend>
<span uk-icon="icon: clock; ratio: 1;" class="uk-icon">
  <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="clock"><circle fill="none" stroke="#000" stroke-width="1.1" cx="10" cy="10" r="9"></circle><rect x="9" y="4" width="1" height="7"></rect><path fill="none" stroke="#000" stroke-width="1.1" d="M13.018,14.197 L9.445,10.625"></path></svg>
</span>Choose a date to display available time slots</legend>
        </div>

        <div class="participants-keeper">
            <div class="isDisabled" title="Please first select date and time"></div>
            <legend>
<span uk-icon="icon: users; ratio: 1;" class="uk-icon">
  <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="users"><circle fill="none" stroke="#000" stroke-width="1.1" cx="7.7" cy="8.6" r="3.5"></circle><path fill="none" stroke="#000" stroke-width="1.1" d="M1,18.1 C1.7,14.6 4.4,12.1 7.6,12.1 C10.9,12.1 13.7,14.8 14.3,18.3"></path><path fill="none" stroke="#000" stroke-width="1.1" d="M11.4,4 C12.8,2.4 15.4,2.8 16.3,4.7 C17.2,6.6 15.7,8.9 13.6,8.9 C16.5,8.9 18.8,11.3 19.2,14.1"></path></svg>
</span>
              Select participants</legend>
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
        <div class="box-price-total tm-mask-default">
            <?php echo JText::_( 'TOTAL_PRICE_BOX' ); ?>
            <?php echo $this->item->currencySymbol; ?><span id="total-price">0.00</span>
        </div>
        <a class="btn" id="book-now">
            <?php echo JText::_( 'BOOK_NOW' ); ?>
        </a>

    </form>

    </div>
</div>

<!-- <div class="col-lg-4 col-md-5 col-sm-12 right-tour-column">
    <div class="extra-sizer">
    <div class="ticket" id="elem" data-sticky_columm>


    </div>

	</?php echo $renderer = JFactory::getDocument()->loadRenderer('modules')->render('tour-sidebar',  array('style' => 'raw'), null);?>

    </div>
</div> -->

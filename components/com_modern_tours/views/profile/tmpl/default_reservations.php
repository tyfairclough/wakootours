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
<h1 id="my-bookings"><?php echo JText::_( 'MY_BOOKINGS' ); ?></h1>
<div class="row">
	<?php foreach($this->reservations as $reservation): ?>
		<div class="asset-booking col-lg-12 <?php echo $reservation->expired; ?>">
			<div class="booking-inner">
				<div class="tour-image" style="background-image: url(<?php echo JURI::base() . '' . $reservation->cover; ?>);">
                    <div class="booking-status waiting">
						<?php echo JText::_($reservation->status); ?>
                    </div>
				</div>

                <div class="reservations-left-col">
                    <h2 class="tour-list-title">
                        <i class="map-marker"></i> <?php echo $reservation->title; ?>
                    </h2>

                    <div class="tour-start inline">
                        <i class="fa fa-clock-o"></i> <?php echo JText::_( 'TOUR_START_DATE' ); ?> <?php echo $reservation->date; ?><br>
                    </div>

                    <div class="booking-registered inline">
                        <i class="fa fa-calendar-o"></i> <?php echo JText::_( 'REGISTERED' ); ?> <?php echo $reservation->registered; ?><br>
                    </div>

                    <div class="booking-participants inline">
                        <i class="fa fa-user-o"></i> <?php echo JText::sprintf( 'PARTICIPANTS', 10 ); ?>
                    </div>
                </div>

                <div class="reservations-right-col">
                    <div class="booking-price">
                        <div class="total-paid-text">
                            <?php echo JText::_( 'TOTAL_PAID' ); ?>
                        </div>
                        <div class="total-price-list">
                            <?php echo MTHelper::addSymbol(MTHelper::getComponentParams('currency')) . $reservation->price; ?>
                        </div>
                    </div>
                    <div class="booking-actions">
                        <a href="<?php echo JURI::base() . 'index.php?option=com_modern_tours&task=getInvoice&id=' . $reservation->order_id; ?>" class="booking-action download-invoice btn btn-warning"><i class="fa fa-file-pdf-o"></i> <?php echo JText::_( 'DOWNLOAD_INVOICE' ); ?></a>
                        <div href="#" data-id="<?php echo $reservation->order_id; ?>" class="booking-action cancel-booking btn btn-danger"><i class="fa fa-ban"></i> <?php echo JText::_( 'CANCEL_BOOKING' ); ?></div>
                    </div>
                </div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
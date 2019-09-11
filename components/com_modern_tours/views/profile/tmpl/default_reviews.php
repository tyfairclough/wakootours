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
<h1 id="my-bookings"><?php echo JText::_( 'MY_REVIEWS' ); ?></h1>
<div class="row">
	<?php foreach($this->reviews as $review): ?>
		<div class="asset-booking col-lg-12 <?php echo $review->expired; ?>">
			<div class="booking-inner">
				<div class="tour-image" style="background-image: url(<?php echo JURI::base() . '' . $review->cover; ?>);"></div>
				<h2 class="tour-list-title">
					<i class="map-marker"></i> <?php echo $review->title; ?>
				</h2>
                <div class="tour-start inline">
	                <?php echo $review->description; ?>
                </div>


				<div class="booking-price">
                    <div data-id="<?php echo $review->review_id; ?>" class="review-action btn btn-danger">X</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
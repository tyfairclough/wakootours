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
<?php if($this->params->get('show_empty_reviews') && $this->params->get('show_reviews')): ?>
<hr>
<div id="tour-reviews-section">
    <h2 class="with-icon">
        <i class="fa fa-users fa-2x" aria-hidden="true"></i>
		<?php echo JText::_( 'REVIEWS_TITLE' ); ?>
    </h2>
    <div class="text-block">
		<?php if(count($this->item->reviews) >= 1) : ?>
            <ul class="excludes-list">
				<?php foreach ( $this->item->reviews as $review ): ?>
                    <div class="reviews-block" itemscope itemtype="https://schema.org/Review">
                        <div class="user-image <?php echo $review->emptyImageClass ?>" src="<?php echo $review->userImage ?>"></div>
                        <div class="reviews-user-info">
                            <span class="name" itemprop="author" itemscope itemtype="https://schema.org/Person"><?php echo $review->author ?></span>
                            <span class="location" itemprop="name"><?php echo $review->title; ?></span>
                            <?php echo $review->starRating; ?>
                            <span itemprop="reviewRating" style="display: none;" itemscope itemtype="https://schema.org/Rating">
                                <span itemprop="ratingValue"><?php echo $review->rating; ?></span>
                            </span>
                        </div>
                        <span class="reviews-text" itemprop="reviewBody"><?php echo $review->review; ?></span>
                        <span class="data"><?php echo $review->date; ?></span>
                    </div>
				<?php endforeach; ?>
            </ul>
		<?php else: ?>
            <div class="no-reviews"><?php echo JText::sprintf( $this->reviewText, $this->reviewLink ); ?></div>
		<?php endif; ?>
    </div>
</div>
<?php endif; ?>

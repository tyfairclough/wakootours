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
$relatedItems = json_decode( $this->item->relatedItems );
?>
<?php if ( $relatedItems ): ?>
    <hr>
    <div id="related-tours">
        <h2 class="with-icon">
            <i class="fa fa-heart-o fa-2x" aria-hidden="true"></i>
			<?php echo JText::_( 'RELATED_TOURS' ); ?>
        </h2>
        <div class="row">
            <div id="related-carousel" class="owl-carousel owl-theme">
                <?php foreach ( $relatedItems as $related ): ?>
                    <div class="related-tour">
                        <div class="post-block">
                            <a href="<?php echo JRoute::_('index.php?option=com_modern_tours&view=asset&alias=' . $related->alias); ?>"><span class="related-cover" style="background-image: url(<?php echo $related->cover; ?>);"></span></a>
                            <span class="post-title no-break"><a href="<?php echo JRoute::_('index.php?option=com_modern_tours&view=asset&alias=' . $related->alias); ?>"><?php echo $related->title; ?></a></span>
                            <span class="price"><?php echo JText::_( 'FROM' ); ?> <?php echo $related->lowestPrice . $related->currencySymbol; ?></span>
                            <span class="post-content"><?php echo $related->small_description; ?></span>
                            <div class="reviews">
                                <?php echo $related->reviewHTML; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

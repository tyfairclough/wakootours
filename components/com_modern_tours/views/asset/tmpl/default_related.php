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
<div class="uk-section-primary uk-section-overlap uk-section uk-section-large uk-flex">
    <div class="uk-position-relative uk-width-1-1 uk-flex uk-flex-middle">
    <div class="uk-width-1-1">
        <div class="uk-container">
            <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid="">
                <div class="uk-width-1-1@m uk-first-column">
                    <h2 class="uk-heading-large uk-heading-line uk-margin-large uk-margin-remove-top uk-text-center" uk-parallax="y: 50,-150; media: @m;" style="transform: translateY(-63px);">
                        <span><?php echo JText::_( 'RELATED_TOURS' ); ?></span>
                    </h2>
                </div>
            </div>
            <div class="uk-grid-margin uk-grid" uk-grid="">
              <?php foreach ( $relatedItems as $related ): ?>
                  <div class="uk-width-expand@m">
                    <div class="uk-margin uk-width-xlarge uk-margin-auto uk-text-center" uk-parallax="y: 100,-100; media: @m;" style="transform: translateY(45px);">
                        <div class="el-container tm-mask-default uk-inline">
                          <a href="<?php echo JRoute::_('index.php?option=com_modern_tours&view=asset&alias=' . $related->alias); ?>">
                            <div class="uk-inline-clip">
                                <img class="el-image" alt="" uk-img="" src="<?php echo $related->cover; ?>">
                                <div class="uk-position-center">
                                  <div class="uk-panel uk-padding uk-light uk-margin-remove-first-child">
                                    <!-- <div class="el-meta uk-h5 uk-margin-top uk-margin-remove-bottom">LocationOfTour</div> -->
                                    <h3 class="el-title uk-margin-small-top uk-margin-remove-bottom"><?php echo $related->title; ?></h3>
                                    <div class="uk-margin-top">
                                        <span class="el-link uk-button uk-button-text uk-button-large">View tour</span>
                                    </div>
                                    </div>
                                </div>
                            </div>
                          </a>
                        </div>
                    </div>
                  </div>
                  <?php endforeach; ?>
            </div>
            <!-- <div class="uk-margin-remove-bottom uk-grid-large uk-margin-large uk-grid uk-grid-stack" uk-grid="">
                <div class="uk-width-1-1@m uk-first-column">
                    <div class="uk-margin-remove-bottom uk-text-center">
                        <a class="el-content uk-button uk-button-text uk-button-large">See all our tours</a>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
    <div class="tm-section-title uk-position-center-right uk-position-medium uk-text-nowrap uk-visible@xl">
        <div class="tm-rotate-180"><?php echo JText::_( 'RELATED_TOURS' ); ?></div>
    </div>
    </div>
</div>
<?php endif; ?>

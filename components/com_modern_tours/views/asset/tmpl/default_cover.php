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


<div class="uk-section-default uk-section-overlap uk-light uk-position-relative">
        <!-- <div data-src="/templates/yootheme/cache/Aerial-View-of-Manuel-Antonio-National-Park-b29638dd.jpeg" data-srcset="/index.php?p=theme%2Fimage&amp;src=WyJpbWFnZXNcL3NhbXBsZWRhdGFcL0FlcmlhbC1WaWV3LW9mLU1hbnVlbC1BbnRvbmlvLU5hdGlvbmFsLVBhcmsuanBnIixbWyJkb1Jlc2l6ZSIsWzc2OSw1MTEsNzY5LDUxMV1dLFsiZG9Dcm9wIixbNzY4LDUxMSwwLDBdXV1d&amp;hash=d7f0d3efd2f54b5b760fe14ff0add406&amp;option=com_ajax&amp;style=9 768w, /templates/yootheme/cache/Aerial-View-of-Manuel-Antonio-National-Park-690e25e0.jpeg 1024w, /index.php?p=theme%2Fimage&amp;src=WyJpbWFnZXNcL3NhbXBsZWRhdGFcL0FlcmlhbC1WaWV3LW9mLU1hbnVlbC1BbnRvbmlvLU5hdGlvbmFsLVBhcmsuanBnIixbWyJkb1Jlc2l6ZSIsWzEzNjYsOTA4LDEzNjYsOTA4XV0sWyJkb0Nyb3AiLFsxMzY2LDkwOCwwLDBdXV1d&amp;hash=ffe10be4df2bba4316cab240e3564c26&amp;option=com_ajax&amp;style=9 1366w, /index.php?p=theme%2Fimage&amp;src=WyJpbWFnZXNcL3NhbXBsZWRhdGFcL0FlcmlhbC1WaWV3LW9mLU1hbnVlbC1BbnRvbmlvLU5hdGlvbmFsLVBhcmsuanBnIixbWyJkb1Jlc2l6ZSIsWzE2MDAsMTA2NCwxNjAwLDEwNjRdXSxbImRvQ3JvcCIsWzE2MDAsMTA2NCwwLDBdXV1d&amp;hash=9b357d0995ee54b1d5c1c883c2a94c7b&amp;option=com_ajax&amp;style=9 1600w, /templates/yootheme/cache/Aerial-View-of-Manuel-Antonio-National-Park-224076cb.jpeg 1920w, /templates/yootheme/cache/Aerial-View-of-Manuel-Antonio-National-Park-b29638dd.jpeg 2560w" data-sizes="(max-aspect-ratio: 2560/1702) 150vh" uk-img=""  style="background-image: url(&quot;http://staging.costaricanatureguidedtours.com/templates/yootheme/cache/Aerial-View-of-Manuel-Antonio-National-Park-b29638dd.jpeg&quot;); min-height: calc(100vh - 80px);"> -->
        <div class="uk-background-norepeat uk-background-cover uk-background-top-center uk-section uk-section-large uk-flex uk-flex-middle" uk-parallax="bgy: -200,0;media: @m" uk-height-viewport="offset-top: true;" style="background-image: url('<?php echo $this->item->cover; ?>'); min-height: calc(100vh - 80px);">
                <div class="uk-position-cover" style="background-color: rgba(0, 0, 0, 0); background-image: linear-gradient(to bottom,rgba(0,0,0,0) 0%,rgba(0,0,0,0.8) 100%); background-clip: padding-box"></div>
                        <div class="uk-width-1-1">
                            <div class="uk-container uk-position-relative">
                              <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid="">
                                <div class="uk-width-1-1@m uk-first-column">
                                    <div class="uk-h3"><span class="uk-text-white"><?php echo $this->item->category; ?> in <?php echo $this->item->location; ?></span></div>
                                    <h1 class="uk-heading-xlarge uk-margin-remove-top uk-width-xxlarge"><?php echo $this->item->title; ?></h1>
                                    <div class="uk-margin-remove-vertical uk-width-large uk-text-large">
																			<?php echo $this->item->small_description; ?>
                                    </div>
                                </div>
                              </div>
                            </div>
                        </div>
        </div>
</div>

<!--
<?php if ( $this->params->get('show_reviews_cover')) : ?>
    <div class="start">
        <?php echo $this->item->reviewHTML; ?>
    </div>
<?php endif; ?> -->


            <!-- <?php if($this->item->params->departure): ?><span><i class="fa fa-paper-plane-o fa-1x" aria-hidden="true"></i>
                <?php echo JText::_( 'DEPARTURE' ); ?><?php echo $this->item->params->departure; ?></span><?php endif; ?>
            <?php if($this->item->params->destination): ?><span><i class="fa fa-paper-plane fa-1x" aria-hidden="true"></i>
                <?php echo JText::_( 'DESTINATION' ); ?><?php echo $this->item->params->destination; ?></span><?php endif; ?>
            <?php if($this->item->params->availability): ?><span><i class="fa fa-calendar-check-o fa-1x" aria-hidden="true"></i>
                <?php echo JText::_( 'AVAILABIITY' ); ?><?php echo $this->item->params->availability; ?></span><?php endif; ?> -->




<?php if(isset( $this->item->params->itirenary ) || $this->item->imageFiles || $this->params->get('show_empty_reviews') || isset( $this->item->related[0])) : ?>

<?php endif; ?>

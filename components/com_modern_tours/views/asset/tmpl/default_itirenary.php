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
$day = 1;
?>

<?php if ( isset( $this->item->params->itirenary ) ): ?>
<div class="uk-section-secondary uk-section-overlap uk-section" id="tour-itirenary-section">
    <div class="uk-position-relative">
        <div class="uk-container">
            <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid="">
                <div class="uk-width-1-1@m uk-first-column">
                  <div class="uk-heading-small uk-width-xlarge@l uk-margin-auto uk-text-center"><?php echo JText::_( 'ITIRENARY' ); ?></div>
                	<div class="uk-width-xlarge uk-margin-auto uk-text-center">
                	    <div class="uk-child-width-1-1 uk-grid-small uk-grid-divider uk-grid-match uk-grid uk-grid-stack" uk-grid="">
                        <?php foreach ( $this->item->params->itirenary as $itirenary ): ?>
                    			<div class="uk-first-column">
                              <div class="el-item uk-panel uk-margin-remove-first-child">
                                <div class="el-meta uk-h4 uk-text-muted uk-margin-top uk-margin-remove-bottom"><?php echo $itirenary->day; ?></div>
                                <p class="uk-text-large uk-margin-remove-top uk-margin-remove-bottom"><?php echo $itirenary->desc; ?></p>
                              </div>
                          </div>
                          <?php $day ++; ?>
                          <?php endforeach; ?>
                	     </div>
                  </div>
               </div>
             </div>
           </div>
            <div class="tm-section-title uk-position-center-right uk-position-medium uk-text-nowrap uk-visible@xl">
              <div class="tm-rotate-180"><?php echo JText::_( 'ITIRENARY' ); ?></div>
            </div>
    </div>
</div>

<?php endif; ?>

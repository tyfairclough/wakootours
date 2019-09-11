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
$doc = JFactory::getDocument();
$doc->addStyleSheet( 'media/com_modern_tours/css/style.css' );
?>
<script>

    var LOADING_TEXT = '<?php echo JText::_( 'LOADING_TEXT' ); ?>';
    var SUCCESSFULL_REVIEW = '<?php echo JText::_( 'SUCCESSFULL_REVIEW' ); ?>';

    jQuery(document).ready(function() {
        jQuery('#review-form').submit(function(e) {
            e.preventDefault();
            jQuery('#submit-review').html(LOADING_TEXT).attr('disabled', 'disabled');
            var url = jQuery(this).attr('action');
            var data = jQuery(this).serialize();
            jQuery.ajax({
                type: "GET",
                data: data,
                url: url,
                success: function (response) {
                    jQuery('#review-form').html(SUCCESSFULL_REVIEW);
                }
            });
        });
    });
</script>
<div id="review-modal" class="col-sm-6">
    <h2 class="leave-review-title"><?php echo JText::sprintf( 'LEAVE_REVIEW', $this->item->title ); ?></h2>
    <form id="review-form" action="<?php echo JURI::base(); ?>index.php?option=com_modern_tours&task=saveReview">
        <div class="col-xs-12">
            <label for="review-title"><?php echo JText::_( 'REVIEW_TITLE' ); ?></label>
            <input name="title" id="review-title" type="text" />
        </div>
        <div class="col-xs-12">
            <label for="review"><?php echo JText::_( 'REVIEW_REVIEW' ); ?></label>
            <textarea name="review" id="review" rows="4"></textarea>
        </div>
        <div class="col-xs-12">
            <label for="review"><?php echo JText::_( 'RATING' ); ?></label>
            <select name="rating">
                <option value="5">5</option>
                <option value="4">4</option>
                <option value="3">3</option>
                <option value="2">2</option>
                <option value="1">1</option>
            </select>
        </div>
        <input type="hidden" name="assets_id" value="<?php echo $this->item->id; ?>"/>
        <button id="submit-review"><?php echo JText::_( 'SUBMIT_REVIEW' ); ?></button>
    </form>
</div>
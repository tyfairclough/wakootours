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
<div id="search-bar" style="width: 100%;">
    <form action="<?php echo JRoute::_('index.php?option=com_modern_tours&view=search'); ?>" method="get">
        <div class="row">
            <div class="col-md-4 col-sm-6 col-lg-2 search-bar-field">
                <label><?php echo JText::_( 'SEARCH_PHRASES' ); ?></label>
                <input name="search" type="text" placeholder="Enter your phrases..."/>
            </div>

            <div class="col-md-4 col-sm-6 col-lg-2 search-bar-field">
                <label><?php echo JText::_( 'LOCATION' ); ?></label>
				<?php echo MTHelper::getLocations(); ?>
            </div>

            <div class="col-md-4 col-sm-6 col-lg-2 search-bar-field">
                <label><?php echo JText::_( 'CATEGORY' ); ?></label>
				<?php echo MTHelper::getCategories(); ?>
            </div>

            <div class="col-md-4 col-sm-6 col-lg-2 search-bar-field">
                <label><?php echo JText::_( 'DURATION' ); ?></label>
				<?php echo MTHelper::getDurationField(); ?>
            </div>

            <div class="col-md-4 col-sm-6 col-lg-2 search-bar-field">
                <label><?php echo JText::_( 'RATING' ); ?></label>
	            <?php echo MTHelper::getRatingField(); ?>
            </div>

            <div class="col-md-4 col-sm-6 col-lg-2 search-bar-field">
                <label class="invisible"><?php echo JText::_( 'SEARCH' ); ?></label>
                <button id="search-btn"><?php echo JText::_( 'SUBMIT' ); ?></button>
            </div>
            <input type="hidden" name="option" value="com_modern_tours"/>
            <input type="hidden" name="view" value="search"/>
        </div>
    </form>
</div>

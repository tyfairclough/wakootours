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
<div id="review-modal" class="col-sm-6">
    <h2 class="leave-review-login"><?php echo JText::sprintf( 'LOGIN_FIRST' ); ?></h2>
	<?php echo $renderer = JFactory::getDocument()->loadRenderer('modules')->render('login',  array('style' => 'raw'), null);?>
</div>
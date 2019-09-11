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
include_once( JPATH_COMPONENT . '/helpers/modern_tours.php' );
$doc = JFactory::getDocument();
$doc->addStyleSheet( 'media/com_modern_tours/css/style.css' );
// $doc->addStyleSheet( 'media/com_modern_tours/css/bootstrap-min.css' );
?>
<?php if($this->params->get('show_page_heading')): ?>
    <div class="uk-section-default uk-section uk-padding-remove-vertical">
        <div class="uk-container">
            <h2><?php echo $this->params->get('page_title'); ?></h2>
        </div>
    </div>
<?php endif; ?>

<div class="uk-section-default uk-section uk-padding-remove-vertical">
  <div class="uk-container">
	<?php if ( !$this->items ) : ?>
        <h2><?php echo JText::_( 'NO_ASSETS_FOUND' ); ?></h2>
	<?php endif; ?>

	<?php if ( $this->items ) : ?>
		<?php foreach ( $this->items as $asset ): ?>
				<?php
                    $layout = new JLayoutFile($this->params->get('list_style', 'column'), JPATH_ROOT .'/components/com_modern_tours/layouts/tour');
                    echo $layout->render(array('asset' => $asset, 'params' => $this->params));
				?>
		<?php endforeach; ?>
	<?php endif; ?>
  </div>
</div>

<?php if(isset($this->pagination)): ?>
	<?php echo $this->pagination->getPagesLinks(); ?>
<?php endif; ?>

<div class="uk-section-default uk-section uk-padding-remove-vertical">
    <div class="uk-container">
<br />
    </div>
</div>

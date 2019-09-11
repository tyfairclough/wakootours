<?php
defined( '_JEXEC' ) or die;
$asset = $displayData['asset'];
$params = $displayData['params'];
?>
<div class="asset-single">
    <a class="asset-link" href="<?php echo JRoute::_('index.php?option=com_modern_tours&view=asset&alias=' . $asset->alias); ?>">
        <span class="asset-image-container h275" style="background-image: url(<?php echo $asset->cover; ?>);"></span>
    </a>
    <div class="asset-content">
		<?php if($params->get('show_title')): ?>
            <h3 class="asset-title no-break">
                <a class="asset-link" href="<?php echo JRoute::_('index.php?option=com_modern_tours&view=asset&alias=' . $asset->alias); ?>">
					<?php echo $asset->title; ?>
                </a>
            </h3>
		<?php endif ;?>

		<?php if($asset->params->length && $params->get('show_details')): ?>
            <div class="asset-info">
                <i class="fa fa-clock-o"></i> <?php echo $asset->params->length; ?>
                <span class="separator">/</span> <i class="fa fa-user-o fa-1x" aria-hidden="true"></i>
				<?php echo JText::_( 'Guests' ); ?>: <?php echo $asset->max_people; ?>
            </div>
		<?php endif; ?>

		<?php if($params->get('show_description')): ?>
            <div class="asset-description">
				<?php echo $asset->small_description; ?>
            </div>
		<?php endif ;?>

		<?php if($params->get('show_review')): ?>
            <div class="asset-review">
				<?php echo $asset->reviewHTML; ?>
            </div>
		<?php endif ;?>

		<?php if($params->get('show_price')): ?>
            <div class="asset-footer">
                <span><?php echo JText::_( 'PRICE' ); ?></span>
                <span class="float-right"><span class="asset-price-from"><?php echo JText::_( 'FROM' ); ?></span><span class="asset-footer-price"><?php echo $asset->lowestPrice . $asset->currencySymbol; ?></span></span>
            </div>
		<?php endif ;?>
    </div>
</div>

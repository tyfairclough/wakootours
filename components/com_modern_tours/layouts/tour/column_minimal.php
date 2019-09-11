<?php
defined( '_JEXEC' ) or die;
$asset = $displayData['asset'];
$params = $displayData['params'];
?>
<div class="column-minimal">
    <div class="behind-shadow light-shadow"></div>

    <span class="position-z">
    <a class="asset-link" href="<?php echo JRoute::_('index.php?option=com_modern_tours&view=asset&alias=' . $asset->alias); ?>">
        <span class="background-holder">
            <span class="asset-image-container h300" style="background-image: url(<?php echo $asset->cover; ?>);"></span>
	        <?php if($params->get('show_title')): ?>
                <h3 class="asset-title">
                    <?php echo $asset->title; ?>
                </h3>
	        <?php endif ;?>

	        <?php if($params->get('show_price')): ?>
                <span class="asset-footer">
                    <span><?php echo JText::_( 'PRICE' ); ?></span>
                    <span class="float-right"><span class="asset-price-from"><?php echo JText::_( 'FROM' ); ?></span><span class="asset-footer-price"><?php echo $asset->lowestPrice . $asset->currencySymbol; ?></span></span>
                </span>
	        <?php endif ;?>
        </span>
    </a>
    </span>
</div>
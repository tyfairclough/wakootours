<?php
defined( '_JEXEC' ) or die;
$asset = $displayData['asset'];
$params = $displayData['params'];
?>
<div class="asset-list-item align-<?php echo $params->get('align'); ?>">
    <div class="list-asset-item">
        <a class="single-asset-link" href="index.php?option=com_modern_tours&view=asset&alias=<?php echo $asset->alias; ?>">
            <span class="category-container list-container h50 w50" style="background-image: url(<?php echo $asset->cover; ?>);"></span>
        </a>
	    <?php if($params->get('show_title')): ?>
            <h4 class="asset-list-h4">
                <a class="single-asset-link" href="index.php?option=com_modern_tours&view=asset&alias=<?php echo $asset->alias; ?>">
				    <?php echo $asset->title; ?>
                </a>
            </h4>
	    <?php endif ;?>

	    <?php if($asset->params->length && $params->get('show_details')): ?>
            <span class="from-price-asset font-color">
                <?php echo JText::_( 'FROM' ); ?> <?php echo $asset->currencySymbol; ?> <?php echo $asset->lowestPrice; ?>
            </span>
	    <?php endif; ?>
    </div>
</div>
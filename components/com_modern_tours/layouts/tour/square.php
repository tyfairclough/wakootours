<?php
defined( '_JEXEC' ) or die;
$asset = $displayData['asset'];
$params = $displayData['params'];
?>
<div class="asset-single">
    <a class="asset-link" href="<?php echo JRoute::_('index.php?option=com_modern_tours&view=asset&alias=' . $asset->alias); ?>">
        <span class="asset-image-container h275" style="background-image: url(<?php echo $asset->cover; ?>);">
            <div class="light-shadow"></div>
            <div class="abs-holder">
                <div class="class-title no-break"><?php echo $asset->title; ?></div>
                <span class="asset-footer-price font-color"><?php echo $asset->lowestPrice . $asset->currencySymbol; ?></span>
            </div>
        </span>
    </a>
</div>

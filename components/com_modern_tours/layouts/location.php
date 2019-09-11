<?php
$location = $displayData;
$parameters = MTHelper::getParams();
?>
<div class="asset-sub <?php echo MTHelper::getCategoriesColumns(); ?>">
    <div class="category-container" style="background-image: url(<?php echo $location->image; ?>);">
        <div class="info-keeper">
            <a class="table-dummy" href="<?php echo JRoute::_('index.php?option=com_modern_tours&view=location&alias=' . $location->alias); ?>">
                <div class="behind-shadow dark"></div>
                <span class="cell-dummy">
                    <h2 class="cat-title"><i class="fa fa-map-marker"></i> <?php echo $location->title; ?></h2>
                    <span class="tours-count">
		                <?php echo JText::_( 'TOURS' ); ?> <?php echo $location->tours_count; ?>
                    </span>
                </span>
            </a>
        </div>
    </div>
</div>
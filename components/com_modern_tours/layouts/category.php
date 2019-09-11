<?php
$category = $displayData;
$parameters = MTHelper::getParams();
?>
<h3 class="el-title uk-h1 uk-heading-line uk-margin-top uk-margin-remove-bottom">Categories</h3>


<div class="asset-sub <?php echo MTHelper::getCategoriesColumns(); ?>">
    <div class="category-container" style="background-image: url(<?php echo $category->image; ?>);">
        <div class="info-keeper">
            <a class="table-dummy" href="<?php echo JRoute::_('index.php?option=com_modern_tours&view=category&alias=' . $category->alias); ?>">
                <div class="behind-shadow uk-overlay-primary"></div>
                <span class="cell-dummy">
                    <h3 class="el-title uk-h2 uk-heading-line uk-margin-top uk-margin-remove-bottom"><?php echo $category->title; ?></h3>
                    <span class="el-title uk-h4 uk-heading-line uk-margin-top uk-margin-remove-bottom">
		                <?php echo JText::_( 'TOURS' ); ?> <?php echo $category->tours_count; ?>
                    </span>
                </span>
            </a>
        </div>
    </div>
</div>

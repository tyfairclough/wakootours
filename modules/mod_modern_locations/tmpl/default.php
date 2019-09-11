<?php
defined( '_JEXEC' ) or die;
$id = $module->id;
$colClass = !$params->get('create_slider') ? $params->get('col_lg') . ' ' . $params->get('col_md') . ' ' . $params->get('col_sm') . ' ' . $params->get('col_xs') : '';
?>
<div class="container data-list <?php if(!$params->get('nav')): ?>no-nav<?php endif; ?>">

	<?php if($params->get('create_slider')): ?>
    <div class="slider-nav" id="nav<?php echo $id; ?>">
	    <?php if($module->showtitle): ?>
            <div class="inline">
                <h3 class="slider-title"><?php echo $module->title; ?></h3>
            </div>
        <?php endif; ?>
        <?php if($params->get('nav')): ?>
            <div class="inline slider_nav">
                <button class="next">←</button>
                <button class="am-prev">→</button>
            </div>
        <?php endif; ?>
    </div>
	<?php endif; ?>


    <div id="asset-list-<?php echo $id; ?>" class="owl-carousel owl-theme the-asset-items-list <?php echo !$params->get('create_slider') ? 'row' : ''; ?>">
        <?php foreach($assets as $asset): ?>
            <?php require dirname(__FILE__) . '/default_column_item.php'; ?>
        <?php endforeach; ?>
    </div>
</div>
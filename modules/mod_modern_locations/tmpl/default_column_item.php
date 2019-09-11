<?php
defined( '_JEXEC' ) or die;
$id = $module->id;
$colClass = !$params->get('create_slider') ? $params->get('col_lg') . ' ' . $params->get('col_md') . ' ' . $params->get('col_sm') . ' ' . $params->get('col_xs') : '';
?>
<div class="align-<?php echo $params->get('align'); ?> <?php echo $colClass; ?> <?php if($params->get('nav')): ?>pad15<?php else: ?>pad15-bot<?php endif; ?>">
    <div class="listing-container location-style1 h325">
        <a class="listing-link no-break" title="<?php echo $asset->title; ?>" href="<?php echo JRoute::_('index.php?option=com_modern_tours&view=location&alias=' . $asset->alias); ?>">
            <div class="light-shadow behind-shadow"></div>
            <span class="listing-title"><?php echo $asset->title; ?></span>
            <span class="listing-image" style="background-image: url(<?php echo $asset->image; ?>);"></span>
            <span class="light-shadow overlay-shadow"></span>
        </a>
    </div>
</div>
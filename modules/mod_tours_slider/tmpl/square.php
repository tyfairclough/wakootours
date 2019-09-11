<?php
defined( '_JEXEC' ) or die;
$parameters = json_decode($module->params);
?>
<div class="container tours-list <?php echo $parameters->moduleclass_sfx; ?>">
    <div class="slider-nav" id="nav<?php echo $module->id; ?>">
        <?php if($module->showtitle): ?>
            <div class="inline">
                <h3 class="slider-title"><?php echo $module->title; ?></h3>
            </div>
        <?php endif; ?>
        <div class="inline slider_nav">
            <button class="next">←</button>
            <button class="am-prev">→</button>
        </div>
    </div>

    <div id="asset-list-<?php echo $module->id; ?>" class="owl-carousel owl-theme the-asset-items-list">
        <?php foreach($assets as $asset): ?>
	        <?php
                $layout = new JLayoutFile('square', JPATH_ROOT .'/components/com_modern_tours/layouts/tour');
                echo $layout->render(array('asset' => $asset, 'params' => $params));
	        ?>
        <?php endforeach; ?>
    </div>
</div>

<script>
    jQuery(function ($) {
        $("#asset-list-<?php echo $module->id; ?>").owlCarousel({
            nav: 1,
            margin: <?php echo $params->get('margin'); ?>,
            loop: 0,
            autoplay: 1,
            dots: false,
            responsive:{
                0:{
                    items:1,
                    nav:true
                },
                600:{
                    items:2,
                    nav:false
                },
                801:{
                    items:<?php echo $params->get('items'); ?>
                }
            }
        });
    });

</script>
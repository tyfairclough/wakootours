<?php
defined( '_JEXEC' ) or die;
?>
<div class="container tours-list <?php echo $params->get('moduleclass_sfx'); ?>">
    <div id="asset-list-<?php echo $module->id; ?>" class="row">
        <?php foreach($assets as $asset): ?>
	        <?php
                $layout = new JLayoutFile('small_list', JPATH_ROOT .'/components/com_modern_tours/layouts/tour');
                echo $layout->render(array('asset' => $asset, 'params' => $params));
	        ?>
        <?php endforeach; ?>
    </div>
</div>
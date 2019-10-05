<?php
defined( '_JEXEC' ) or die;
?>

<div class="<?php echo $params->get('moduleclass_sfx'); ?>">
    <div class="uk-grid-margin uk-grid">
        <?php foreach($assets as $asset): ?>
	        <?php
                $layout = new JLayoutFile('small_list', JPATH_ROOT .'/components/com_modern_tours/layouts/tour');
                echo $layout->render(array('asset' => $asset, 'params' => $params));
	        ?>
        <?php endforeach; ?>
    </div>
</div>

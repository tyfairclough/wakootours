<?php
defined( '_JEXEC' ) or die;
$colClass = $params->get('col_lg') . ' ' . $params->get('col_md') . ' ' . $params->get('col_sm') . ' ' . $params->get('col_xs');
?>
<div class="container tours-list <?php echo $params->get('moduleclass_sfx'); ?>">
    <div id="asset-list-<?php echo $module->id; ?>" class="row">
        <?php foreach($assets as $asset): ?>
            <div class="<?php echo $colClass; ?>">
                <?php
                    $layout = new JLayoutFile($params->get('template', 'column'), JPATH_ROOT .'/components/com_modern_tours/layouts/tour');
                    echo $layout->render(array('asset' => $asset, 'params' => $params));
                ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
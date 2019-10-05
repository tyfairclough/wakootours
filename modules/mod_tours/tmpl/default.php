<?php
defined( '_JEXEC' ) or die;
$colClass = $params->get('col_lg') . ' ' . $params->get('col_md') . ' ' . $params->get('col_sm') . ' ' . $params->get('col_xs');
?>

<div class="uk-section-default uk-section">
  <div class="uk-container">
    <div class="uk-grid-margin uk-grid" uk-grid="">
          <?php foreach($assets as $asset): ?>
                  <?php
                      $layout = new JLayoutFile($params->get('template', 'column'), JPATH_ROOT .'/components/com_modern_tours/layouts/tour');
                      echo $layout->render(array('asset' => $asset, 'params' => $params));
                  ?>
          <?php endforeach; ?>
    </div>
  </div>
</div>

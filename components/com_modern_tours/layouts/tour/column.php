<?php
defined( '_JEXEC' ) or die;
$asset = $displayData['asset'];
$params = $displayData['params'];
?>

    <div class="uk-width-expand@m">
    <a class="uk-card uk-card-secondary uk-card-small uk-card-hover uk-display-block uk-link-toggle uk-margin" href="<?php echo JRoute::_('index.php?option=com_modern_tours&view=asset&alias=' . $asset->alias); ?>">
      <div class="uk-card-media-top"><img class="el-image" alt="" src="<?php echo $asset->cover; ?>"></div>
      <div class="uk-card-body uk-margin-remove-first-child">
        <?php if($params->get('show_title')): ?>
        <h3 class="el-title uk-h2 uk-heading-divider uk-margin-top uk-margin-remove-bottom"><?php echo $asset->title; ?></h3>
        <?php endif; ?>
	      <?php if($asset->params->length && $params->get('show_details')): ?>
          <div class="asset-info">
              <i class="fa fa-clock-o"></i> <?php echo $asset->params->length; ?>
              <span class="separator">/</span> <i class="fa fa-user-o fa-1x" aria-hidden="true"></i>
              <?php echo JText::_( 'Guests' ); ?>: <?php echo $asset->max_people; ?>
          </div>
        <?php endif; ?>
        <?php if($params->get('show_description')): ?>
          <div class="el-content uk-panel uk-margin-top"><?php echo $asset->small_description; ?></div>
        <?php endif; ?>
        <?php if($params->get('show_price')): ?>
          <div class="el-meta uk-h3 uk-margin-top uk-margin-remove-bottom"><?php echo JText::_( 'FROM' ); ?> <?php echo $asset->currencySymbol . $asset->lowestPrice; ?></div>
        <?php endif; ?>
      </div>
    </a>
    </div>

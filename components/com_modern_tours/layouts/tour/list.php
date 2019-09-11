<?php
$asset = $displayData['asset'];
$params = $displayData['params'];
?>
<div class="uk-grid-collapse uk-margin uk-grid" uk-grid="" uk-height-match="target: .uk-card; row: false">
  <div class="uk-grid-item-match uk-width-expand@l uk-first-column">
          <div class="uk-flex">
                    <div class="uk-tile uk-width-1-1 uk-background-norepeat uk-background-cover uk-background-center-center" data-src="<?php echo $asset->cover; ?>"  data-sizes="(max-aspect-ratio: 1300/910) 143vh" uk-img="" style="background-image: url(<?php echo $asset->cover; ?>)"></div>
          </div>
  </div>
    <div class="uk-grid-item-match uk-width-2-3@l">
      <div class="uk-tile-muted uk-tile-small uk-tile">
        <h2 class=" uk-margin-remove-bottom"><a href="<?php echo JRoute::_('index.php?option=com_modern_tours&view=asset&alias=' . $asset->alias); ?>"><?php echo $asset->title; ?></a></h2>
        <div class="">
          <div class="uk-child-width-1-1 uk-child-width-1-2@m uk-grid-divider uk-grid-match uk-grid" uk-grid="">
            <div class="uk-first-column">
              <div class="el-item uk-panel uk-margin-remove-first-child">
                  <div class="el-meta uk-h3 uk-margin-top uk-margin-remove-bottom">In <?php echo $asset->location; ?></div>
                  <div class="el-content uk-panel uk-margin-top"><?php echo $asset->small_description; ?></div>
                  <?php if($asset->max_people && $params->get('show_max_people')): ?>
                    <div class="uk-margin-top">
                      <span uk-icon="icon: users; ratio: 1;" class="uk-icon"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="users"><circle fill="none" stroke="#000" stroke-width="1.1" cx="7.7" cy="8.6" r="3.5"></circle><path fill="none" stroke="#000" stroke-width="1.1" d="M1,18.1 C1.7,14.6 4.4,12.1 7.6,12.1 C10.9,12.1 13.7,14.8 14.3,18.3"></path><path fill="none" stroke="#000" stroke-width="1.1" d="M11.4,4 C12.8,2.4 15.4,2.8 16.3,4.7 C17.2,6.6 15.7,8.9 13.6,8.9 C16.5,8.9 18.8,11.3 19.2,14.1"></path></svg></span>
                      <?php echo JText::_( 'MAX_PEOPLE' ); ?><?php echo $asset->max_people; ?>
                    </div>
                  <?php // endif; ?>
                  <?php // if($asset->params->availability && $params->get('show_availability')): ?>
                    <!--<div class="uk-margin-top"><?php // echo $asset->params->availability; ?></div>
                    -->
                  <?php endif; ?>
                  <?php // if($asset->params->departure && $params->get('show_departure')): ?>
                    <!-- <div class="uk-margin-top">
                      <span uk-icon="icon: location; ratio: 1;" class="uk-icon"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="location"><path fill="none" stroke="#000" stroke-width="1.01" d="M10,0.5 C6.41,0.5 3.5,3.39 3.5,6.98 C3.5,11.83 10,19 10,19 C10,19 16.5,11.83 16.5,6.98 C16.5,3.39 13.59,0.5 10,0.5 L10,0.5 Z"></path><circle fill="none" stroke="#000" cx="10" cy="6.8" r="2.3"></circle></svg></span>
                    -->
                      <?php // echo JText::_( 'DEPARTURE' ); ?> <?php // echo $asset->params->departure; ?>
                    <!-- </div>
                    -->
                  <?php // endif; ?>
                  <?php // if($asset->params->destination && $params->get('show_arrival')): ?>
                    <!-- <div class="uk-margin-top">
                      <span uk-icon="icon: location; ratio: 1;" class="uk-icon"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="location"><path fill="none" stroke="#000" stroke-width="1.01" d="M10,0.5 C6.41,0.5 3.5,3.39 3.5,6.98 C3.5,11.83 10,19 10,19 C10,19 16.5,11.83 16.5,6.98 C16.5,3.39 13.59,0.5 10,0.5 L10,0.5 Z"></path><circle fill="none" stroke="#000" cx="10" cy="6.8" r="2.3"></circle></svg></span>
                    -->
                      <?php // echo JText::_( 'DESTINATION' ); ?>
                      <?php // echo $asset->params->destination; ?>
                    <!-- </div>
                    -->
                  <?php // endif; ?>
                  <?php if($asset->params->length && $params->get('show_length')):?>
                    <div class="uk-margin-top">
                      <span uk-icon="icon: future; ratio: 1;" class="uk-icon"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="future"><polyline points="19 2 18 2 18 6 14 6 14 7 19 7 19 2"></polyline><path fill="none" stroke="#000" stroke-width="1.1" d="M18,6.548 C16.709,3.29 13.354,1 9.6,1 C4.6,1 0.6,5 0.6,10 C0.6,15 4.6,19 9.6,19 C14.6,19 18.6,15 18.6,10"></path><rect x="9" y="4" width="1" height="7"></rect><path d="M13.018,14.197 L9.445,10.625" fill="none" stroke="#000" stroke-width="1.1"></path></svg></span>
                      <?php echo JText::_( 'DURATION' ); ?>
                      <?php echo $asset->params->length; ?>
                    </div>
                  <?php endif; ?>
              </div>
            </div>
          <div>
            <div class="el-item uk-panel uk-margin-remove-first-child">
                  <h2 class="el-title uk-heading-small uk-margin-top uk-margin-remove-bottom"><?php /* if($params->get('show_description')):?><div class="asset-review"><?php echo $asset->reviewHTML; ?></div><?php endif; */ ?></h2>
                  <div class="el-meta uk-h3 uk-margin-top uk-margin-remove-bottom"><span class="from-word"><?php echo JText::_( 'FROM' ); ?></span>
                  <?php echo $asset->currencySymbol; ?><?php echo $asset->lowestPrice; ?></div>
                  <div class="uk-margin-top"><a href="<?php echo JRoute::_('index.php?option=com_modern_tours&view=asset&alias=' . $asset->alias); ?>" class="el-content uk-width-1-1 uk-button uk-button-secondary uk-button-large"><?php echo JText::_( 'VIEW_DETAILS' ); ?></a></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
$asset = $displayData['asset'];
$params = $displayData['params'];
?>
<div class="asset-item">
    <div class="col-sm-5 no-float no-pad">
        <a href="<?php echo JRoute::_('index.php?option=com_modern_tours&view=asset&alias=' . $asset->alias); ?>">
            <span class="category-container" style="background-image: url(<?php echo $asset->cover; ?>);"></span>
        </a>
    </div>
    <div class="col-sm-4 no-float pad-15">
        <h3 class="list-item-title">
            <a class="single-asset-link"
               href="<?php echo JRoute::_('index.php?option=com_modern_tours&view=asset&alias=' . $asset->alias); ?>">
				<?php echo $asset->title; ?>
            </a>
        </h3>
        <div class="small-desc">
			<?php echo $asset->small_description; ?>
        </div>

        <div class="additional-fields">
	        <?php if($asset->max_people && $params->get('show_max_people')): ?><span><i class="fa fa-user-o fa-1x" aria-hidden="true"></i>
		        <?php echo JText::_( 'MAX_PEOPLE' ); ?><?php echo $asset->max_people; ?></span><?php endif; ?>
	        <?php if($asset->params->availability && $params->get('show_availability')): ?><span><i class="fa fa-calendar-check-o fa-1x" aria-hidden="true"></i>
		        <?php echo $asset->params->availability; ?></span><?php endif; ?>
	        <?php if($asset->params->departure && $params->get('show_departure')): ?><span><i class="fa fa-paper-plane-o fa-1x" aria-hidden="true"></i>
		        <?php echo JText::_( 'DEPARTURE' ); ?><?php echo $asset->params->departure; ?></span><?php endif; ?>
	        <?php if($asset->params->destination && $params->get('show_arrival')): ?><span><i class="fa fa-paper-plane fa-1x" aria-hidden="true"></i>
		        <?php echo JText::_( 'DESTINATION' ); ?><?php echo $asset->params->destination; ?></span><?php endif; ?>
        </div>
    </div>
    <div class="col-sm-3 no-float h-100 pad-15 asset-last-col">

        <?php if($params->get('show_description')):?>
            <div class="asset-review">
                <?php echo $asset->reviewHTML; ?>
            </div>
        <?php endif; ?>

        <div class="lowest-price">
            <span class="from-word"><?php echo JText::_( 'FROM' ); ?></span>
            <div class="asset-lowest">
				<?php echo $asset->currencySymbol; ?><?php echo $asset->lowestPrice; ?>
            </div>
        </div>

	    <?php if($asset->params->length && $params->get('show_length')):?>
            <div class="info-fields">
                <i class="fa fa-calendar-check-o fa-1x" aria-hidden="true"></i> <?php echo $asset->params->length; ?>
            </div>
	    <?php endif; ?>

        <div class="btn-keeper">
            <a class="btn full-btn default-color" href="<?php echo JRoute::_('index.php?option=com_modern_tours&view=asset&alias=' . $asset->alias); ?>">
				<?php echo JText::_( 'VIEW_DETAILS' ); ?>
            </a>
        </div>
    </div>
</div>



<div class="uk-grid-collapse uk-grid" uk-grid="" uk-height-match="target: .uk-card; row: false">
    <div class="uk-width-expand@l uk-first-column">
      <div data-id="page#23" class="uk-margin">
        <a href="<?php echo JRoute::_('index.php?option=com_modern_tours&view=asset&alias=' . $asset->alias); ?>">
          <img class="el-image" alt="" data-src="/index.php?p=theme%2Fimage&amp;src=WyJpbWFnZXNcL3BvcnRyYWl0XC9wb3J0cmFpdF9waG90b3NfMDAwM182NjUyOTE3NV8yMDM0NTUzOTk2NjgyMzM2XzEyMjY1MjY0OTgwNTIxMTIzODRfbi5qcGciLFtbImRvUmVzaXplIixbNTMwLDcxMCw1MzAsNzEwXV0sWyJ0eXBlIixbIndlYnAiLCI4NSJdXV1d&amp;hash=2c7cf114e09aa884db360e22fb23eb71&amp;option=com_ajax&amp;style=9" data-srcset="/index.php?p=theme%2Fimage&amp;src=WyJpbWFnZXNcL3BvcnRyYWl0XC9wb3J0cmFpdF9waG90b3NfMDAwM182NjUyOTE3NV8yMDM0NTUzOTk2NjgyMzM2XzEyMjY1MjY0OTgwNTIxMTIzODRfbi5qcGciLFtbImRvUmVzaXplIixbNTMwLDcxMCw1MzAsNzEwXV0sWyJ0eXBlIixbIndlYnAiLCI4NSJdXV1d&amp;hash=2c7cf114e09aa884db360e22fb23eb71&amp;option=com_ajax&amp;style=9 530w" data-sizes="(min-width: 530px) 530px" data-width="530" data-height="710" uk-img="" sizes="(min-width: 530px) 530px" srcset="/index.php?p=theme%2Fimage&amp;src=WyJpbWFnZXNcL3BvcnRyYWl0XC9wb3J0cmFpdF9waG90b3NfMDAwM182NjUyOTE3NV8yMDM0NTUzOTk2NjgyMzM2XzEyMjY1MjY0OTgwNTIxMTIzODRfbi5qcGciLFtbImRvUmVzaXplIixbNTMwLDcxMCw1MzAsNzEwXV0sWyJ0eXBlIixbIndlYnAiLCI4NSJdXV1d&amp;hash=2c7cf114e09aa884db360e22fb23eb71&amp;option=com_ajax&amp;style=9 530w" src="http://staging.costaricanatureguidedtours.com/index.php?p=theme%2Fimage&amp;src=WyJpbWFnZXNcL3BvcnRyYWl0XC9wb3J0cmFpdF9waG90b3NfMDAwM182NjUyOTE3NV8yMDM0NTUzOTk2NjgyMzM2XzEyMjY1MjY0OTgwNTIxMTIzODRfbi5qcGciLFtbImRvUmVzaXplIixbNTMwLDcxMCw1MzAsNzEwXV0sWyJ0eXBlIixbIndlYnAiLCI4NSJdXV1d&amp;hash=2c7cf114e09aa884db360e22fb23eb71&amp;option=com_ajax&amp;style=9">
        </a>
      </div>
    </div>

    <div class="uk-grid-item-match uk-width-2-3@l">
      <div class="uk-tile-muted uk-tile">
        <div data-id="page#25" class="uk-margin">
          <div class="uk-child-width-1-1 uk-child-width-1-2@m uk-grid-collapse uk-grid-match uk-grid" uk-grid="">
            <div class="uk-first-column">
              <div class="el-item uk-panel uk-margin-remove-first-child">
                  <h2 class="el-title uk-heading-small uk-margin-top uk-margin-remove-bottom">Tour headline</h2>
                  <div class="el-meta uk-h3 uk-margin-top uk-margin-remove-bottom">location</div>
                  <div class="el-content uk-panel uk-margin-top">  are you ready to have a great time. are you ready to have a great time. are you ready to have a great time.</div>
                  <div class="uk-margin-top"><a href="#" uk-scroll="" class="el-link uk-button uk-button-text uk-button-large">View tour</a></div>
              </div>
            </div>
          <div>
            <div class="el-item uk-panel uk-margin-remove-first-child">
                  <h2 class="el-title uk-heading-small uk-margin-top uk-margin-remove-bottom">Reviews</h2>
                  <div class="el-meta uk-h3 uk-margin-top uk-margin-remove-bottom">Price</div>
                  <div class="el-content uk-panel uk-margin-top">£59</div>
                  <div class="uk-margin-top"><a href="<?php echo JRoute::_('index.php?option=com_modern_tours&view=asset&alias=' . $asset->alias); ?>" uk-scroll="" class="el-link uk-button uk-button-text uk-button-large">View tour</a></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

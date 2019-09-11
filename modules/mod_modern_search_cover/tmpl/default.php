<?php
defined( '_JEXEC' ) or die;
MTHelper::loadLanguage();
?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
<script>
    jQuery(document).ready(function () {
        var rCalendar = jQuery('#range-calendar');
        rCalendar.daterangepicker({
            locale: {
                format: 'MMM DD'
            }
        });
        rCalendar.on('apply.daterangepicker', function(ev, picker) {
            var start = picker.startDate.format('YYYY-MM-DD');
            var end = picker.endDate.format('YYYY-MM-DD');
            jQuery('[name="start"]').val(start);
            jQuery('[name="end"]').val(end);
        });
    });
</script>
<div id="search-cover" class="search-items-<?php echo SearchCover::countFields($params); ?>">
      <form action="<?php echo JRoute::_( 'index.php?option=com_modern_tours&view=search' ); ?>"
            method="get">

        <?php /* if($params->get('search')): ?>
              <div class="search-field">
                  <input name="search" class="form-control" type="text" placeholder="Enter search phrase...">
              </div>
        <?php endif; */ ?>

        <?php if($params->get('calendar')): ?>
              <div class="search-field">
                  <input name="dates" class="input-block" type="text" id="range-calendar" placeholder="Select time interval">
              </div>
        <?php endif; ?>

        <?php if($params->get('categories')): ?>
              <div class="search-field">
            <?php echo MTHelper::getCategories(); ?>
              </div>
        <?php endif; ?>

        <?php if($params->get('locations')): ?>
              <div class="search-field">
                  <!-- <i class="fa fa-map-o"> </i> -->
                  <?php echo MTHelper::getLocations(); ?>
              </div>
        <?php endif; ?>

          <div class="search-field">
              <button class="el-content uk-button uk-button-primary">
                <span class="uk-text-middle"><?php echo JText::_( 'SEARCH' ); ?></span>
                <span uk-icon="arrow-right" class="uk-icon">
                  <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="arrow-right"><polyline fill="none" stroke="#000" points="10 5 15 9.5 10 14"></polyline><line fill="none" stroke="#000" x1="4" y1="9.5" x2="15" y2="9.5"></line></svg>
                </span>
              </button>
          </div>
          <input type="hidden" name="start">
          <input type="hidden" name="end">
      </form>
</div>

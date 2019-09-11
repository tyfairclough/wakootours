<?php
/**
 * @version    CVS: 1.0.0
 * @package    com_modern_tours
 * @author      modernjoomla.com <support@modernjoomla.com>
 * @copyright  modernjoomla.com
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined( '_JEXEC' ) or die;
$day = 1;
?>

<?php
$imageArray = $this->item->imageFiles;
$imageArrayNumerical = array_values($imageArray);
// print_r(array_values($numerical));
?>
<!-- map -->
<?php if($this->item->params->destination): ?>
  <?php /* echo JText::_( 'DESTINATION' ); */ ?>
  <div class="uk-section-muted uk-section">
    <div class="uk-container">
      <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid="">
        <div class="uk-width-1-1@m uk-first-column">
          <h3 class="uk-margin-remove-bottom uk-text-center">Map of <?php echo $this->item->location; ?></h3>
          <h1 class="uk-heading-medium uk-heading-line uk-margin-remove-top uk-text-center"><span>X marks the spot</span></h1>
        </div>
      </div>
      <div class="uk-grid-large uk-grid-margin-large uk-grid" uk-grid="" uk-height-match="target: .uk-card; row: false">
        <div class="uk-width-2-3@m uk-first-column">
          <div id="map" class="uk-position-relative uk-position-z-index" style="height:300px;">
          </div>
  <!-- https://codepen.io/engza/pen/Btedl -->
  <!-- AIzaSyDNekG1soXsvWW-ka2PCMMd2BLFDj98brA -->
  <!-- script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDNekG1soXsvWW-ka2PCMMd2BLFDj98brA&callback=initMap" async defer></script -->

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css"
    integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
    crossorigin=""/>
  <!-- Make sure you put this AFTER Leaflet's CSS -->
  <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"
    integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og=="
    crossorigin=""></script>
  <script>

  var mymap = L.map('map').setView([<?php echo $this->item->params->destination; ?>], 13);



L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoidHlmYWlyY2xvdWdoIiwiYSI6Ilg0d2JCdWcifQ.Os6oIt5VzkHGwTWXxOZJag', {
  maxZoom: 18,
  attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
    '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
    'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
  id: 'mapbox.streets'
}).addTo(mymap);

L.marker([<?php echo $this->item->params->destination; ?>]).addTo(mymap)

  </script>
<!--script>

  var jToursDestination = '<?php // echo $this->item->params->destination; ?>';
  var geoSplit = jToursDestination.split(", ");
  var geoLat = Number(geoSplit[0]);
  var geoLon = Number(geoSplit[1]);
  var geoLocation = jToursDestination.replace(/[ ,]+/g, ",");
  console.log("location is: " + geoLat);


  var map;
  function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
      center: {lat: geoLat, lng: geoLon},
      zoom: 14
    });
  }
</script -->>
        </div>
        <div class="uk-width-expand@m">
          <h1>Getting here</h1>
          <div class="uk-margin">Whether you are using our transfer service or arriving at the departure destination yourself it's important to be present at the agreed location 15 minutes before the tour officially sets off. During this time we go through the pre-tour house keeping and make sure we have everything we need to set off on-time.</div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php if ( isset( $this->item->params->includes ) || isset( $this->item->params->excludes ) ): ?>
    <div class="uk-section-muted uk-section uk-section-large">
        <div class="uk-position-relative">
            <div class="uk-container uk-container-expand-right">
                <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid="">
                    <div class="uk-width-1-1@m uk-first-column">
                        <h2 class="uk-heading-medium">What do I get?</h2>
                    </div>
                </div>
                <div class="uk-grid-large uk-grid-margin-large uk-grid" uk-grid="">
                  <?php if ( $this->item->params->includes ): ?>
                    <div class="uk-width-expand@m uk-first-column">
                        <h3><?php echo JText::_( 'INCLUDES' ); ?></h3>
                        <ul class="uk-list uk-list-striped">
                          <?php foreach ( $this->item->params->includes as $include ): ?>
                            <li class="el-item">
                                <div class="uk-grid-small uk-child-width-expand uk-flex-nowrap uk-flex-middle uk-grid" uk-grid="">
                                    <div class="uk-width-auto uk-first-column">
                                        <span class="el-image uk-icon" uk-icon="icon: check;">
                                            <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="check"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg>
                                        </span>
                                    </div>
                                    <div>
                                        <div class="el-content uk-panel"><?php echo $include; ?></div>
                                    </div>
                                </div>
                            </li>
                          <?php endforeach; ?>
                        </ul>
                    </div>
                  <?php endif; ?>
                  <?php if ( $this->item->params->excludes ): ?>
                    <div class="uk-width-expand@m">
                        <h3><?php echo JText::_( 'EXCLUDES' ); ?></h3>
                        <ul class="uk-list uk-list-striped">
                          <?php foreach ( $this->item->params->excludes as $exclude ): ?>
                            <li class="el-item">
                                <div class="uk-grid-small uk-child-width-expand uk-flex-nowrap uk-flex-middle uk-grid" uk-grid="">
                                    <div class="uk-width-auto uk-first-column">
                                        <span class="el-image uk-icon" uk-icon="icon: close;">
                                            <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="close"><path fill="none" stroke="#000" stroke-width="1.06" d="M16,16 L4,4"></path><path fill="none" stroke="#000" stroke-width="1.06" d="M16,4 L4,16"></path></svg>
                                        </span>
                                    </div>
                                    <div>
                                        <div class="el-content uk-panel"><?php echo $exclude; ?></div>
                                    </div>
                                </div>
                            </li>
                          <?php endforeach; ?>
                        </ul>
                    </div>
                  <?php endif; ?>
                  <?php if (count($imageArrayNumerical) >= 3): ?>
                  <div class="uk-width-expand@m">
                  <div data-id="page#55" class="uk-margin">
                          <div class="tm-box-decoration-primary uk-inline"><img class="el-image" alt="" src="/images/tours/<?php echo $imageArrayNumerical[2];?>">
                          </div>
                  </div>
                  </div>
                <?php endif; ?>

                </div>
                <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid="">
                    <div class="uk-width-1-1@m uk-first-column">
                        <div class="uk-margin">
                            <a class="el-content uk-button uk-button-primary" href="#book" uk-scroll="">
                                <span class="uk-text-middle">Book this tour</span>
                                <span uk-icon="arrow-right" class="uk-icon">
                                    <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="arrow-right"><polyline fill="none" stroke="#000" points="10 5 15 9.5 10 14"></polyline><line fill="none" stroke="#000" x1="4" y1="9.5" x2="15" y2="9.5"></line></svg>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
              </div>
              <!-- message on the side of the page -->
              <!-- <div class="tm-section-title uk-position-center-left uk-position-medium uk-text-nowrap uk-visible@xl">
                <div class="tm-rotate-180">Useful Information</div>
              </div> -->
        </div>
    </div>

    <div class="uk-container uk-section-large">
      <div class="uk-grid-large uk-grid-margin-large uk-grid uk-grid-stack" uk-grid="">
    <div class="uk-width-1-1@m uk-first-column">





    <h1 class="uk-margin-large uk-heading-small uk-text-center uk-scrollspy-inview uk-animation-slide-bottom-small" data-id="page#4" uk-scrollspy-class="" style="">        Tour comparison    </h1><div class="uk-text-large uk-margin uk-width-xxlarge uk-margin-auto uk-text-center uk-scrollspy-inview uk-animation-slide-bottom-small" data-id="page#5" uk-scrollspy-class="" style="">We offer 3 tour types to cater for the different needs of our guests. <br>Compare features of each tour type to help decide which is best for your party.</div>



    </div>
    </div><div class="uk-grid-large uk-margin-large uk-grid" uk-grid="" uk-height-match="target: .uk-card; row: false">
    <div class="uk-grid-item-match uk-width-expand@m">
            <div class="uk-tile-primary uk-tile uk-flex uk-flex-middle">


                            <div class="uk-panel uk-width-1-1">

    <h3 class="uk-h1 uk-text-center uk-scrollspy-inview uk-animation-slide-bottom-small" data-id="page#8" uk-scrollspy-class="" style="">        Standard    </h3><div class="uk-text-lead uk-margin uk-margin-remove-bottom uk-text-center uk-scrollspy-inview uk-animation-slide-bottom-small" data-id="page#9" uk-scrollspy-class="" style="">Adult tickets from</div>
    <h1 class="uk-heading-small uk-margin-remove-top uk-text-center uk-scrollspy-inview uk-animation-slide-bottom-small" data-id="page#10" uk-scrollspy-class="" style="">        $59    </h1><div class="uk-margin uk-scrollspy-inview uk-animation-slide-bottom-small" data-id="page#11" uk-scrollspy-class="" style="">Our standard group tour is our most popular tour type. Guests are transferred to the destination while a professional guide chaperones the party around a carefully selected route.</div>
    <ul class="uk-list uk-list-striped uk-text-right uk-scrollspy-inview uk-animation-slide-bottom-small" data-id="page#12" uk-scrollspy-class="" style="">        <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Group size</strong>

            </div>
            <div>


                <div class="el-content uk-panel">7-16</div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Entry fees<sup>†</sup></strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: check; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="check"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span>
    <span class="hidden">Included in ticket</span></div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Certified professional guide</strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: check; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="check"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span>
    <span class="hidden">Included in ticket</span></div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Swarovski optics scope</strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: check; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="check"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span>
    <span class="hidden">Included in ticket</span></div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Transfers</strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: check; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="check"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span>
    <span class="hidden">Included in ticket</span></div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Complimentary water</strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: check; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="check"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span>
    <span class="hidden">Included in ticket</span></div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Complimentary snacks</strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: check; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="check"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span>
    <span class="hidden">Included in ticket</span></div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Complimentary meal</strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: close; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="close"><path fill="none" stroke="#000" stroke-width="1.06" d="M16,16 L4,4"></path><path fill="none" stroke="#000" stroke-width="1.06" d="M16,4 L4,16"></path></svg></span>
    <span class="hidden">Not included in this tour</span></div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Personalised tour</strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: close; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="close"><path fill="none" stroke="#000" stroke-width="1.06" d="M16,16 L4,4"></path><path fill="none" stroke="#000" stroke-width="1.06" d="M16,4 L4,16"></path></svg></span>
    <span class="hidden">Not included in this tour</span></div>

            </div>
        </div>

    </li>
        </ul>

                            </div>


            </div>

    </div>

    <div class="uk-grid-item-match uk-width-expand@m">
            <div class="uk-tile-secondary uk-tile uk-flex uk-flex-middle">


                            <div class="uk-panel uk-width-1-1">

    <h3 class="uk-h1 uk-text-center uk-scrollspy-inview uk-animation-slide-bottom-small" data-id="page#14" uk-scrollspy-class="" style="">        Private    </h3><div class="uk-text-lead uk-margin uk-margin-remove-bottom uk-text-center uk-scrollspy-inview uk-animation-slide-bottom-small" data-id="page#15" uk-scrollspy-class="" style="">Adult tickets from</div>
    <h1 class="uk-heading-small uk-margin-remove-top uk-text-center uk-scrollspy-inview uk-animation-slide-bottom-small" data-id="page#16" uk-scrollspy-class="" style="">        $99    </h1><div class="uk-margin uk-scrollspy-inview uk-animation-slide-bottom-small" data-id="page#17" uk-scrollspy-class="" style="">Deseperate to see a 3 toes sloth but one hasn't revealed itself to you? On a private tour our guides will personalise your experience to maximise time spent looking for the things you love.</div>
    <ul class="uk-list uk-list-striped uk-text-right uk-scrollspy-inview uk-animation-slide-bottom-small" data-id="page#18" uk-scrollspy-class="" style="">        <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Group size</strong>

            </div>
            <div>


                <div class="el-content uk-panel">1-6</div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Entry fees<sup>†</sup></strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: check; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="check"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span>
    <span class="hidden">Included in ticket</span></div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Certified professional guide</strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: check; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="check"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span>
    <span class="hidden">Included in ticket</span></div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Swarovski optics scope</strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: check; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="check"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span>
    <span class="hidden">Included in ticket</span></div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Transfers</strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: check; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="check"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span>
    <span class="hidden">Included in ticket</span></div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Complimentary water</strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: check; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="check"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span>
    <span class="hidden">Included in ticket</span></div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Complimentary snacks</strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: check; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="check"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span>
    <span class="hidden">Included in ticket</span></div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Complimentary meal</strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: check; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="check"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span>
    <span class="hidden">Included in ticket</span></div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Personalised tour</strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: check; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="check"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span>
    <span class="hidden">Included in ticket</span></div>

            </div>
        </div>

    </li>
        </ul>

                            </div>


            </div>

    </div>

    <div class="uk-grid-item-match uk-flex-middle uk-width-expand@m uk-flex-first@m uk-first-column">



                            <div class="uk-panel uk-width-1-1">

    <h3 class="uk-h1 uk-text-center uk-scrollspy-inview uk-animation-slide-bottom-small" data-id="page#20" uk-scrollspy-class="" style="">        Basic    </h3><div class="uk-text-lead uk-margin uk-margin-remove-bottom uk-text-center uk-scrollspy-inview uk-animation-slide-bottom-small" data-id="page#21" uk-scrollspy-class="" style="">Adult tickets from</div>
    <h1 class="uk-heading-small uk-margin-remove-top uk-text-center uk-scrollspy-inview uk-animation-slide-bottom-small" data-id="page#22" uk-scrollspy-class="" style="">        $40    </h1><div class="uk-margin uk-scrollspy-inview uk-animation-slide-bottom-small" data-id="page#23" uk-scrollspy-class="" style="">Student, Backpacker or on a tight budget?
    For those willing to forgo the creature comforts or have alternative transportion and catering arranged then the Basic option includes the same great group tours at a reduced price.</div>
    <ul class="uk-list uk-list-striped uk-text-right uk-scrollspy-inview uk-animation-slide-bottom-small" data-id="page#24" uk-scrollspy-class="" style="">        <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Group size</strong>

            </div>
            <div>


                <div class="el-content uk-panel">10-16</div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Entry fees<sup>†</sup></strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: check; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="check"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span>
    <span class="hidden">Included in ticket</span></div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Certified professional guide</strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: check; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="check"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span>
    <span class="hidden">Included in ticket</span></div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Swarovski optics scope</strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: check; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="check"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span>
    <span class="hidden">Included in ticket</span></div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Transfers</strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: close; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="close"><path fill="none" stroke="#000" stroke-width="1.06" d="M16,16 L4,4"></path><path fill="none" stroke="#000" stroke-width="1.06" d="M16,4 L4,16"></path></svg></span>
    <span class="hidden">Not included in this tour</span></div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Complimentary water</strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: close; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="close"><path fill="none" stroke="#000" stroke-width="1.06" d="M16,16 L4,4"></path><path fill="none" stroke="#000" stroke-width="1.06" d="M16,4 L4,16"></path></svg></span>
    <span class="hidden">Not included in this tour</span></div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Complimentary snack</strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: close; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="close"><path fill="none" stroke="#000" stroke-width="1.06" d="M16,16 L4,4"></path><path fill="none" stroke="#000" stroke-width="1.06" d="M16,4 L4,16"></path></svg></span>
    <span class="hidden">Not included in this tour</span></div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Complimentary meal</strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: close; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="close"><path fill="none" stroke="#000" stroke-width="1.06" d="M16,16 L4,4"></path><path fill="none" stroke="#000" stroke-width="1.06" d="M16,4 L4,16"></path></svg></span>
    <span class="hidden">Not included in this tour</span></div>

            </div>
        </div>

    </li>
            <li class="el-item">

        <div class="uk-child-width-expand uk-grid-small uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column">


        <strong class="el-title uk-display-block">Personalised tour</strong>

            </div>
            <div>


                <div class="el-content uk-panel"><span class="uk-icon" uk-icon="icon: close; ratio: 1;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="close"><path fill="none" stroke="#000" stroke-width="1.06" d="M16,16 L4,4"></path><path fill="none" stroke="#000" stroke-width="1.06" d="M16,4 L4,16"></path></svg></span>
    <span class="hidden">Not included in this tour</span></div>

            </div>
        </div>

    </li>
        </ul>

                            </div>



    </div>
    </div></div>


    <div class="uk-section-default uk-section">





            <div class="uk-container"><div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid="">
<div class="uk-width-1-1@m uk-first-column">




                <div class="uk-text-meta uk-margin"><p><sup>†</sup> Entry fees included only where a ticket is required for entry to the tour.</p>
<p>Where transfers are not included, or you make your own way to the destination please be advised to check parking restrictions and fees before setting off.</p></div>



</div>
</div></div>



</div>


  <?php endif; ?>

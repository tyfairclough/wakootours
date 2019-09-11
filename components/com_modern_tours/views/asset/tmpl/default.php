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

JHtml::_('behavior.framework');
JHTML::_('behavior.tooltip');

$canEdit = JFactory::getUser()->authorise( 'core.edit', 'com_modern_tours' );

if ( ! $canEdit && JFactory::getUser()->authorise( 'core.edit.own', 'com_modern_tours' ) )
{
$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
$doc = JFactory::getDocument();
$doc->addStyleSheet( 'media/com_modern_tours/css/style.css' );
$doc->addStyleSheet( 'media/com_modern_tours/css/sweetalert2.min.css' );
$doc->addStyleSheet( 'media/com_modern_tours/css/lightgallery.min.css' );
$doc->addStyleSheet( 'media/com_modern_tours/css/gridonly.css' );
$doc->addStyleSheet( 'media/com_modern_tours/css/tourstyle.css' );
// $doc->addStyleSheet( 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css' );
// $doc->addStyleSheet( 'https://use.fontawesome.com/releases/v5.7.1/css/all.css' );
// $doc->addStyleSheet( 'https://fonts.googleapis.com/css?family=Rubik:300,400,500,700' );
$doc->addStyleSheet("https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css");
$doc->addStyleSheet("https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css");
$doc->addScript( 'media/com_modern_tours/js/moment.min.js' );
$doc->addScript( 'media/com_modern_tours/js/underscore-min.js' );
$doc->addScript( 'media/com_modern_tours/js/clndr.min.js' );
$doc->addScript( 'media/com_modern_tours/js/moment-with-locales.js' );
$doc->addScript( 'media/com_modern_tours/js/form-render.min.js' );
$doc->addScript( 'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js" type="text/javascript' );
$doc->addScript( 'media/com_modern_tours/js/functions.js' );
$doc->addScript( 'media/com_modern_tours/js/tour.js' );
$doc->addScript( 'https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.21/moment-timezone-with-data.js' );
// $doc->addScript( 'https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js' );
$doc->addScript( 'https://js.stripe.com/v2/" type="text/javascript' );
$doc->addScript( 'https://cdn.rawgit.com/leafo/sticky-kit/v1.1.2/jquery.sticky-kit.min.js' );
$doc->addScript( 'media/com_modern_tours/js/lightgallery.min.js' );
$doc->addScript( 'media/com_modern_tours/js/lg-fullscreen.min.js' );
$doc->addScript( 'https://parsleyjs.org/dist/parsley.min.js' );
$doc->addScript( 'media\com_modern_tours\js\owl.carousel.min.js' );


$this->id = JFactory::getApplication()->input->get('id');
$itemid = JFactory::getApplication()->input->get('Itemid');
$this->showCover = $this->params->get('show_cover');
$checkoutView = !$this->travellersData ? 'checkout_2steps' : 'checkout';
$this->url = JURI::current();
$interval = false;

if(MTHelper::hasAvailability($this->item))
{
$interval = true;
$startDay = $this->item->params->{'from-day'};
$startMonth = $this->item->params->{'from-month'};
$endDay = $this->item->params->{'to-day'};
$endMonth = $this->item->params->{'to-month'};
}


$this->reviewText = $this->params->get('reviews_link_frontend') && $this->params->get('reviews_link_frontend') ?  'NO_REVIEWS_BE_FIRST' : 'NO_REVIEWS';
$this->reviewLink = 'index.php?option=com_modern_tours&task=leaveReview&id=' . $this->item->id;
?>
<script>
var tourInDays = '<?php echo $this->item->params->duration ? $this->item->params->duration : 0; ?>';
var public_key = "<?php echo $this->stripeKey ?>";
var hours = JSON.parse('<?php echo $this->hours; ?>');
var interval = '<?php echo $interval; ?>';
var reservations = JSON.parse('<?php echo $this->reservations; ?>');
var locale = '<?php echo MTHelper::getLanguage(); ?>';
var clockFormat = 'HH:mm';
var userData = '<?php echo $this->userData; ?>';
var emailFields = '<?php echo $this->emailFields; ?>';
var travellersData = '<?php echo $this->travellersData; ?>';
var url = '<?php echo JURI::base(); ?>';
var itemid = '<?php echo $itemid; ?>';
var deposit_percentage = '<?php echo $this->deposit->deposit_percentage; ?>';
var force_deposit = '<?php echo $this->deposit->deposit_booking_choose; ?>';
var translations = {
'CONFIRM_BUTTON_TEXT': '<?php echo JText::_( "CONFIRM_BUTTON_TEXT" ); ?>',
'CANCEL_BUTTON_TEXT': '<?php echo JText::_( "CANCEL_BUTTON_TEXT" ); ?>',
'SELECT_PARTICIPANTS': '<?php echo JText::_( "SELECT_PARTICIPANTS" ); ?>',
'CHOOSE_PAYMENT_METHOD': '<?php echo JText::_( "CHOOSE_PAYMENT_METHOD" ); ?>',
'SELECT_DATE': '<?php echo JText::_( "SELECT_DATE" ); ?>',
'ENTER_CARD_DETAILS': '<?php echo JText::_( "ENTER_CARD_DETAILS" ); ?>',
'NO_SLOTS_LEFT': '<?php echo JText::_( "NO_SLOTS_LEFT" ); ?>',
'SOLD_OUT_CLICKED': '<?php echo JText::_( "SOLD_OUT_CLICKED" ); ?>',
'SOLD_OUT_CLICKED_DESCRIPTION': '<?php echo JText::_( "SOLD_OUT_CLICKED_DESCRIPTION" ); ?>',
'SUCCESSFUL_REGISTRATION': '<?php echo JText::_( "SUCCESSFUL_REGISTRATION" ); ?>',
'CHECK_EMAIL': '<?php echo JText::_( "CHECK_EMAIL" ); ?>',
'ERROR_WITH_PAYMENT': '<?php echo JText::_( "ERROR_WITH_PAYMENT" ); ?>',
'CONTACT_ADMINISTRATOR': '<?php echo JText::_( "CONTACT_ADMINISTRATOR" ); ?>',
'NO_MORE_SLOTS': '<?php echo JText::_( "NO_MORE_SLOTS" ); ?>',
'NO_FORM_CREATED': '<?php echo JText::_( "NO_FORM_CREATED" ); ?>',
'SLOTS_AVAILABLE': '<?php echo JText::_( "SLOTS_AVAILABLE" ); ?>',
'RESERVATION_COMPLETE': '<?php echo JText::_( "RESERVATION_COMPLETE" ); ?>'
};

<?php if($interval): ?>
var startDay = <?php echo $startDay; ?>;
var startMonth = <?php echo $startMonth; ?>;
var endDay = <?php echo $endDay; ?>;
var endMonth = <?php echo $endMonth; ?>;
<?php endif; ?>

jQuery(document).ready(function($) {
$("#related-carousel").owlCarousel({
nav: 1,
margin: 30,
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
items:2
}
}
});
});

console.log(hours);
</script>

<?php echo $this->loadTemplate($checkoutView); ?>



<div class="tour-body">

<div id="participants" style="display: none;"></div>
<div id="ccc" style="display:none;">
<div id="travellers-data-form"></div>
</div>
<div id="tok" style="display: none;"></div>

<div id="clndr"></div>
<div id="tool"></div>

<!-- INTRO -->


<?php if($this->showCover): ?>
<?php echo $this->loadTemplate('cover'); ?>
<?php endif; ?>
<!-- END INTRO -->

<?php
$array = $this->item->imageFiles;
$numerical = array_values($array);
// print_r(array_values($numerical));
?>



<!-- BOOK -->
<div id="book" class="uk-section-default uk-section uk-padding-remove-vertical">
		<div class="uk-position-relative">
				<div class="uk-container uk-container-large">
						<div class="uk-grid-collapse uk-grid" uk-grid="" uk-height-match="target: .uk-card; row: false">
							<div class="uk-grid-item-match uk-flex-middle uk-visible@m uk-width-expand@m uk-first-column">
									<?php if (count($numerical) >= 1): ?>
										<div class="uk-panel uk-width-1-1">
												<div class="uk-visible@m uk-margin uk-width-xlarge uk-margin-auto-left uk-text-right" uk-parallax="y: -100,-240; media: @m;" style="transform: translateY(-106px);">
												    <div class="tm-box-decoration-default uk-inline"><img class="el-image" alt="" uk-img="" src="/images/tours/<?php echo $numerical[0];?>"></div>
												</div>
										</div>
									<?php endif; ?>
							</div>
							<div class="uk-width-expand@m">
												<?php echo $this->loadTemplate('ticket_box'); ?>
							</div>
						</div>
				</div>
		</div>
</div>

<!-- END BOOK -->

<!-- DETAILS -->
<div class="uk-section-default uk-section-overlap uk-section uk-section-large">
    <div class="uk-position-relative">
        <div class="uk-container uk-container-large">
						<div class="uk-grid-large uk-grid-margin-large uk-grid uk-grid-stack" uk-grid="">
							<div class="uk-width-expand@l uk-first-column">
								<?php if (count($numerical) >= 2): ?>
									<div class="tm-box-decoration-secondary">
											<!-- second photo here -->
											<img class="el-image" alt="" uk-img="" src="/images/tours/<?php echo $numerical[1];?>">
									</div>
								<?php endif; ?>
							</div>
<div class="uk-width-expand@l uk-flex-first@l uk-first-column uk-grid-margin">
<h2 class="uk-heading-medium uk-heading-bullet"><?php echo JText::_( 'TOUR_DETAILS' ); ?></h2>
<div class="uk-margin-large uk-margin-remove-top">
	<?php echo nl2br( $this->item->description ); ?>
</div>
<h3 class="uk-h4 uk-margin-small">Key features</h3>
<ul class="uk-list uk-list-large uk-margin-medium uk-margin-remove-top">
	<li class="el-item">
    <div class="uk-grid-small uk-child-width-expand uk-flex-nowrap uk-flex-middle uk-grid" uk-grid="">
			<div class="uk-width-auto uk-first-column">
				<span class="el-image uk-icon" uk-icon="icon: clock;">
					<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="clock"><circle fill="none" stroke="#000" stroke-width="1.1" cx="10" cy="10" r="9"></circle><rect x="9" y="4" width="1" height="7"></rect><path fill="none" stroke="#000" stroke-width="1.1" d="M13.018,14.197 L9.445,10.625"></path></svg>
				</span>
			</div>
			<div>
            <div class="el-content uk-panel"><?php echo JText::_( 'TIME_LENGTH' ); ?><?php echo $this->item->params->length; ?></div>
					</div>
    </div>
</li>
        <li class="el-item">
    <div class="uk-grid-small uk-child-width-expand uk-flex-nowrap uk-flex-middle uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column"><span class="el-image uk-icon" uk-icon="icon: history;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="history"><polyline fill="#000" points="1 2 2 2 2 6 6 6 6 7 1 7 1 2"></polyline><path fill="none" stroke="#000" stroke-width="1.1" d="M2.1,6.548 C3.391,3.29 6.746,1 10.5,1 C15.5,1 19.5,5 19.5,10 C19.5,15 15.5,19 10.5,19 C5.5,19 1.5,15 1.5,10"></path><rect x="9" y="4" width="1" height="7"></rect><path fill="none" stroke="#000" stroke-width="1.1" d="M13.018,14.197 L9.445,10.625"></path></svg></span></div>        <div>
            <div class="el-content uk-panel">Arrive 15 minutes before departure</div>        </div>
    </div>
</li>
        <li class="el-item">
    <div class="uk-grid-small uk-child-width-expand uk-flex-nowrap uk-flex-middle uk-grid" uk-grid="">        <div class="uk-width-auto uk-first-column"><span class="el-image uk-icon" uk-icon="icon: users;"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="users"><circle fill="none" stroke="#000" stroke-width="1.1" cx="7.7" cy="8.6" r="3.5"></circle><path fill="none" stroke="#000" stroke-width="1.1" d="M1,18.1 C1.7,14.6 4.4,12.1 7.6,12.1 C10.9,12.1 13.7,14.8 14.3,18.3"></path><path fill="none" stroke="#000" stroke-width="1.1" d="M11.4,4 C12.8,2.4 15.4,2.8 16.3,4.7 C17.2,6.6 15.7,8.9 13.6,8.9 C16.5,8.9 18.8,11.3 19.2,14.1"></path></svg></span></div>        <div>
            <div class="el-content uk-panel">Maximum group size: 16</div>        </div>
    </div>
</li>
    </ul>

	<div class="uk-margin">
		<a class="el-content uk-button uk-button-default uk-button-large" href="#book" uk-scroll="">
			<span class="uk-text-middle">Book this tour</span>
			<span uk-icon="arrow-right" class="uk-icon"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="arrow-right"><polyline fill="none" stroke="#000" points="10 5 15 9.5 10 14"></polyline><line fill="none" stroke="#000" x1="4" y1="9.5" x2="15" y2="9.5"></line></svg></span>
		</a>
	</div>
</div>
</div>
</div>

<!--<div class="tm-section-title uk-position-center-left uk-position-medium uk-text-nowrap uk-visible@xl">                <div class="tm-rotate-180">About this tour</div>
</div>
-->
        </div>


</div>

<!-- END DETAILS -->

<!-- ITINERARY -->
<?php echo $this->loadTemplate('itirenary'); ?>
<!-- END ITINERARY -->

<!-- WHATS INCLUDED -->
<?php echo $this->loadTemplate('extras♂'); ?>
<!-- END WHATS INCLUDED -->

<!-- RELATED TOURS -->
<?php echo $this->loadTemplate('related'); ?>
<!-- END RELATED TOURS -->

<!-- REVIEWS -->
<?php echo $this->loadTemplate('reviews'); ?>
<!-- END REVIEWS -->

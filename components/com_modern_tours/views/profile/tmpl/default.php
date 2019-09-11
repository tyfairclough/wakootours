<?php
/**
 * @version     CVS: 1.0.0
 * @package     com_modern_tours
 * @author      modernjoomla.com <support@modernjoomla.com>
 * @copyright   modernjoomla.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined( '_JEXEC' ) or die;

MTHelper::loadLanguage();
$doc = JFactory::getDocument();
$doc->addStyleSheet( 'media/com_modern_tours/css/style.css' );
$doc->addStyleSheet( 'media/com_modern_tours/css/bootstrap-min.css' );
$doc->addStyleSheet( 'media/com_modern_tours/css/profile.css' );
$doc->addStyleSheet( 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css' );
$doc->addStyleSheet( 'media/com_modern_tours/css/sweetalert2.min.css' );
$doc->addScriptDeclaration('var PLEASE_WAIT = "' . JText::_('PLEASE_WAIT') . '", BOOKING_CANCELED = "' . JText::_('BOOKING_CANCELED') . '", CANCELED = "' . JText::_('CANCELED') . '", SUCCESSFULLY_CANCELED = "' . JText::_('SUCCESSFULLY_CANCELED') . '", SUCCESSFUL = "' . JText::_('SUCCESSFUL') . '";');
$doc->addScript('media/com_modern_tours/js/profile.js');
$doc->addScript( 'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js" type="text/javascript' );
$userToken = JSession::getFormToken();
$user      = JFactory::getUser();        // Get the user object
$app       = JFactory::getApplication(); // Get the application
?>
<?php if ( $user->id == 0 ): ?>
    <div class="text-center align-center">
        <h1 class="not-logged"><?php echo JText::_( 'NOT_LOGGED_IN' ); ?></h1>
        <div class="login-text">
	        <?php echo $renderer = JFactory::getDocument()->loadRenderer('modules')->render('login',  array('style' => 'raw'), null);?>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-lg-4">
            <ul id="accordion" class="accordion">
                <li>
                    <div class="iamgurdeep-pic">
                        <img class="img-responsive iamgurdeeposahan" alt="iamgurdeeposahan"
                             src="https://cdn.cnn.com/cnnnext/dam/assets/170407220921-07-iconic-mountains-pitons-restricted.jpg">
                        <div class="username">
                            <h2>
								<?php echo $user->name; ?>
                                <small><i class="fa fa-map-marker"></i></small>
                            </h2>
<!--                            <p><i class="fa fa-briefcase"></i> You have 0 points</p>-->
                        </div>
                    </div>
                </li>
                <li>
                    <div class="link">
                        <a href="<?php echo JRoute::_('index.php?option=com_modern_tours&view=profile'); ?>">
                            <i class="fa fa-globe"></i><?php echo JText::_( 'MY_BOOKINGS' ); ?>
                        </a>
                    </div>
                </li>
                <li>
                    <a href="<?php echo JRoute::_('index.php?option=com_modern_tours&view=profile&page=reviews'); ?>" class="link"><i class="fa fa-star"></i><?php echo JText::_( 'MY_REVIEWS' ); ?></a>
                </li>
<!--                <li>-->
<!--                    <div class="link"><i class="fa fa-code"></i>--><?php //echo JText::_( 'WISHLIST' ); ?><!--</div>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <div class="link"><i class="fa fa-picture-o"></i>--><?php //echo JText::_( 'EDIT_PROFILE' ); ?><!--</div>-->
<!--                </li>-->
                <li>
                    <a href="index.php?option=com_users&task=user.logout&<?php echo $userToken; ?>=1">
                        <div class="link"><i class="fa fa-users"></i><?php echo JText::_( 'LOG_OUT' ); ?><i
                                    class="fa fa-window-close"></i></div>
                    </a>
                </li>
            </ul>
        </div>

    <div class="col-lg-8">
	    <?php echo $this->loadTemplate($this->view); ?>
    </div>
<?php endif; ?>

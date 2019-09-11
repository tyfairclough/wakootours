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
?>
<section class="header-cover">
    <div class="cover<?php if ( $this->params->get('full_width_cover')) : ?> full-width<?php endif; ?>" style="background-image: url('<?php echo $this->item->cover; ?>');">
        <div class="cover-overlay"></div>
    </div>


    <div class="header-block">
        <?php if ( $this->params->get('show_reviews_cover')) : ?>
            <div class="start">
                <?php echo $this->item->reviewHTML; ?>
            </div>
        <?php endif; ?>
        <h1 class="tour-title"><?php echo $this->item->title; ?></h1>
        <div class="time-list">
            <?php if($this->item->params->length): ?>
                <span><i class="fa fa-calendar fa-1x" aria-hidden="true"></i>
                    <?php echo JText::_( 'TIME_LENGTH' ); ?><?php echo $this->item->params->length; ?></span>
            <?php endif; ?>

            <?php if($this->item->max_people): ?><span><i class="fa fa-user-o fa-1x" aria-hidden="true"></i>
                <?php echo JText::_( 'MAX_PEOPLE' ); ?><?php echo $this->item->max_people; ?></span><?php endif; ?>
            <?php if($this->item->params->departure): ?><span><i class="fa fa-paper-plane-o fa-1x" aria-hidden="true"></i>
                <?php echo JText::_( 'DEPARTURE' ); ?><?php echo $this->item->params->departure; ?></span><?php endif; ?>
            <?php if($this->item->params->destination): ?><span><i class="fa fa-paper-plane fa-1x" aria-hidden="true"></i>
                <?php echo JText::_( 'DESTINATION' ); ?><?php echo $this->item->params->destination; ?></span><?php endif; ?>
            <?php if($this->item->params->availability): ?><span><i class="fa fa-calendar-check-o fa-1x" aria-hidden="true"></i>
                <?php echo JText::_( 'AVAILABIITY' ); ?><?php echo $this->item->params->availability; ?></span><?php endif; ?>
            <?php if($this->item->params->arrive): ?><span><i class="fa fa-clock-o fa-1x" aria-hidden="true"></i>
                <?php echo JText::_( 'ARRIVE' ); ?><?php echo $this->item->params->arrive; ?></span><?php endif; ?>
        </div>
        <?php if($this->item->imageFiles): ?>
            <span id="view-gallery"><i class="fa fa-picture-o"></i><span class="view-gallery"> <?php echo JText::_( 'VIEW_GALLERY' ); ?></span> </span>
        <?php endif; ?>
    </div>
</section>
<?php if(isset( $this->item->params->itirenary ) || $this->item->imageFiles || $this->params->get('show_empty_reviews') || isset( $this->item->related[0])) : ?>
<section class="content-block-menu">
    <div class="cover<?php if ( $this->params->get('full_width_cover')) : ?> full-width<?php endif; ?> grey"></div>
    <div class="container">
        <div class="row">
            <ul class="menu">
                <li>
                    <a href="<?php echo $this->url; ?>#tour-details-section" class="active"><i class="fa fa-info-circle menu-icon" aria-hidden="true"></i> <?php echo JText::_( 'DETAILS' ); ?></a>
                </li>

                <?php if ( isset( $this->item->params->itirenary ) ): ?>
                    <li>
                        <a href="<?php echo $this->url; ?>#tour-itirenary-section"><i class="fa fa-plane  menu-icon" aria-hidden="true"></i> <?php echo JText::_( 'ITIRENARY' ); ?></a>
                    </li>
                <?php endif; ?>

                <?php if($this->item->imageFiles): ?>
                    <li id="view-photos">
                        <a href="<?php echo $this->url; ?>#tour-photos-section"><i class="fa fa-info-circle menu-icon" aria-hidden="true"></i> <?php echo JText::_( 'PHOTOS' ); ?></a>
                    </li>
                <?php endif; ?>

                <?php if($this->params->get('show_empty_reviews')): ?>
                    <li>
                        <a href="<?php echo $this->url; ?>#tour-reviews-section"><i class="fa fa-users menu-icon" aria-hidden="true"></i> <?php echo JText::_( 'REVIEWS_TITLE' ); ?></a>
                    </li>
                <?php endif; ?>

                <?php if ( isset( $this->item->related[0] ) ): ?>
                    <li>
                        <a href="<?php echo $this->url; ?>#related-tours"><i class="fa fa-info-circle menu-icon" aria-hidden="true"></i> <?php echo JText::_( 'RELATED_TOURS' ); ?></a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</section>
<?php endif; ?>

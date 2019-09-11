<?php
/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jonas <jonasjov2@gmail.com> - http://www.modernjoomla.com
 */

// no direct access
defined( '_JEXEC' ) or die;

JHtml::addIncludePath( JPATH_COMPONENT . '/helpers/html' );
require_once JPATH_COMPONENT . '/helpers/modern_tours.php';
JHtml::_( 'bootstrap.tooltip' );
JHtml::_( 'behavior.multiselect' );
JHtml::_( 'formbehavior.chosen', 'select' );
JHtml::_( 'behavior.modal', 'a.modal' );

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet( JURI::root() . 'media/com_modern_tours/css/modern_tours.css' );
$document->addScript( JURI::root() . 'media/com_modern_tours/js/jqu.js' );

$user      = JFactory::getUser();
$userId    = $user->get( 'id' );
$listOrder = $this->state->get( 'list.ordering' );
$listDirn  = $this->state->get( 'list.direction' );
$canOrder  = $user->authorise( 'core.edit.state', 'com_modern_tours' );
$saveOrder = $listOrder == 'a.`ordering`';
if ( $saveOrder )
{
	$saveOrderingUrl = 'index.php?option=com_modern_tours&task=coupons.saveOrderAjax&tmpl=component';
	JHtml::_( 'sortablelist.sortable', 'couponList', 'adminForm', strtolower( $listDirn ), $saveOrderingUrl );
}
$sortFields  = $this->getSortFields();
$user_coupon = $this->state->get( 'filter.user' );
?>

<script type="text/javascript">
    Joomla.orderTable = function () {
        table = document.getElementById("sortTable");
        direction = document.getElementById("directionTable");
        order = table.options[table.selectedIndex].value;
        if (order != '<?php echo $listOrder; ?>') {
            dirn = 'asc';
        } else {
            dirn = direction.options[direction.selectedIndex].value;
        }
        Joomla.tableOrdering(order, dirn, '');
    }

    jQuery(document).ready(function () {
        jQuery('#clear-search-button').on('click', function () {
            jQuery('#filter_search').val('');
            jQuery('#adminForm').submit();
        });

        jQuery('#coupon-code').draggable({
            containment: '#coupon-container'
        });

        jQuery('#toolbar-couponPDF').click(function() {
            jQuery('.container-for-coupon, #selection').show();
        });

        jQuery('#save-coupon').click(function() {
            var coupon = jQuery('#coupon-code');
            var coords = coupon.attr('style');
            var style = coords.replace('relative;', 'absolute; z-index:9999;');

            jQuery.ajax({
                type: "GET",
                url: 'index.php?option=com_modern_tours&task=invoice.saveInvoice&style=' + style,
                success: function (response) {
                    jQuery('.container-for-coupon, #selection').fadeOut(666);
                    alert('Invoice coupon code position saved');
                }
            });
        });

        jQuery('#toolbar-couponPDF button').attr('onclick', '');

    });
</script>

<?php
//Joomla Component Creator code to allow adding non select list filters
if ( ! empty( $this->extra_sidebar ) )
{
	$this->sidebar .= $this->extra_sidebar;
}
?>

<form action="<?php echo JRoute::_( 'index.php?option=com_modern_tours&view=coupons' ); ?>" method="post"
      name="adminForm" id="adminForm">
	<?php if ( ! empty( $this->sidebar ) ): ?>
    <div id="j-main-container" class="span10">
		<?php else : ?>
        <div id="j-main-container">
			<?php endif; ?>
            <div class="clearfix"></div>
            <div class="table-contaner">
                <h4><?php echo JText::_( 'COUPONS_TITLE' ); ?></h4>
                <table class="table table-striped" id="couponList">
                    <thead>
                    <tr>
						<?php if ( isset( $this->items[0]->ordering ) ): ?>
                            <th width="1%" class="nowrap center hidden-phone">
								<?php echo JHtml::_( 'grid.sort', '<i class="icon-menu-2"></i>', 'a.`ordering`', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING' ); ?>
                            </th>
						<?php endif; ?>
                        <th width="1%" class="hidden-phone">
                            <input type="checkbox" name="checkall-toggle" value=""
                                   title="<?php echo JText::_( 'JGLOBAL_CHECK_ALL' ); ?>"
                                   onclick="Joomla.checkAll(this)"/>
                        </th>
						<?php if ( isset( $this->items[0]->state ) ): ?>
                            <th width="1%" class="nowrap center">
								<?php echo JHtml::_( 'grid.sort', 'JSTATUS', 'a.`state`', $listDirn, $listOrder ); ?>
                            </th>
						<?php endif; ?>


						<?php if ( $user_coupon ) : ?>
                            <th class='left'>
								<?php echo JHtml::_( 'grid.sort', 'Added', 'a.`added`', $listDirn, $listOrder ); ?>
                            </th>
                            <th class='left'>
								<?php echo JHtml::_( 'grid.sort', 'Name', 'xx.`name`', $listDirn, $listOrder ); ?>
                            </th>
						<?php endif; ?>
                        <th class='left'>
							<?php echo JHtml::_( 'grid.sort', 'COM_MODERN_TOURS_COUPONS_TITLE', 'a.`title`', $listDirn, $listOrder ); ?>
                        </th>
                        <th class='left'>
							<?php echo JHtml::_( 'grid.sort', 'COM_MODERN_TOURS_COUPONS_CODE', 'a.`code`', $listDirn, $listOrder ); ?>
                        </th>
						<?php if ( ! $user_coupon ) : ?>
                            <th class='left'>
								<?php echo JHtml::_( 'grid.sort', 'COM_MODERN_TOURS_COUPONS_START', 'a.`start`', $listDirn, $listOrder ); ?>
                            </th>
                            <th class='left'>
								<?php echo JHtml::_( 'grid.sort', 'COM_MODERN_TOURS_COUPONS_END', 'a.`end`', $listDirn, $listOrder ); ?>
                            </th>
                            <th class='left'>
								<?php echo JHtml::_( 'grid.sort', 'COM_MODERN_TOURS_COUPONS_COUPONSNUMBER', 'a.`couponsnumber`', $listDirn, $listOrder ); ?>
                            </th>
						<?php endif; ?>
						<?php if ( ! $user_coupon ) : ?>
                            <th class='left'>
								<?php echo JHtml::_( 'grid.sort', 'COM_MODERN_TOURS_COUPONS_PRICEPERCENT', 'a.`pricepercent`', $listDirn, $listOrder ); ?>
                            </th>
						<?php else: ?>
                            <th class='left'>
								<?php echo JHtml::_( 'grid.sort', 'Price', 'a.`pricepercent`', $listDirn, $listOrder ); ?>
                            </th>
						<?php endif; ?>
						<?php if ( ! $user_coupon ) : ?>
                            <th class='left'>
								<?php echo JHtml::_( 'grid.sort', 'COM_MODERN_TOURS_COUPONS_PRICETYPE', 'a.`pricetype`', $listDirn, $listOrder ); ?>
                            </th>

						<?php endif; ?>
						<?php if ( $user_coupon ) : ?>
                            <th class='left'>
								<?php echo JHtml::_( 'grid.sort', 'PDF Link', 'pdf', $listDirn, $listOrder ); ?>
                            </th>
						<?php endif; ?>

						<?php if ( isset( $this->items[0]->id ) ): ?>
                            <th width="1%" class="nowrap center hidden-phone">
								<?php echo JHtml::_( 'grid.sort', 'JGRID_HEADING_ID', 'a.`id`', $listDirn, $listOrder ); ?>
                            </th>
						<?php endif; ?>
                    </tr>
                    </thead>
                    <tfoot>
					<?php
					if ( isset( $this->items[0] ) )
					{
						$colspan = count( get_object_vars( $this->items[0] ) );
					}
					else
					{
						$colspan = 10;
					}
					?>
                    <tr>
                        <td colspan="<?php echo $colspan ?>">
							<?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                    </tfoot>
                    <tbody>
					<?php foreach ( $this->items as $i => $item ) :
						$ordering = ( $listOrder == 'a.ordering' );
						$canCreate = $user->authorise( 'core.create', 'com_modern_tours' );
						$canEdit = $user->authorise( 'core.edit', 'com_modern_tours' );
						$canCheckin = $user->authorise( 'core.manage', 'com_modern_tours' );
						$canChange = $user->authorise( 'core.edit.state', 'com_modern_tours' );
						?>
                        <tr class="row<?php echo $i % 2; ?>">

							<?php if ( isset( $this->items[0]->ordering ) ): ?>
                                <td class="order nowrap center hidden-phone">
									<?php if ( $canChange ) :
										$disableClassName = '';
										$disabledLabel = '';
										if ( ! $saveOrder ) :
											$disabledLabel    = JText::_( 'JORDERINGDISABLED' );
											$disableClassName = 'inactive tip-top';
										endif; ?>
                                        <span class="sortable-handler hasTooltip <?php echo $disableClassName ?>"
                                              title="<?php echo $disabledLabel ?>">
							<i class="icon-menu"></i>
						</span>
                                        <input type="text" style="display:none" name="order[]" size="5"
                                               value="<?php echo $item->ordering; ?>"
                                               class="width-20 text-area-order "/>
									<?php else : ?>
                                        <span class="sortable-handler inactive">
							<i class="icon-menu"></i>
						</span>
									<?php endif; ?>
                                </td>
							<?php endif; ?>
                            <td class="hidden-phone">
								<?php echo JHtml::_( 'grid.id', $i, $item->id ); ?>
                            </td>
							<?php if ( isset( $this->items[0]->state ) ): ?>
                                <td class="center">
									<?php echo JHtml::_( 'jgrid.published', $item->state, $i, 'coupons.', $canChange, 'cb' ); ?>
                                </td>
							<?php endif; ?>
							<?php if ( $user_coupon ) : ?>
                                <td>
									<?php echo $item->added; ?>
                                </td>
                                <td>
									<?php echo $item->name . ' ' . $item->surname; ?>
                                </td>
							<?php endif; ?>


                            <td>
								<?php if ( isset( $item->checked_out ) && $item->checked_out ) : ?>
									<?php echo JHtml::_( 'jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'coupons.', $canCheckin ); ?>
								<?php endif; ?>
								<?php if ( $canEdit ) : ?>
                                    <a href="<?php echo JRoute::_( 'index.php?option=com_modern_tours&task=coupon.edit&id=' . (int) $item->id ); ?>">
										<?php echo $this->escape( $item->title ); ?></a>
								<?php else : ?>
									<?php echo $this->escape( $item->title ); ?>
								<?php endif; ?>
                            </td>
                            <td>
								<?php echo $item->code; ?>
                            </td>
							<?php if ( ! $user_coupon ) : ?>
                                <td>

									<?php echo $item->start; ?>
                                </td>
                                <td>

									<?php echo $item->end; ?>
                                </td>
                                <td>

									<?php echo $item->couponsnumber; ?>
                                </td>
							<?php endif; ?>

                            <td>

								<?php echo $item->pricepercent; ?>
                            </td>
							<?php if ( ! $user_coupon ) : ?>
                                <td>

									<?php echo $item->pricetype; ?>
                                </td>
							<?php endif; ?>

							<?php if ( $user_coupon ) : ?>
                                <td>
                                    <a style="white-space: nowrap; overflow: hidden; text-overflow:ellipsis;"
                                       href="<?php echo JURI::base() . '../components/com_modern_tours/invoice/Invoice_' . $item->id . '.pdf'; ?>">Link
                                        to PDF</a>
                                </td>
							<?php endif; ?>



							<?php if ( isset( $this->items[0]->id ) ): ?>
                                <td class="center hidden-phone">
									<?php echo (int) $item->id; ?>
                                </td>
							<?php endif; ?>
                        </tr>
					<?php endforeach; ?>
                    </tbody>
                </table>

                <input type="hidden" name="task" value=""/>
                <input type="hidden" name="boxchecked" value="0"/>
                <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
                <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
				<?php echo JHtml::_( 'form.token' ); ?>
            </div>
        </div>
</form>

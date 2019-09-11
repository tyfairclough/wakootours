<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Modern_tours
 * @author     Modernjoomla.com <support@modernjoomla.com>
 * @copyright  modernjoomla.com
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined( '_JEXEC' ) or die;

JHtml::addIncludePath( JPATH_COMPONENT . '/helpers/' );
JHtml::_( 'bootstrap.tooltip' );
JHtml::_( 'behavior.multiselect' );
JHtml::_( 'formbehavior.chosen', 'select' );

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet( JUri::root() . 'media/com_modern_tours/css/modern_tours.css' );

$user      = JFactory::getUser();
$userId    = $user->get( 'id' );
$listOrder = $this->state->get( 'list.ordering' );
$listDirn  = $this->state->get( 'list.direction' );
$canOrder  = $user->authorise( 'core.edit.state', 'com_modern_tours' );
$saveOrder = $listOrder == 'a.`ordering`';

if ( $saveOrder )
{
	$saveOrderingUrl = 'index.php?option=com_modern_tours&task=reservations.saveOrderAjax&tmpl=component';
	JHtml::_( 'sortablelist.sortable', 'reservationList', 'adminForm', strtolower( $listDirn ), $saveOrderingUrl );
}

$sortFields = $this->getSortFields();

?>
<script>
    jQuery(document).ready(function(){
        jQuery('.xxx').click(function() {
            var content = jQuery(this).parent().find('.info-block').html();
            jQuery('.modal-body').html(content);
        });

        jQuery('.change-status').click(function() {
            jQuery('.states-list').hide();
            jQuery(this).find('.states-list').show();
        });

        jQuery('.states-list div').click(function() {
            var unique_id = jQuery(this).parents('tr').find('input[type=checkbox]').val();
            var name = jQuery(this).text();
            setTimeout(function () {
                jQuery('.states-list').hide();
            }, 100);
            jQuery(this).parents('.statuses').find('.status').html(jQuery(this)[0].outerHTML);
            window.location.href = '<?php echo JURi::current(); ?>?option=com_modern_tours&view=reservations&status='+name+'&task=reservation.changeStatus&unique_id='+unique_id;
        });

        jQuery('.reveal').click(function() {
            jQuery(this).hide().parent().css({'height': '100%'});

        });

    });
</script>

<?php echo JHTML::_('bootstrap.renderModal', 'myModal', array(), 'asdasd'); ?>


<?php
//Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar)) {
	$this->sidebar .= $this->extra_sidebar;
}
?>

<form action="<?php echo JRoute::_( 'index.php?option=com_modern_tours&view=reservations' ); ?>" method="post" name="adminForm" id="adminForm">
            <div id="j-main-container">
                <div class="table-contaner">
                    <h4><?php echo JText::_('RESERVATIONS_TITLE'); ?></h4>
<!--	                --><?php //echo JLayoutHelper::render( 'joomla.searchtools.default', array( 'view' => $this ) ); ?>

                    <table class="table table-striped" id="reservationList">
                <thead>
                <tr>
					<?php if ( isset( $this->items[0]->ordering ) ): ?>
                        <th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_( 'searchtools.sort', '', 'a.`ordering`', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2' ); ?>
                        </th>
					<?php endif; ?>
                    <th width="1%" class="hidden-phone">
                        <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_( 'JGLOBAL_CHECK_ALL' ); ?>" onclick="Joomla.checkAll(this)"/>
                    </th>
					<?php if ( isset( $this->items[0]->state ) ): ?>

					<?php endif; ?>

                    <th class='left'>
						<?php echo JHtml::_( 'searchtools.sort', 'COM_MODERN_TOURS_RESERVATIONS_ID', 'a.`id`', $listDirn, $listOrder ); ?>
                    </th>
                    <th class='left'>
						<?php echo JHtml::_( 'searchtools.sort', 'COM_MODERN_TOURS_RESERVATIONS_STATUS', 'a.`status`', $listDirn, $listOrder ); ?>
                    </th>
                    <th class='left'>
		                <?php echo JHtml::_( 'searchtools.sort', 'COM_MODERN_TOURS_ASSET_NAME', 'a.`asset_id`', $listDirn, $listOrder ); ?>
                    </th>
                    <th class='left'>
		                <?php echo JHtml::_( 'searchtools.sort', 'COM_MODERN_TOURS_RESERVATIONS_USERDATA', 'a.`userData`', $listDirn, $listOrder ); ?>
                    </th>
                    <th class='left'>
		                <?php echo JHtml::_( 'searchtools.sort', 'COM_MODERN_TOURS_RESERVATIONS_EMAIL', 'a.`email`', $listDirn, $listOrder ); ?>
                    </th>
                    <th class='left'>
		                <?php echo JHtml::_( 'searchtools.sort', 'COM_MODERN_TOURS_RESERVATIONS_TRAVELLERSDATA', 'a.`travellersData`', $listDirn, $listOrder ); ?>
                    </th>
                    <th class='left'>
		                <?php echo JHtml::_( 'searchtools.sort', 'COM_MODERN_TOURS_RESERVATIONS_ADULTS', 'a.`adults`', $listDirn, $listOrder ); ?>
                    </th>
                    <th class='left'>
		                <?php echo JHtml::_( 'searchtools.sort', 'COM_MODERN_TOURS_RESERVATIONS_CHILDREN', 'a.`children`', $listDirn, $listOrder ); ?>
                    </th>
                    <th class='left'>
		                <?php echo JHtml::_( 'searchtools.sort', 'COM_MODERN_TOURS_RESERVATIONS_PRICE', 'a.`price`', $listDirn, $listOrder ); ?>
                    </th>
                    <th class='left'>
		                <?php echo JHtml::_( 'searchtools.sort', 'COM_MODERN_TOURS_RESERVATIONS_PAID_SUM', 'a.`paid_deposit`', $listDirn, $listOrder ); ?>
                    </th>
                    <th class='left'>
		                <?php echo JHtml::_( 'searchtools.sort', 'COM_MODERN_TOURS_RESERVATIONS_USER', 'a.`registered`', $listDirn, $listOrder ); ?>
                    </th>
                    <th class='left'>
		                <?php echo JHtml::_( 'searchtools.sort', 'COM_MODERN_TOURS_RESERVATIONS_DATE', 'a.`date`', $listDirn, $listOrder ); ?>
                    </th>
                    <th class='left'>
						<?php echo JHtml::_( 'searchtools.sort', 'COM_MODERN_TOURS_RESERVATIONS_REGISTERED', 'a.`registered`', $listDirn, $listOrder ); ?>
                    </th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td colspan="<?php echo isset( $this->items[0] ) ? count( get_object_vars( $this->items[0] ) ) : 12; ?>">
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

						<?php if ( isset( $this->items[0]->ordering ) ) : ?>
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
                                           value="<?php echo $item->ordering; ?>" class="width-20 text-area-order "/>
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
						<?php endif; ?>
                        <td>
	                        <?php if ( $canEdit ) : ?>
                                <a href="<?php echo JRoute::_( 'index.php?option=com_modern_tours&task=reservation.edit&id=' . (int) $item->id ); ?>">
			                        <?php echo $item->id; ?></a>
	                        <?php else : ?>
		                        <?php echo $item->id; ?>
	                        <?php endif; ?>
                        </td>
                        <td>
		                    <?php echo $item->status; ?>
                        </td>
                        <td>
		                    <?php echo $item->assets_id; ?>
                        </td>
                        <td>
		                    <?php echo $item->userData; ?>
                        </td>
                        <td>
		                    <?php echo $item->email; ?>
                        </td>
                        <td>
                            <div class="attendees-block">
	                            <?php if($item->travellersData): ?>
		                            <?php echo $item->travellersData; ?>
                                    <div class="reveal">
                                        <div class="arrow-down"></div><?php echo JText::_( 'REVEAL' ); ?>
                                    </div>
                                <?php else: ?>
		                            <?php echo JText::_( 'NO_TRAVELLERS_DATA' ); ?>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
		                    <?php echo $item->adults; ?>
                        </td>
                        <td>
		                    <?php echo $item->children; ?>
                        </td>
                        <td>
		                    <?php echo $item->price; ?>
                        </td>
                        <td>
		                    <?php echo $item->paid_deposit; ?>
                        </td>
                        <td>
		                    <?php echo $item->user_id; ?>
                        </td>
                        <td>
		                    <?php echo $item->date; ?>
                        </td>
                        <td>
							<?php echo $item->registered; ?>
                        </td>
                    </tr>
				<?php endforeach; ?>
                </tbody>
            </table>

            <input type="hidden" name="task" value=""/>
            <input type="hidden" name="boxchecked" value="0"/>
            <input type="hidden" name="list[fullorder]" value="<?php echo $listOrder; ?> <?php echo $listDirn; ?>"/>
			<?php echo JHtml::_( 'form.token' ); ?>
        </div>
            </div>

</form>
<script>
    window.toggleField = function (id, task, field) {

        var f = document.adminForm, i = 0, cbx, cb = f[id];

        if (!cb) return false;

        while (true) {
            cbx = f['cb' + i];

            if (!cbx) break;

            cbx.checked = false;
            i++;
        }

        var inputField = document.createElement('input');

        inputField.type = 'hidden';
        inputField.name = 'field';
        inputField.value = field;
        f.appendChild(inputField);

        cb.checked = true;
        f.boxchecked.value = 1;
        window.submitform(task);

        return false;
    };
</script>
<?php
/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jonas <labasas@gmail.com> - http://www.modernjoomla.com
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', '.multipleTags', null, array('placeholder_text_multiple' => JText::_('JOPTION_SELECT_TAG')));
JHtml::_('formbehavior.chosen', '.multipleCategories', null, array('placeholder_text_multiple' => JText::_('JOPTION_SELECT_CATEGORY')));
JHtml::_('formbehavior.chosen', '.multipleAccessLevels', null, array('placeholder_text_multiple' => JText::_('JOPTION_SELECT_ACCESS')));
JHtml::_('formbehavior.chosen', '.multipleAuthors', null, array('placeholder_text_multiple' => JText::_('JOPTION_SELECT_AUTHOR')));
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . '/media/com_modern_tours/css/modern_tours.css');

$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_modern_tours');
$saveOrder = $listOrder == 'a.`ordering`';
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_modern_tours&task=assets.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'assetList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
?>

<script type="text/javascript">
    function tableOrdering( order, dir, task )
    {
        var form = document.adminForm;

        form.filter_order.value = order;
        form.filter_order_Dir.value = dir;
        document.adminForm.submit( task );
    }
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
	});

</script>

<?php
//Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar))
{
	$this->sidebar .= $this->extra_sidebar;
}
?>

<form action="<?php echo JRoute::_('index.php?option=com_modern_tours&view=assets'); ?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>
			<div class="table-contaner">
			<h4><?php echo JText::_('TOURS_TITLE'); ?></h4>
                <table class="table table-striped" id="assetList">
                <thead>
                <tr>
					<?php if (isset($this->items[0]->ordering)): ?>
                        <th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', '', 'a.`ordering`', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
                        </th>
					<?php endif; ?>
                    <th width="1%" class="hidden-phone">
                        <input type="checkbox" name="checkall-toggle" value=""
                               title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
                    </th>
					<?php if (isset($this->items[0]->state)): ?>
                        <th width="1%" class="nowrap center">
							<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.`state`', $listDirn, $listOrder); ?>
                        </th>
					<?php endif; ?>

                    <th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'COM_MODERN_TOURS_ASSETS_ID', 'a.`id`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'COM_MODERN_TOURS_ASSETS_TITLE', 'a.`title`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'COM_MODERN_TOURS_ASSETS_CATEGORY', 'a.`category`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'COM_MODERN_TOURS_ASSETS_LOCATION', 'a.`location`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'COM_MODERN_TOURS_ASSETS_MAX_PEOPLE', 'a.`max_people`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
		                <?php echo JHtml::_('searchtools.sort',  'USER_DATA_FIELDS', 'a.`max_people`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
		                <?php echo JHtml::_('searchtools.sort',  'COM_MODERN_TOURS_LANGUAGE', 'a.`lang`', $listDirn, $listOrder); ?>
                    </th>
                </tr>
                </thead>
				<tfoot>
				<?php
				if (isset($this->items[0]))
				{
					$colspan = count(get_object_vars($this->items[0]));
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
				<?php foreach ($this->items as $i => $item) :
					$ordering   = ($listOrder == 'a.ordering');
					$canCreate  = $user->authorise('core.create', 'com_modern_tours');
					$canEdit    = $user->authorise('core.edit', 'com_modern_tours');
					$canCheckin = $user->authorise('core.manage', 'com_modern_tours');
					$canChange  = $user->authorise('core.edit.state', 'com_modern_tours');
					?>
                    <tr class="row<?php echo $i % 2; ?>">

						<?php if (isset($this->items[0]->ordering)) : ?>
                            <td class="order nowrap center hidden-phone">
								<?php if ($canChange) :
									$disableClassName = '';
									$disabledLabel    = '';

									if (!$saveOrder) :
										$disabledLabel    = JText::_('JORDERINGDISABLED');
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
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
                        </td>
						<?php if (isset($this->items[0]->state)): ?>
                            <td class="center">
								<?php echo JHtml::_('jgrid.published', $item->state, $i, 'assets.', $canChange, 'cb'); ?>
                            </td>
						<?php endif; ?>

                        <td>

							<?php echo $item->id; ?>
                        </td>
                        <td>
                                <a href="<?php echo JRoute::_('index.php?option=com_modern_tours&task=asset.edit&id='.(int) $item->id); ?>">
                                    <?php echo $this->escape($item->title); ?></a>
                        </td>
                        <td>
							<?php echo $item->category; ?>
                        </td>				<td>

							<?php echo $item->location; ?>
                        </td>
                        <td>
							<?php echo $item->max_people; ?>
                        </td>
                        <td>
		                    <?php echo $item->field; ?>
                        </td>
                        <td>
		                    <?php echo JLayoutHelper::render('joomla.content.language', $item); ?>
                        </td>
                    </tr>
				<?php endforeach; ?>
                </tbody>
                </table>

			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
			<?php echo JHtml::_('form.token'); ?>
            </div>
			</div>
    </div>

</form>



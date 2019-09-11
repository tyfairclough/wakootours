<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Modern_tours
 * @author     Jonas <jonasjov2@gmail.com>
 * @copyright  2016 Jonas
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
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
	$saveOrderingUrl = 'index.php?option=com_modern_tours&task=locations.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'locationList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
?>

<form action="<?php echo JRoute::_('index.php?option=com_modern_tours&view=locations'); ?>" method="post"
      name="adminForm" id="adminForm">	<?php if (!empty($this->sidebar)): ?>
    <div id="j-main-container" class="span10">
		<?php else : ?>
        <div id="j-main-container">
			<?php endif; ?>
            <div class="table-contaner">
                <h4><?php echo JText::_('LOCATIONS_TITLE'); ?></h4>
                <table class="table table-striped" id="locationList">
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
				<?php echo JHtml::_('searchtools.sort',  'COM_MODERN_TOURS_LOCATIONS_ID', 'a.`id`', $listDirn, $listOrder); ?>
				</th>
                <th class='left'>
                    <?php echo JHtml::_('searchtools.sort',  'COM_MODERN_TOURS_LOCATIONS_TITLE', 'a.`title`', $listDirn, $listOrder); ?>
                </th>
                <th class='left'>
                    <?php echo JHtml::_('searchtools.sort',  'COM_MODERN_TOURS_FORM_LBL_ASSET_DESCRIPTION', 'a.`description`', $listDirn, $listOrder); ?>
                </th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_MODERN_TOURS_LOCATIONS_IMAGE', 'a.`image`', $listDirn, $listOrder); ?>
				</th>
                <th class='left'>
                    <?php echo JHtml::_('searchtools.sort',  'COM_MODERN_TOURS_LANGUAGE', 'a.`lang`', $listDirn, $listOrder); ?>
                </th>

				</tr>
				</thead>
				<tfoot>
				<tr>
					<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
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
								<?php echo JHtml::_('jgrid.published', $item->state, $i, 'locations.', $canChange, 'cb'); ?>
</td>
						<?php endif; ?>

										<td>

					<?php echo $item->id; ?>
				</td>				<td>
                            <a href="<?php echo JRoute::_('index.php?option=com_modern_tours&task=location.edit&id='.(int) $item->id); ?>">
								<?php echo $this->escape($item->title); ?></a>

				</td>
                <td>
                    <?php echo $item->description; ?>
                </td>
                <td>
                    <img width="50" src="<?php echo $item->image ? JURI::root(). $item->image : ''; ?>"/>
				</td>
                <td>
                    <?php echo JLayoutHelper::render('joomla.content.language', $item); ?>
                </td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="boxchecked" value="0"/>
            <input type="hidden" name="list[fullorder]" value="<?php echo $listOrder; ?> <?php echo $listDirn; ?>"/>
			<?php echo JHtml::_('form.token'); ?>
		</div>
</form>
<script>
    window.toggleField = function (id, task, field) {

        var f = document.adminForm, i = 0, cbx, cb = f[ id ];

        if (!cb) return false;

        while (true) {
            cbx = f[ 'cb' + i ];

            if (!cbx) break;

            cbx.checked = false;
            i++;
        }

        var inputField   = document.createElement('input');

        inputField.type  = 'hidden';
        inputField.name  = 'field';
        inputField.value = field;
        f.appendChild(inputField);

        cb.checked = true;
        f.boxchecked.value = 1;
        window.submitform(task);

        return false;
    };
</script>
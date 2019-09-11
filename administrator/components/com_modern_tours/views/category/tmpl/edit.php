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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');
$id = JFactory::getApplication()->input->get('id');
$title = ($id ? JText::_('EDIT_CATEGORY') : JText::_('ADD_CATEGORY'));
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet( JURI::root() . 'media/com_modern_tours/css/modern_tours.css' );
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	});

	Joomla.submitbutton = function (task) {
		if (task == 'category.cancel') {
			Joomla.submitform(task, document.getElementById('category-form'));
		}
		else {
			
			if (task != 'category.cancel' && document.formvalidator.isValid(document.id('category-form'))) {
				
				Joomla.submitform(task, document.getElementById('category-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>
<div id="j-main-container" class="span10">

<form
	action="<?php echo JRoute::_('index.php?option=com_modern_tours&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="category-form" class="form-validate">
    <div class="row-fluid">
        <div class="span12 form-horizontal">
            <h1 class="slim"><?php echo $title; ?></h1>
				<fieldset class="adminform">

									<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php echo $this->form->renderField('created_by'); ?>
				<?php echo $this->form->renderField('modified_by'); ?>
                <?php echo $this->form->renderField('title'); ?>
                <?php echo $this->form->renderField('alias'); ?>
                <?php echo $this->form->renderField('description'); ?>
                <?php echo $this->form->renderField('image'); ?>
                <?php echo $this->form->renderField('language'); ?>

				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>

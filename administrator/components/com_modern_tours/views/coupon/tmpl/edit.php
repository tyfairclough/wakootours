<?php
/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jonas <jonasjov2@gmail.com> - http://www.modernjoomla.com
 */
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root(). 'media/com_modern_tours/css/modern_tours.css');
$id = JFactory::getApplication()->input->get('id');
$title = ($id ? JText::_('EDIT_COUPON') : JText::_('ADD_COUPON'));
?>
<style>
	#myTabTabs { display:none; } .control-group { width: 58%; background: white; padding: 15px; border-radius: 5px; }
</style>
<script type="text/javascript">
	js = jQuery.noConflict();

	function randomstring(L){
		var s= '';
		var randomchar=function(){
			var n= Math.floor(Math.random()*62);
			if(n<10) return n; //1-10
			if(n<36) return String.fromCharCode(n+55); //A-Z
			return String.fromCharCode(n+61); //a-z
		}
		while(s.length< L) s+= randomchar();
		return s;
	}

	js(document).ready(function() {
		js('#generate').click(function() {
			var str = randomstring(12);
			js('#jform_code').val(str);
		});
	});

	Joomla.submitbutton = function(task)
	{
		if (task == 'coupon.cancel') {
			Joomla.submitform(task, document.getElementById('coupon-form'));
		}
		else {

			if (task != 'coupon.cancel' && document.formvalidator.isValid(document.id('coupon-form'))) {

				Joomla.submitform(task, document.getElementById('coupon-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>
<div id="j-main-container" class="span10">
	<form action="<?php echo JRoute::_('index.php?option=com_modern_tours&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="coupon-form" class="form-validate">
		<div class="form-horizontal">
			<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_MODERN_TOURS_TITLE_COUPON', true)); ?>
			<div class="row-fluid">
				<div class="span12 form-horizontal">
					<h1 class="slim"><?php echo $title; ?></h1>
					<fieldset class="adminform">
						<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
						<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
						<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
						<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
						<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

						<?php if(empty($this->item->created_by)){ ?>
							<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

						<?php }
						else{ ?>
							<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

						<?php } ?>			<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('title'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('code'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('code'); ?> <span id="generate"><?php echo JText::_('GENERATE'); ?></span></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('start'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('start'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('end'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('end'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('couponsnumber'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('couponsnumber'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('pricepercent'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('pricepercent'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('pricetype'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('pricetype'); ?></div>
						</div>


					</fieldset>
				</div>
			</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>



			<?php echo JHtml::_('bootstrap.endTabSet'); ?>

			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>

		</div>
	</form>
</div>
<?php
/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   Copyright (C) 2015. All rights reserved. Unikalus team.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jonas <jonasjov2@gmail.com> - http://modernjoomla.com
 */
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

$id = JFactory::getApplication()->input->get('id');
$display = $id ? 'none' : 'block';
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root(). 'media/com_modern_tours/css/modern_tours.css');
$document->addStyleSheet(JURI::root() . 'media/com_modern_tours/css/form-builder.min.css');
$document->addStyleSheet(JURI::root() . 'media/com_modern_tours/js/icon-picker/fontawesome-iconpicker.min.css');

//$document->addStyleSheet(JURI::root() . 'media/com_modern_tours/css/formBuilder.css');

$inputCookie  = JFactory::getApplication()->input->cookie;
$translate    = $inputCookie->get($name = 'translate', $defaultValue = null);
$inputCookie->set('translate', null, time() - 1);
if($translate) {
	$document->addStyleDeclaration('
	input.option-label, input[name=label], input.option-reponsibility {
	    background: #e8ffe1 !important;
	    border: 1px solid #d2e8cb !important;
	}
' );
}
?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
<div id="selection" style="display: <?php echo $display; ?>">
    <div class="sel">
        <h1 class="hchoice">
            <?php echo JText::_( 'USER_FIELDS_INFORMATION' ); ?>
        </h1>
        <div class="choice">
            <a class="user-fields"><?php echo JText::_( 'USER_FIELDS' ); ?></a>
            <a class="travellers-fields"><?php echo JText::_( 'TRAVELLERS_FIELDS' ); ?></a>
            <a class="email-fields"><?php echo JText::_( 'EMAIL_FIELDS' ); ?></a>
        </div>
    </div>
</div>
<script src="../media/com_modern_tours/js/jquery-ui.min.js"></script>
<script src="../media/com_modern_tours/js/form-builder.js"></script>
<script src="../media/com_modern_tours/js/form-render.min.js"></script>
<script src="../media/com_modern_tours/js/icon-picker/fontawesome-iconpicker.min.js"></script>
<script src="../media/com_modern_tours/js/icon-picker/iconpicker.js"></script>
<script src="../media/com_modern_tours/js/icon-picker/jquery.ui.pos.js"></script>
<script src="../media/com_modern_tours/js/icon-picker/license.js"></script>

<script type="text/javascript">
    var title = '<?php echo $this->item->title; ?>';
    var id = '<?php echo $id; ?>';
    var translate = '<?php echo $translate; ?>';
    var currency = 'eur';
    var REQUIRED = '<?php echo JText::_('REQUIRED'); ?>';
    var LABEL = '<?php echo JText::_('LABEL'); ?>';
    var PLACEHOLDER = '<?php echo JText::_('PLACEHOLDER'); ?>';
    var FIELD_NAME = '<?php echo JText::_('FIELD_NAME'); ?>';
    var REMOVE_FIELD="<?php echo JText::_("REMOVE_FIELD"); ?>";
    var EDIT="<?php echo JText::_("EDIT"); ?>";
    var CLOSE = '<?php echo JText::_('CLOSE'); ?>';
    var AS_SERVICE = '<?php echo JText::_('AS_SERVICE'); ?>';
    var SEPARATE_TIMESLOTS = '<?php echo JText::_('SEPARATE_TIMESLOTS'); ?>';
    var SHOW_PRICES = '<?php echo JText::_('SHOW_PRICES'); ?>';
    var PDF_DESC = '<?php echo JText::_('PDF_DESC'); ?>';
    var MULTIPLIER = '<?php echo JText::_('MULTIPLIER'); ?>';
    var TEXT_FIELD = '<?php echo JText::_('TEXT_FIELD'); ?>';
    var EMAIL_FIELD= '<?php echo JText::_('EMAIL_FIELD'); ?>';
    var TEXTAREA = '<?php echo JText::_('TEXTAREA'); ?>';
    var SPECIALISTS = '<?php echo JText::_('SPECIALISTS'); ?>';
    var SELECT_FIELD= '<?php echo JText::_('SELECT_FIELD'); ?>';
    var RADIO_GROUP= '<?php echo JText::_('RADIO_GROUP'); ?>';
    var CHECKBOX = '<?php echo JText::_('CHECKBOX'); ?>';
    var DATE_FIELD= '<?php echo JText::_('DATE_FIELD'); ?>';
    var HEADER = '<?php echo JText::_('HEADER'); ?>';
    var HIDDEN_FIELD = '<?php echo JText::_('HIDDEN_FIELD'); ?>';
    var PARAGRAPH = '<?php echo JText::_('PARAGRAPH'); ?>';
    var NUMBER = '<?php echo JText::_('NUMBER'); ?>';
    var PRICE = '<?php echo JText::_('PRICE'); ?>';
    var DESC_PDF = '<?php echo JText::_('DESC_PDF'); ?>';
    var DESC_SERV = '<?php echo JText::_('DESC_SERV'); ?>';
    var IMG_LINK = '<?php echo JText::_('IMG_LINK'); ?>';
    var TIMETABLE = '<?php echo JText::_('TIMETABLE'); ?>';
    var VALUE = '<?php echo JText::_('VALUE'); ?>';
    var RESPONSIBILITY = '<?php echo JText::_('RESPONSIBILITY'); ?>';
    var CONTENT = '<?php echo JText::_('CONTENT'); ?>';
    var DRAG_FIELD = '<?php echo JText::_('DRAG_FIELD'); ?>';
    var MULTIPLE_FILES = '<?php echo JText::_('MULTIPLE_FILES'); ?>';
    var SELECT_AGENTS = '<?php echo JText::_('SELECT_AGENTS'); ?>';
    var ENTER_FORM_NAME = '<?php echo JText::_('ENTER_FIELD_FORM_NAME'); ?>';
    var FIELD_WIDTH = '<?php echo JText::_('FIELD_WIDTH'); ?>';
    var FIELD_MARGIN_RIGHT = '<?php echo JText::_('FIELD_MARGIN_RIGHT'); ?>';
    var FIELD_MARGIN_LEFT = '<?php echo JText::_('FIELD_MARGIN_LEFT'); ?>';
    var FIELD_ICON = '<?php echo JText::_('FIELD_ICON'); ?>';
    var EDIT_FIELD_STYLING = '<?php echo JText::_('EDIT_FIELD_STYLING'); ?>';

    jQuery(document).ready(function() {
        jQuery('.fld-iconx').iconpicker({placement: 'right'});
    });

    Joomla.submitbutton = function(task)
    {
        if (task == 'modernform.cancel') {
            Joomla.submitform(task, document.getElementById('modernform-form'));
        }
        else {
            if(check() && isFilled()) {
                setTimeout(function () {
                    document.getElementById('frmb-0-view-data').click();
                    var data = document.getElementsByClassName('xml')[0].textContent;
                    document.getElementById('jform_formdata').value = data;
                    if (!document.getElementById('jform_title').value) {
                        title = prompt("<?php echo JText::_( 'ENTER_FIELD_FORM_NAME' ); ?>");
                        document.getElementById('jform_title').value = title;
                    }
                    if (title) {
                        if (task != 'modernform.cancel' && document.formvalidator.isValid(document.id('modernform-form'))) {
                            Joomla.submitform(task, document.getElementById('modernform-form'));
                        }
                        else {
                            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
                        }
                    }
                }, 400);
            }
        }
    }
</script>
<div id="j-main-container" class="span10">
    <textarea id="fb-template"><?php echo $this->form->getValue('formdata'); ?></textarea>
</div>
<div id="j-main-container" class="span12">
<form action="<?php echo JRoute::_('index.php?option=com_modern_tours&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm"  id="modernform-form" class="form-validate" style="display: none;">
    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_MODERN_TOURS_TITLE_MODERNFORM', true)); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal">
                <fieldset class="adminform">

				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />
				<?php } else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
				<?php } ?>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('title'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('state'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('fields'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('fields'); ?></div>
                </div>
                <div class="control-group">
                    <input type="text" name="jform[formdata]" id="jform_formdata" value='<?php echo $this->form->getValue('formdata'); ?>' class="inputbox" size="40">
	                <?php echo $this->form->getInput('user_fields'); ?>
                </div>
                </fieldset>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
        <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
        <input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
        <input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
        <input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
        <input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>
    </div>


<?php
/**
 * @version     2.0.0
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
JHtml::_('behavior.keepalive');
$document = JFactory::getDocument();
$document->addStyleSheet('../media/com_modern_tours/css/invoice.css');
$document->addStyleSheet(JURI::root(). 'media/com_modern_tours/css/modern_tours.css');
$id = JFactory::getApplication()->input->get('id');
?>
<style>
    body .container-fluid { padding: 0; }
    body.admin.com_modern_tours.view-invoice { background: url("<?php echo JURI::root() . 'media/com_modern_tours/img/invoicebg.png'; ?>"); }
</style>
<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        check();
        if (task == 'invoice.save') {
            var currency = document.getElementById('currency').value;
            document.getElementById('jform_currency').value = currency;
            var data = document.getElementById('invoice').innerHTML;
            document.getElementById('jform_template').value = data;
        }
        if (task == 'invoice.cancel') {
            Joomla.submitform(task, document.getElementById('invoice-form'));
        }
        else {
            if (task != 'invoice.cancel' && document.formvalidator.isValid(document.id('invoice-form'))) {
                Joomla.submitform(task, document.getElementById('invoice-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
    jQuery(document).ready(function() {

        var url = '<?php echo JURI::base(); ?>';
        var ENTER_CURRENCY = '<?php echo JText::_( 'ENTER_CURRENCY' ); ?>';
        jQuery('#logo img').attr('src', '../media/com_modern_tours/img/logo.png');

        jQuery('div#toolbar-show').click(function() {
            var elem = jQuery('.explanations');
            if(!elem.is(':visible'))
            {
                elem.slideDown(600);
            }
            else
            {
                elem.slideUp(600);
            }
        });


        jQuery('.param').click(function(){
            copyToClipboard(jQuery(this));
        });

        function copyToClipboard(element) {
            var $temp = jQuery("<input>");
            jQuery("body").append($temp);
            $temp.val(jQuery(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
        }

        window.check = function() {
            jQuery('#logo img').attr('src', url + '../media/com_modern_tours/img/logo.png');
        }

        jQuery('<div class="cur"><input type="text" id="currency" value="<?php echo $this->template->currency; ?>" name="currency"/><label for="curency">' + ENTER_CURRENCY + '</label></div>').insertAfter('#toolbar-cancel');

        jQuery('div[contenteditable="true"]').keypress(function (event) {

            if (event.which != 13)
                return true;

            var docFragment = document.createDocumentFragment();

            //add a new line
            var newEle = document.createTextNode('\n');
            docFragment.appendChild(newEle);

            //add the br, or p, or something else
            newEle = document.createElement('br');
            docFragment.appendChild(newEle);

            //make the br replace selection
            var range = window.getSelection().getRangeAt(0);
            range.deleteContents();
            range.insertNode(docFragment);

            //create a new range
            range = document.createRange();
            range.setStartAfter(newEle);
            range.collapse(true);

            //make the cursor there
            var sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);

            return false;
        });
    });
</script>

<div class="container of-explanations">
    <div class="explanations">
		<?php echo $this->variables; ?>
    </div>
</div>


<form action="<?php echo JRoute::_('index.php?option=com_modern_tours&layout=edit&id=' . $id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="invoice-form" class="form-validate">
    <div id="invoice" contenteditable="true">
        <?php echo $this->template->template; ?>
    </div>
    <input type="hidden" name="jform[id]" value="<?php echo $id; ?>" />
    <input type="hidden" name="jform[currency]" id="jform_currency" value="<?php echo $this->template->currency; ?>" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="jform[template]" id="jform_template" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>


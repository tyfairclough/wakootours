<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Modern_tours
 * @author     Jonas Jovaišas <support@modernjoomla.com>
 * @copyright  2018 Jonas Jovaišas
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
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
$title = ($id ? JText::_('EDIT_EMAIL') : JText::_('ADD_EMAIL'));
?>
<script type="text/javascript">
	jQuery(document).ready(function() {
        jQuery('.show-explanation').click(function() {
            var elem = jQuery('.extra-hide');
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
	});

	Joomla.submitbutton = function (task) {
		if (task == 'toursemail.cancel') {
			Joomla.submitform(task, document.getElementById('toursemail-form'));
		}
		else {
			
			if (task != 'toursemail.cancel' && document.formvalidator.isValid(document.id('toursemail-form'))) {
				
				Joomla.submitform(task, document.getElementById('toursemail-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>
<div id="j-main-container" class="span10">
<form action="<?php echo JRoute::_('index.php?option=com_modern_tours&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="toursemail-form" class="form-validate">
	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'user_email')); ?>


		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'user_email', JText::_('USER_EMAIL')); ?>
            <div class="row-fluid">
                <div class="span10 form-horizontal">
                    <h1 class="slim"><?php echo JText::_( 'LEAVE_BLANK_MESSAGES_USER' ); ?></h1>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo JText::_( 'AVAILABLE_VARIABLES' ); ?>
                            <small class="small-desc"><?php echo JText::_( 'CLICK_FIELD_TO_CLIPBOARD' ); ?></small>
                        </div>
                        <div class="controls">
                            <div id="available-variables">
			                    <?php echo $this->variables; ?>
                            </div>
                        </div>
                    </div>

                    <fieldset class="adminform">
                        <?php echo $this->form->renderField('user_paid_message', 'params'); ?>
                        <?php echo $this->form->renderField('user_reserved_message', 'params'); ?>
                        <?php echo $this->form->renderField('user_waiting_message', 'params'); ?>
                        <?php echo $this->form->renderField('user_partially_paid_message', 'params'); ?>
                    </fieldset>
                </div>
            </div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'admin_email', JText::_('ADMIN_EMAIL_SETTINGS')); ?>
            <div class="row-fluid">
                <div class="span10 form-horizontal">
                    <h1 class="slim"><?php echo JText::_( 'LEAVE_BLANK_MESSAGES_ADMIN' ); ?></h1>
                    <div class="control-group">
                        <div class="control-label">
			                <?php echo JText::_( 'AVAILABLE_VARIABLES' ); ?>
                                <small class="small-desc"><?php echo JText::_( 'CLICK_FIELD_TO_CLIPBOARD' ); ?></small>
                        </div>
                        <div class="controls">
                            <div id="available-variables">
		                        <?php echo $this->variables; ?>
                            </div>
                        </div>
                    </div>
                    <fieldset class="adminform">
                        <?php echo $this->form->renderField('admin_email', 'params'); ?>
                        <?php echo $this->form->renderField('admin_paid_message', 'params'); ?>
                        <?php echo $this->form->renderField('admin_reserved_message', 'params'); ?>
                        <?php echo $this->form->renderField('admin_waiting_message', 'params'); ?>
                        <?php echo $this->form->renderField('admin_partially_paid_message', 'params'); ?>
                    </fieldset>
                </div>
            </div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>

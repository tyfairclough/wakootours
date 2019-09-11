<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Modern_tours
 * @author     Modernjoomla.com <support@modernjoomla.com>
 * @copyright  modernjoomla.com
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
$document->addScript( '../media/com_modern_tours/js/form-render.min.js' );

$id = JFactory::getApplication()->input->get('id');
$title = ($id ? JText::_('EDIT_RESERVATION') : JText::_('ADD_RESERVATION'));
$id = $this->form->getValue('fields_id');
$formData = $id ? JFactory::getDbo()->setQuery('SELECT formdata FROM #__modern_tours_forms WHERE id = ' . $id)->loadResult() : '';
?>
<script type="text/javascript">
    var translations = {
        'NO_FORM_CREATED': '<?php echo JText::_( "NO_FORM_CREATED" ); ?>'
    };
	jQuery(document).ready(function ($) {
        var userForm = '<?php echo $formData; ?>';
        var userData = JSON.parse('<?php echo $this->form->getValue('userData'); ?>');
        $('#user-data-form').formRender({'formData': userForm, dataType: 'xml'});

        $('.rendered-form input, .rendered-form select').each(function( i, val ) {
            var id = jQuery(this).attr('id'), type = $(this).attr('type');
            var newValue = userData[id];

            if(type == 'select')
            {
                $('#' + id).val(newValue);
            }
            if(type == 'input')
            {
                $('#' + id).val(newValue);
            }
            $('#' + id).val(newValue);

        });

        $('#toolbar-apply').hover(function() {
            var json = $('#userForm').serializeArray();
            var jsonObj = {};
            jQuery.map(json , function (n, i) {
                jsonObj[n.name] = n.value;
            });
            $('#jform_userData').val(JSON.stringify(jsonObj));
        });

    });

	Joomla.submitbutton = function (task) {
		if (task == 'reservation.cancel') {
			Joomla.submitform(task, document.getElementById('reservation-form'));
		}
		else {
			if (task != 'reservation.cancel' && document.formvalidator.isValid(document.id('reservation-form'))) {
				
				Joomla.submitform(task, document.getElementById('reservation-form'));
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
	method="post" enctype="multipart/form-data" name="adminForm" id="reservation-form" class="form-validate">

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'main')); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'main', JText::_('EDIT_RESERVATION')); ?>

        <div class="row-fluid">
            <div class="span12 form-horizontal">
                <h1 class="slim"><?php echo $title; ?></h1>
				<fieldset class="adminform">
                <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php echo $this->form->renderField('created_by'); ?>
				<?php echo $this->form->renderField('modified_by'); ?>
                <?php echo $this->form->renderField('status'); ?>
				<?php echo $this->form->renderField('assets_id'); ?>
				<?php echo $this->form->renderField('email'); ?>
                <?php echo $this->form->renderField('price'); ?>
                <?php echo $this->form->renderField('adults'); ?>
                <?php echo $this->form->renderField('children'); ?>
				<input type="hidden" name="jform[additional]" value="<?php echo $this->item->additional; ?>" />
                <?php echo $this->form->renderField('date'); ?>
					<?php echo $this->form->renderField('registered'); ?>
					<?php echo $this->form->renderField('userData'); ?>
				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('form.token'); ?>

</form>

    <?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'fields', JText::_('COM_MODERN_TOURS_RESERVATIONS_USERDATA')); ?>

        <div class="row-fluid">
            <div class="span12 form-horizontal">
                <h1 class="slim"><?php echo JText::_( 'COM_MODERN_TOURS_RESERVATIONS_USERDATA' ); ?></h1>
                <form id="userForm">
                    <div id="user-data-form"></div>
                </form>
            </div>
        </div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>

	</div>

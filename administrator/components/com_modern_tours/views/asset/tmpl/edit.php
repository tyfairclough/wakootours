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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
$id = JFactory::getApplication()->input->get('id');
$discount = $this->form->getValue('discount');
$reserved = $this->form->getValue('reserved');
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root(). 'media/com_modern_tours/css/modern_tours.css');
//$document->addScript(JURI::root(). 'media/com_modern_tours/js/script.js');
$document->addStyleSheet('../media/com_modern_tours/css/rules.css');
$document->addStyleSheet('https://raw.githubusercontent.com/mistic100/jQuery-QueryBuilder/master/dist/css/query-builder.default.min.css');
$document->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.10.0/ui/trumbowyg.min.css');

$app = JFactory::getApplication();
$params = JComponentHelper::getParams('com_modern_tours');
$api_key = $params->get('api_key');

$filesTypes = 'gif,jpg,jpeg,png';
$jsFilesTypes = str_replace(',', '|', $filesTypes);
$uploadedFiles = $this->form->getValue('imageFiles') ? $this->form->getValue('imageFiles') : '{}';
$uploadedFilesObject = json_decode($uploadedFiles);

$itirenary = isset($this->form->getValue( 'params')->itirenary) ? json_encode($this->form->getValue( 'params')->itirenary) : '{}';
?>
<script>
    var DELETE_ITIRENARY_DAY='<?php echo JText::_('DELETE_ITIRENARY_DAY'); ?>';
    var ITIRENARY_DAY_TITLE='<?php echo JText::_('ITIRENARY_DAY_TITLE'); ?>';
    var ITIRENARY_DAY_DESCRIPTION='<?php echo JText::_('ITIRENARY_DAY_DESCRIPTION'); ?>';
    var placeholder_includes="<?php echo JText::_("PLACEHOLDER_INCLUDES"); ?>";
    var placeholder_excludes="<?php echo JText::_("PLACEHOLDER_EXCLUDES"); ?>";
</script>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet"/>
<script src="../media/com_modern_tours/js/moment.min.js"></script>
<script src="../media/com_modern_tours/js/underscore-min.js"></script>
<script src="../media/com_modern_tours/js/clndr.min.js"></script>
<script src="../media/com_modern_tours/js/moment-with-locales.js"></script>
<script src="../media/com_modern_tours/js/jquery-ui.min.js"></script>
<script src="../media/com_modern_tours/js/query-builder.min.js"></script>
<script src="../media/com_modern_tours/js/script.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.21/moment-timezone-with-data.js"></script>
<script src="../media/com_modern_tours/js/upload/jquery.ui.widget.js"></script>
<script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<script src="../media/com_modern_tours/js/upload/jquery.iframe-transport.js"></script>
<script src="../media/com_modern_tours/js/upload/jquery.fileupload.js"></script>
<script src="../media/com_modern_tours/js/upload/jquery.fileupload-process.js"></script>
<script src="../media/com_modern_tours/js/upload/jquery.fileupload-image.js"></script>
<script src="../media/com_modern_tours/js/upload/jquery.fileupload-audio.js"></script>
<script src="../media/com_modern_tours/js/upload/jquery.fileupload-video.js"></script>
<script src="../media/com_modern_tours/js/upload/jquery.fileupload-validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.10.0/trumbowyg.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $api_key; ?>&libraries=places"></script>
<script>
    function initialize() {
        var input = document.getElementById('jform_params_map');
        var autocomplete = new google.maps.places.Autocomplete(input);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            console.log(place);
//            document.getElementById('city2').value = place.name;
//            document.getElementById('cityLat').value = place.geometry.location.lat();
//            document.getElementById('cityLng').value = place.geometry.location.lng();
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);
</script>

<style>
    .timecontainer.disable:before { content: "<?php echo JText::_('DISABLED'); ?>"; } .timecontainer:before { content: "<?php echo JText::_('ENABLED'); ?>"; } .slts { /*display: none;*/ } div#clnr.modal { width: 175px; margin-left: -119px; display: block; padding: 13px; z-index: 999999; padding-top: 0; padding-bottom: 6px; } select#msg { width: 100%; margin: 5px 0; } #clnr { display: none; } .day { cursor: pointer; } .generatetimes { margin-bottom: 8px; clear: both; }
</style>
<script type="text/javascript">
    var day = 1, custom = 1, lastdayheader, title, last, month, lastday, lastdaydate, clndrday, bans = {}, time = {}, monthly;
    var disabled = <?php echo ($this->form->getValue('bandates') ? $this->form->getValue('bandates') : '{}'); ?>;
    var hoursJSON = <?php echo ($this->form->getValue('times') ? $this->form->getValue('times') : ' { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0, 6: 0, 7: 0 }'); ?>;
    var newTime = <?php echo ($id ? $id : '""'); ?>;
    var fromTime = <?php echo ($this->form->getValue('fromtime') ? $this->form->getValue('fromtime') : '""'); ?>;
    var userGroups = <?php echo $this->userGroups; ?>;
    var jsonas = <?php echo ($discount ? $discount: '""'); ?>;
    var reserved = <?php echo ($reserved ? $reserved: '[]'); ?>;
</script>
<script>
    <?php echo $this->form->getValue('params')->additional; ?>
    var itirenary = '<?php echo $itirenary; ?>';

    jQuery(document).ready(function() {
        $.reCreateItirenary('itirenary', '#itirenary-fields', itirenary);
    });

    setTimeout(function () {
        var html = jQuery('[for="jform_params_date"]').html();
        jQuery('[for="jform_params_date"]').html(html + '<span class="mark-with-bg">?</span>');
    }, 1000);

    jQuery(function () {
        'use strict';
        // Change this to the location of your server-side upload handler:
        var url = 'index.php?option=com_modern_tours&task=test';
        var deleteURL = 'index.php?option=com_modern_tours&task=delete';

        var uploadButton = jQuery('<button/>')
            .addClass('btn btn-primary')
            .prop('disabled', true)
            .text('Processing...')
            .on('click', function () {
                var $this = jQuery(this),
                    data = $this.data();
                $this
                    .off('click')
                    .text('Abort')
                    .on('click', function () {
                        $this.remove();
                        data.abort();
                    });
                data.submit().always(function () {
                    $this.remove();
                });
            });


        jQuery('body').on('hover', 'div#toolbar-apply', function() {
            eachFile();
        });

        function eachFile() {
            var files = {};
            jQuery('.asset-img').each(function() {
                var path = jQuery(this).attr('file');
                files[path] = path;
            });
            console.log(JSON.stringify(files));
            jQuery('#uploadField').val(JSON.stringify(files));

            console.log(jQuery('#uploadField').val());
        }

        jQuery('body').on('click', '#uploadImages', function() {
            jQuery('#fileupload').click();
        });

        jQuery('body').on('click', '.delete-file', function() {
            var data = 'file=' + jQuery(this).attr('link');
            var thisB = jQuery(this);
            var elem = thisB.parent('.outer-file');
            elem.hide(222);
            jQuery.ajax({
                type: "GET",
                data: data,
                url: deleteURL,
                success: function (response) {
                    var elem = thisB.parent('.outer-file');
                    elem.remove();
//                    deleteFile(thisB.attr('link'));
                }
            });
        });

	    <?php if($this->form->getValue( 'alias' )): ?>
            jQuery('#jform_params_import_booking option[value=<?php echo $this->form->getValue( 'alias' ); ?>]').hide();
        <?php endif; ?>

        jQuery('#fileupload').fileupload({
            url: url,
            dataType: 'json',
            autoUpload: true,
            acceptFileTypes: /(\.|\/)(<?php echo $jsFilesTypes; ?>)$/i,
            maxFileSize: 999000,
            // Enable image resizing, except for Android and Opera,
            // which actually support image resizing, but fail to
            // send Blob objects via XHR requests:
            disableImageResize: /Android(?!.*Chrome)|Opera/
                .test(window.navigator.userAgent),
            previewMaxWidth: 100,
            previewMaxHeight: 100,
            previewCrop: true
        }).on('fileuploadadd', function (e, data) {
            data.context = jQuery('<div/>').appendTo('#files');
            jQuery.each(data.files, function (index, file) {
                var filepath = '<?php echo JURI::root() . 'images/tours/'; ?>' + file.name;
                data.context.addClass('outer-file');
                var content = jQuery('<a target="_blank" href="<?php echo JURI::root() . 'images/tours/'; ?>' + file.name + '"> <div class="asset-img" file="' + file.name + '"></div> </a> <btn class="btn btn-danger delete-file" link="' + file.name + '">x</btn>');
                content.appendTo(data.context);
                setTimeout(function () {
                    content.find('.asset-img').attr('style', 'background-image: url("' + filepath + '");');
                    console.log('now');
                }, 1111);

            });
        }).on('fileuploadprocessalways', function (e, data) {
            var index = data.index,
                file = data.files[index],
                node = jQuery(data.context.children()[index]);
            if (file.preview) {
                node
                    .prepend('<br>')
                    .prepend(file.preview);
            }
            if (file.error) {
                node
                    .append('<br>')
                    .append(jQuery('<span class="text-danger"/>').text(file.error));
            }
            if (index + 1 === data.files.length) {
                data.context.find('button')
                    .text('Upload')
                    .prop('disabled', !!data.files.error);
            }
        }).on('fileuploadprogressall', function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            jQuery('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }).on('fileuploaddone', function (e, data) {
            jQuery.each(data.result.data.files, function (index, file) {
                if (file.url) {
                } else if (file.error) {
                    var error = jQuery('<span class="text-danger"/>').text(file.error);
                    jQuery(data.context.children()[index])
                        .append('<br>')
                        .append(error);
                }
            });
        }).on('fileuploadfail', function (e, data) {
            jQuery.each(data.files, function (index) {
                var error = jQuery('<span class="text-danger"/>').text('File upload failed.');
                jQuery(data.context.children()[index])
                    .append('<br>')
                    .append(error);
            });
        }).prop('disabled', !jQuery.support.fileInput)
            .parent().addClass(jQuery.support.fileInput ? undefined : 'disabled');
    });

</script>
<style>
    div#cale {
        display:none;
        position: absolute;
        width: 400px;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        margin: auto;
        z-index: 9999999;
    }
</style>
<div class="overlay">
    <div class="close">x</div>
</div>
<script type="text/javascript">

    Joomla.submitbutton = function (task) {
        if(!checkRules()) {
            alert('Please fill all fields');
            return false;
        }
        save();
        if (task == 'asset.cancel') {
            Joomla.submitform(task, document.getElementById('asset-form'));
        }
        else {
            if (task != 'asset.cancel' && document.formvalidator.isValid(document.id('asset-form'))) {
                Joomla.submitform(task, document.getElementById('asset-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>
<div id="j-main-container" class="span10">
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

    <form action="<?php echo JRoute::_('index.php?option=com_modern_tours&layout=edit&id=' . (int)$this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="asset-form" class="form-validate">
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('MAIN_INFO')); ?>
    <div class="row-fluid">
        <div class="span12">
            <fieldset class="general">
                <h3><?php echo JText::_( 'MAIN_INFO' ); ?></h3>
	                <?php echo $this->form->renderField('title'); ?>
	                <?php echo $this->form->renderField('alias'); ?>

                <?php echo $this->form->renderField('description'); ?>
	            <?php echo $this->form->renderField('small_description'); ?>
            </fieldset>
        </div>
    </div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'itirenary', JText::_('ITIRENARY')); ?>
    <div class="row-fluid">
        <div class="span12">
            <fieldset class="adminform">
                <h3><?php echo JText::_('ITIRENARY'); ?></h3>
                <small><?php echo JText::_('ITIRENARY_DESC'); ?></small>
                <br>
                <div id="itirenary-fields"></div>
                <div id="itirenary-btn" class="add-field-btn">
                    <?php echo JText::_("ADD_NEW_FIELD"); ?>
                </div>
            </fieldset>
        </div>

    </div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'includes', JText::_('INCLUDES_EXCLUDES')); ?>
    <div class="row-fluid">
        <div class="span12">
            <fieldset class="adminform">
                <div class="width50">
                    <h3><?php echo JText::_('INCLUDES'); ?></h3>
                    <div id="includes-fields"></div>
                    <div id="includes-btn" class="add-field-btn"><?php echo JText::_( 'ADD_NEW_FIELD' ); ?></div>
                </div>

                <div class="width50">
                    <h3><?php echo JText::_('EXCLUDES'); ?></h3>
                    <div id="excludes-fields"></div>
                    <div id="excludes-btn" class="add-field-btn"><?php echo JText::_( 'ADD_NEW_FIELD' ); ?></div>
                </div>
            </fieldset>
        </div>

    </div>
    <?php echo JHtml::_('bootstrap.endTab'); ?>

    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'additional', JText::_('ADDITIONAL_INFO')); ?>
    <div class="row-fluid">
        <div class="span12">
            <fieldset class="adminform">
                <h3><?php echo JText::_('ADDITIONAL_INFO'); ?></h3>

                <div class="additional-separator">
	                <?php echo $this->form->renderField('category'); ?>
	                <?php echo $this->form->renderField('location'); ?>
                </div>

                <div class="additional-separator">
	                <?php echo $this->form->renderField('language'); ?>
	                <?php echo $this->form->renderField('state'); ?>
                </div>

                <div class="additional-separator">
	                <?php echo $this->form->renderField('departure', 'params'); ?>
	                <?php echo $this->form->renderField('destination', 'params'); ?>
                </div>

                <div class="additional-separator">
	                <?php echo $this->form->renderField('length', 'params'); ?>
	                <?php echo $this->form->renderField('duration', 'params'); ?>
                </div>

                <div class="additional-separator">
	                <?php echo $this->form->renderField('max_people'); ?>
	                <?php echo $this->form->renderField('availability', 'params'); ?>
	                <?php echo $this->form->renderField('arrive', 'params'); ?>
                </div>

                <div class="additional-separator">
	                <?php echo $this->form->renderField('related'); ?>
                </div>
            </fieldset>
        </div>

    </div>
    <?php echo JHtml::_('bootstrap.endTab'); ?>

    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'images', JText::_('IMAGES')); ?>
        <div class="row-fluid">
            <div class="span12">
                <fieldset class="adminform">
                    <h3><?php echo JText::_('ASSET_IMAGES'); ?></h3>
	                <?php echo $this->form->renderField('cover'); ?>

                    <div id="uploadImages"><?php echo JText::_( 'UPLOAD_IMAGES' ); ?></div>
                    <input type="text" name="jform[imageFiles]" id="uploadField" value='<?php echo $uploadedFiles; ?>' />
                    <div id="files" class="files">
		                <?php foreach($uploadedFilesObject as $key => $file): ?>
                            <div class="outer-file">
                                <a target="_blank" href="<?php echo JURI::root() . 'images/tours/' . $file; ?>">
                                    <div class="asset-img" file="<?php echo $file; ?>" style="background-image: url(<?php echo JURI::root() . 'images/tours/' . $file; ?>);"></div>
                                </a>
                                <btn class="btn btn-danger delete-file" link="<?php echo $file; ?>">x</btn>
                            </div>
		                <?php endforeach; ?>
                    </div>

<!--                    <div id="cover-slider">-->
<!--	                    --><?php //echo $this->form->renderField('cover_slider', 'params'); ?>
<!--	                    --><?php //echo $this->form->renderField('image1', 'params'); ?>
<!--	                    --><?php //echo $this->form->renderField('image2', 'params'); ?>
<!--	                    --><?php //echo $this->form->renderField('image3', 'params'); ?>
<!--	                    --><?php //echo $this->form->renderField('image4', 'params'); ?>
<!--	                    --><?php //echo $this->form->renderField('image5', 'params'); ?>
<!--                    </div>-->
                </fieldset>
            </div>
        </div>
    <?php echo JHtml::_('bootstrap.endTab'); ?>


    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'times', JText::_('WORKING_TIMES')); ?>
    <div class="row-fluid">
        <div class="span12">
            <fieldset class="adminform">
                <div class="availablity-intervals">
                    <h3><?php echo JText::_( 'AVAILABILITY_INTERVAL' ); ?></h3>
                    <div class="availability-interval interval1">
	                    <label><?php echo JText::_( 'AVAILABLE_FROM' ); ?></label>
	                    <?php echo $this->form->getInput('from-month', 'params'); ?>
	                    <?php echo $this->form->getInput('from-day', 'params'); ?>
                        <br>
                        <label><?php echo JText::_( 'AVAILABLE_TO' ); ?></label>
	                    <?php echo $this->form->getInput('to-month', 'params'); ?>
	                    <?php echo $this->form->getInput('to-day', 'params'); ?>
                    </div>
                </div>

                <div class="generatetimes custom show" style="display: none;">
                    <h3><?php echo JText::_('TIME_GENERATOR'); ?></h3>
                    <div class="item noremove">
                        <div id="fromx" class="fromx bz">
                            <h5><?php echo JText::_( 'STARTING_TIME' ); ?></h5>
                            <select id="starting-time" name="starting-time" aria-invalid="false">
                                <option value=""><?php echo JText::_( 'STARTING_TIME' ); ?></option>
                                <option value="24">00</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                            </select>
                        </div>

                        <div class="tox bz">
                            <h5><?php echo JText::_( 'ENDING_TIME' ); ?></h5>
                            <select id="ending-time" name="ending-time">
                                <option value=""><?php echo JText::_( 'ENDING_TIME' ); ?></option>
                                <option value="24">00</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                            </select>
                        </div>

                        <div class="tox bz">
                            <h5><?php echo JText::_( 'EVERY_N_MINUTE' ); ?></h5>
                            <select id="every-n-minutes" name="every-n-minutes">
                                <option value=""><?php echo JText::_( 'SELECT_MINUTES_INTERVAL' ); ?></option>
                                <option value="1"><?php echo JText::_( 'EVERY_60_MINUTES' ); ?></option>
                                <option value="2"><?php echo JText::_( 'EVERY_30_MINUTES' ); ?></option>
                                <option value="3"><?php echo JText::_( 'EVERY_20_MINUTES' ); ?></option>
                                <option value="4"><?php echo JText::_( 'EVERY_15_MINUTES' ); ?></option>
                                <option value="5"><?php echo JText::_( 'EVERY_12_MINUTES' ); ?></option>
                                <option value="6"><?php echo JText::_( 'EVERY_10_MINUTES' ); ?></option>
                                <option value="12"><?php echo JText::_( 'EVERY_5_MINUTES' ); ?></option>
                            </select>
                        </div>

                        <div class="pricex bz search-field">
                            <?php echo $this->form->getLabel('date', 'params'); ?>
                            <i class="fa fa-calendar launch-calendar generator"> </i>
                            <input type="text" id="date-generator" value="1" placeholder="<?php echo JText::_( 'DAY_OR_DATE' ); ?>" name="date">
                        </div>

                        <div class="praiz bz">
                            <h5><?php echo JText::_( 'CHILD_PRICE' ); ?></h5>
                            <input type="number" id="child-price-generator" placeholder="Enter price for this time interval" name="child-price-generator" value="0" min="0" max="99999" step="1">
                            <h5><?php echo JText::_( 'ADULT_PRICE' ); ?></h5>
                            <input type="number" id="adult-price-generator" placeholder="Enter price for this time interval" name="adult-price-generator" value="0" min="0" max="99999" step="1">
                        </div>

                        <div class="praiz bz slts">
                            <h5><?php echo JText::_( 'SLOTS' ); ?></h5>
                            <input type="number" id="slots-generator" value="1" name="slots" min="0" max="99999" step="1">
                        </div>

                        <div class="pricex bz">
                            <h5><?php echo JText::_( 'GENERATE' ); ?></h5>
                            <div id="sbm" class="round"><?php echo JText::_( 'GENERATE_SLOTS' ); ?></div>
                        </div>
                    </div>
                    <div class="clear">
                        <hr>
                    </div>
                </div>
                <div class="customlaikas custom" style="">
                    <h3><?php echo JText::_('TOUR_TIMESLOT_CREATOR'); ?></h3>
                    <div class="item noremove">
                        <div id="fromx" class="fromx bz">
                            <h5><?php echo JText::_( 'STARTING_TIME' ); ?></h5>
                            <select id="start-hour" name="start-hour" aria-invalid="false">
                                <option value=""><?php echo JText::_( 'STARTING_TIME' ); ?></option>
                                <option value="00">00</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                            </select>
                        </div>

                        <div class="fromx bz">
                            <h5 style="visibility:hidden;">x</h5>
                            <input type="number" placeholder="Enter minutes" id="start-minutes" name="start-minutes" min="0" max="59" step="1">
                        </div>

                        <div class="tox bz">
                            <h5><?php echo JText::_( 'ENDING_TIME' ); ?></h5>
                            <select id="end-hour" name="end-hour">
                                <option value=""><?php echo JText::_( 'ENDING_TIME' ); ?></option>
                                <option value="00">00</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                            </select>

                        </div>

                        <div class="fromx bz">
                            <h5 style="visibility:hidden;">x</h5>
                            <input type="number" id="end-minutes" placeholder="Enter minutes" name="end-minutes" min="0" max="59" step="1">
                        </div>

                        <div class="pricex bz">
                            <h5><?php echo JText::_( 'CHILD_PRICE' ); ?></h5>
                            <input type="number" id="child-price" placeholder="Enter price for this time interval" name="child-price" value="0" min="0" max="99999" step="1">
                            <h5><?php echo JText::_( 'ADULT_PRICE' ); ?></h5>
                            <input type="number" id="adult-price" placeholder="Enter price for this time interval" name="adult-price" value="0" min="0" max="99999" step="1">
                        </div>
                        <style>.timeslot { position:relative; }</style>
                        <div class="pricex bz">
                            <h5><?php echo JText::_( 'SLOTS' ); ?></h5>
                            <div class="slts">
                                <input type="number" id="slots" value="1" placeholder="<?php echo JText::_( 'SLOTS_PER_TIME' ); ?>" name="slots" min="0" max="99999" step="1"></div>
	                        <?php echo $this->form->getLabel('date', 'params'); ?>
                            <div class="search-field">
                                <i class="fa fa-calendar launch-calendar"> </i>
                                <input type="text" id="date" value="1" placeholder="<?php echo JText::_( 'DAY_OR_DATE' ); ?>" name="date">
                            </div>
                            <div id="date-calendar" ></div>
                        </div>

                    </div>

                    <div class="pricex bz">
                        <h5><?php echo JText::_( 'CREATE_TIME_INTERVAL' ); ?></h5>
                        <div class="add round"><?php echo JText::_( 'ADD_TIMESLOT' ); ?></div>
                    </div>


                    <div style="clear:both;"></div>
                </div>

                <div class="months">
                    <div id="cln"></div>
                    <div class="date_hour">
                        <h3><?php echo JText::_('EDIT_TIMESLOTS'); ?></h3>
                        <div class="date"></div>
                    </div>
                </div>
                <div class="dyaz">
                    <div class="days">
                        <div class="dd z1 valandos">
                            <div data-day="1" class="dayc active"><?php echo JText::_('Monday'); ?>
                                <div class="turn"></div>
                            </div>
                            <div class="beatify"></div>
                            <div class="dayblock d1" data-day="1"></div>
                        </div>
                        <div class="dd z2 valandos">
                            <div data-day="2" class="dayc"><?php echo JText::_('Tuesday'); ?>
                                <div class="turn"></div>
                            </div>
                            <div class="beatify"></div>
                            <div class="dayblock d2" data-day="2"></div>
                        </div>
                        <div class="dd z3 valandos">
                            <div data-day="3" class="dayc"><?php echo JText::_('Wednesday'); ?>
                                <div class="turn"></div>
                            </div>
                            <div class="beatify"></div>
                            <div class="dayblock d3" data-day="3"></div>
                        </div>
                        <div class="dd z4 valandos">
                            <div data-day="4" class="dayc"><?php echo JText::_('Thursday'); ?>
                                <div class="turn"></div>
                            </div>
                            <div class="beatify"></div>
                            <div class="dayblock d4" data-day="4"></div>
                        </div>
                        <div class="dd z5 valandos">
                            <div data-day="5" class="dayc"><?php echo JText::_('Friday'); ?>
                                <div class="turn"></div>
                            </div>
                            <div class="beatify"></div>
                            <div class="dayblock d5" data-day="5"></div>
                        </div>
                        <div class="dd z6 valandos">
                            <div data-day="6" class="dayc"><?php echo JText::_('Saturday'); ?>
                                <div class="turn"></div>
                            </div>
                            <div class="beatify"></div>
                            <div class="dayblock d6" data-day="6"></div>
                        </div>
                        <div class="dd z7 valandos">
                            <div data-day="7" class="dayc"><?php echo JText::_('Sunday'); ?>
                                <div class="turn"></div>
                            </div>
                            <div class="beatify"></div>
                            <div class="dayblock d7" data-day="7"></div>
                        </div>
                    </div>

                    <div id="custom-dates">

                    </div>

                    <div style="clear:both;"></div>

                    <div class="valandos"></div>
                    <div class="laikas"></div>
                </div>
            </fieldset>
        </div>
    </div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>


<!--	    --><?php //echo JHtml::_('bootstrap.addTab', 'myTab', 'discounts', JText::_('DISCOUNTS')); ?>
<!--        <div class="row-fluid">-->
<!--            <div class="span12">-->
<!--                <div class="discount-containers">-->
<!--                    <h3>--><?php //echo JText::_('DISCOUNT_DESC'); ?><!--</h3>-->
<!--                    <div id="discounts"></div>-->
<!--                    <a href="#" id="condition">--><?php //echo JText::_('ADD_DISCOUNT'); ?><!--</a>-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!--        </div>-->
<!--	    --><?php //echo JHtml::_('bootstrap.endTab'); ?>


	    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'fields', JText::_('RESERVATION_FIELDS')); ?>
        <div class="row-fluid">
            <div class="span12">
                <fieldset class="adminform">
                    <h3><?php echo JText::_('FIELDS_FOR_USER'); ?></h3>
	                <?php echo $this->form->renderField('user_data_fields', 'params'); ?>
	                <?php echo $this->form->renderField('travellers_data_fields', 'params'); ?>
	                <?php echo $this->form->renderField('email_fields', 'params'); ?>
                </fieldset>
            </div>
        </div>
	    <?php echo JHtml::_('bootstrap.endTab'); ?>

	    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'deposit', JText::_('DEPOSIT_BOOKING')); ?>
        <div class="row-fluid">
            <div class="span12">
                <fieldset class="adminform">
                    <h3><?php echo JText::_('DEPOSIT_BOOKING'); ?></h3>
	                <?php echo $this->form->renderField('deposit_booking', 'params'); ?>
	                <?php echo $this->form->renderField('deposit_booking_choose', 'params'); ?>
	                <?php echo $this->form->renderField('deposit_percentage', 'params'); ?>
                </fieldset>
            </div>
        </div>
	    <?php echo JHtml::_('bootstrap.endTab'); ?>

	    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'colors', JText::_('COLOR_SETTINGS')); ?>
        <div class="row-fluid">
            <div class="span12">
                <fieldset class="adminform">
                    <h3><?php echo JText::_('COLOR_SETTINGS'); ?></h3>
				    <?php echo $this->form->renderField('color', 'params'); ?>
                </fieldset>
            </div>
        </div>
	    <?php echo JHtml::_('bootstrap.endTab'); ?>

	    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'import', JText::_('IMPORT_BOOKING')); ?>
        <div class="row-fluid">
            <div class="span12">
                <fieldset class="adminform">
                    <h3><?php echo JText::_('IMPORT_BOOKING'); ?></h3>
	                <?php echo $this->form->renderField('import_note', 'params'); ?>
	                <?php echo $this->form->renderField('import_booking', 'params'); ?>
                </fieldset>
            </div>
        </div>
	    <?php echo JHtml::_('bootstrap.endTab'); ?>

        <div style="clear:both;"></div>
        <div class="controls generate" style="display: none;">
            <?php echo $this->form->getInput('fromtime'); ?>
            <?php echo $this->form->getInput('totime'); ?>
            <?php echo $this->form->getInput('division'); ?>
            <input type="text" name="all" id="all" value="" placeholder="<?php echo JText::_('DETAIL_PRICE'); ?>"/>
        </div>


        <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>"/>
        <input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>"/>
        <input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>"/>
        <input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>"/>
        <?php if (empty($this->item->created_by)) { ?>
            <input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>"/>

        <?php } else { ?>
            <input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>"/>

        <?php } ?>
        <div class="controls hidden"><?php echo $this->form->getInput('times'); ?></div>
        <div class="controls hidden"><?php echo $this->form->getInput('bandates'); ?></div>
        <input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>"/>
        <input type="hidden" name="task" value=""/>
        <input type="hidden" id="jfromtime" name="jform[fromtime]" value=""/>
        <input type="hidden" id="discount" name="jform[discount]" value="<?php echo $discount; ?>"/>
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>

<input id="fileupload" type="file" name="files">

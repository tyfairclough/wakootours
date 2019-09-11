jQuery(document).ready(function() {
    jQuery('h2').click(function() {
        jQuery('head').append( jQuery('<link rel="stylesheet" type="text/css" />').attr('href', 'xs.css') );
    });

    jQuery('.template').click(function() {
        jQuery('link[rel=stylesheet][href~="xs.css"]').remove();
    });

    function getOffsets() {
        jQuery('.block').each(function() {
            if(!jQuery(this).hasClass('col-lg-12')) {
                if(jQuery(this).offset().left < 270) {
                    jQuery(this).find('.drop-block').removeClass('not-full');
                }
            }
        });
    }

    function addContainer() {
        jQuery('.elements .form-group').wrap('<div class="form-container"> <div class="buttons"> <div class="grip">d</div> <div class="edit">e</div> <div class="delete">x</div> </div>');
    }

    jQuery('body').on('click', '.resize', function() {
        jQuery(this).parent().parent().find('.slider').toggle();
    });

    function formsDraggable() {
        jQuery(".form-container").draggable({
            cancel: "select",
            revert: "invalid",
            helper: "clone",
            handle: ".grip",
            start: function(  event, ui) {
                jQuery(this).draggable('instance').offset.click = {
                    left: Math.floor(150 / 2) + 5,
                    top: Math.floor(ui.helper.height() / 2)
                };
            }
        });
    }

    function setDroppable() {
        jQuery('.block').draggable({
            cancel: "select",
            revert: "invalid",
            helper: "clone",
            cursor: "move",
            handle: ".grip",
            scroll: true,
            start: function(  event, ui) {
                getOffsets();
                jQuery(this).draggable('instance').offset.click = {
                    left: Math.floor(ui.helper.width() / 2) + 15,
                    top: Math.floor(ui.helper.height() / 2 + 12)
                };
            }
        }).droppable({
            accept: ".form-container",
            classes: {
                "ui-droppable-active": "ui-state-highlight"
            },
            drop: function (event, ui) {
                jQuery(event.target).find('.contents').append(ui.draggable);
                addStuff();
            }
        });
    }

    function doSlider() {
        jQuery(".slider:not(.ui-slider)").slider({
            value: 12,
            min: 1,
            step: 1,
            max: 12,
            slide: function( event, ui ) {
                var block = jQuery( this ).closest('.block');
                var newClass = 'col-lg-' + ui.value;
                var klass = block.attr('class');
                if(klass.match(/col-lg-(\d+)/)) {
                    var newklass = klass.replace(/col-lg-(\d+)/, '');
                    block.attr('class', newklass);
                }
                if(ui.value < 12) {
                    block.find('.drop-block').addClass('not-full');
                }
                block.addClass(newClass);
            }
        });
    }

    function setDrop() {
        jQuery('#drop-container').droppable({
            accept: ".block",
            classes: {
                "ui-droppable-active": "ui-state-highlight"
            },
            drop: function (event, ui) {
                var clone = jQuery(ui.draggable).attr('clone');
                var element = jQuery(ui.draggable);
                if(clone) {
                    element = element.clone().removeAttr('clone');
                }
                jQuery(event.target).before(element);
                addStuff();
            }
        });
        jQuery('.drop-block').droppable({
            accept: ".block",
            classes: {
                "ui-droppable-active": "ui-state-highlight"
            },
            drop: function (event, ui) {
                var clone = jQuery(ui.draggable).attr('clone');
                var element = jQuery(ui.draggable);

                if(clone) {
                    element = element.clone().removeAttr('clone');
                }

                element.find('.contents').show();
                jQuery(event.target).parent().parent().before(element);
                addStuff();
            }
        });
    }

    function addStuff() {
        doSlider();
        setDroppable();
        setDrop();
        formsDraggable();
    }

    var newBlock = '<div class="block col-xs-12 col-lg-12 ui-draggable ui-droppable"> <div class="block-insider"> <div class="drop-block ui-droppable"></div> <div class="block-content"><div class="slider"><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 45.4545%;"></span></div><div class="buttons"><div class="grip ui-draggable-handle">d</div><div class="edit">e</div><div class="resize">r</div><div class="delete">t</div></div><select class="change-size" name="size"> <option value="col-lg-1">1/12</option> <option value="col-lg-2">2/12</option> <option value="col-lg-3">3/12</option> <option value="col-lg-4">4/12</option> <option value="col-lg-5">5/12</option> <option value="col-lg-6">6/12</option> <option value="col-lg-7">7/12</option> <option value="col-lg-8">8/12</option> <option value="col-lg-9">9/12</option> <option value="col-lg-10">10/12</option> <option value="col-lg-11">11/12</option> <option value="col-lg-12">12/12</option> </select> </div><div class="contents"></div> </div> </div>';

    //**<div class="block col-xs-12 col-lg-12 ui-draggable ui-droppable"> <div class="block-insider"> <div class="drop-block ui-droppable"></div> <div class="block-content"><div class="slider ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span></div><div class="buttons"><div class="grip ui-draggable-handle">d</div><div class="edit">e</div><div class="resize">r</div><div class="delete">t</div></div><select class="change-size" name="size"> <option value="col-lg-1">1/12</option> <option value="col-lg-2">2/12</option> <option value="col-lg-3">3/12</option> <option value="col-lg-4">4/12</option> <option value="col-lg-5">5/12</option> <option value="col-lg-6">6/12</option> <option value="col-lg-7">7/12</option> <option value="col-lg-8">8/12</option> <option value="col-lg-9">9/12</option> <option value="col-lg-10">10/12</option> <option value="col-lg-11">11/12</option> <option value="col-lg-12">12/12</option> </select> </div><div class="contents"></div> </div> </div>'


    jQuery('#drop-container').click(function() {
        jQuery(newBlock).insertBefore(jQuery(this));
        addStuff();
    });

    jQuery('body').on('change', '.change-size', function() {
        var block = jQuery(this).parent().parent().parent();
        var newClass = jQuery(this).val();
        var klass = block.attr('class');
        if(klass.match(/col-lg-(\d+)/)) {
            var newklass = klass.replace(/col-lg-(\d+)/, '');
            block.attr('class', newklass);
        }
        block.addClass(newClass);
    });

    jQuery('body').on('click', '.form-container .edit', function() {
//                var form = jQuery(this).parents('.form-container').remove();
    });

    jQuery('body').on('click', '.form-container .delete', function() {
        var form = jQuery(this).parents('.form-container');
        form.remove();
    });

    jQuery('body').on('click', '.block .delete', function() {
        var block = jQuery(this).parents('.block');
        var elems = block.find('.form-container');
        jQuery(elems).each(function() {
            var html = jQuery(this)[0].outerHTML;
            jQuery('.elements .rendered-form').append(html);
        });
        block.remove();
        addStuff();
    });


    setTimeout(function () {
        addContainer();

        addStuff();
        console.log('now');
    }, 2000);
});
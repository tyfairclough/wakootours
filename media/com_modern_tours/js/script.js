jQuery(document).ready(function () {

    rowCount = 1, generator = true, interval = false, intarget = 0;

    jQuery( "#files" ).sortable();
    jQuery(".date-from, .date-to").click(function(e) {
        interval = true, intarget = jQuery(this).attr('target');
        console.log(intarget);
        jQuery( "#date-calendar" ).show();

    });

    jQuery(".launch-calendar").click(function(e) {
        interval = false;
        e.stopPropagation();
        jQuery(this).hasClass('generator') ? generator = true : generator = false;
        jQuery( ".overlay" ).show();
        jQuery( "#date-calendar" ).show();
    });

    jQuery( "#date-calendar" ).clndr({
        clickEvents: {
            click: function (target) {
                var date = moment(target.date._i).format('YYYY-MM-DD');
                if(!interval)
                {
                    generator ? jQuery('#date-generator').val(date) : jQuery('#date').val(date);
                }
                else
                {
                    
                    jQuery(intarget).val(date);
                }

                jQuery( "#date-calendar" ).hide();
                jQuery( ".overlay" ).hide();
            }
        }
    });

    $.createFields = function (element, title, value) {
        var $this = element;
        var inputName = "jform[params][" + title + "]";
        var id = "jform_params_" + title;
        var placeholder = 'placeholder_' + title;
        placeholder='';
        var customFieldBlock = jQuery('<div></div>').addClass('custom-field');
        var inputTextElement = jQuery('<input>').attr({'placeholder' : placeholder, 'name': inputName + '[' + rowCount + ']', 'type': 'text', 'id': id + rowCount});
        if(value) {
            if (value.length > 0) {
                inputTextElement.val(value);
            }
        }
        var deleteElement = jQuery('<div>Delete</div>').addClass('deleteRow');
        customFieldBlock.append(inputTextElement, deleteElement);
        $this.before(customFieldBlock);
        rowCount++;
    }

    $.createItirenaryFields = function (element, value) {
        var $this = element;
        var inputNameDay = "jform[params][itirenary][" + rowCount + "][day]";
        var inputNameDesc = "jform[params][itirenary][" + rowCount + "][desc]";
        var customFieldBlock = jQuery('<div></div>').addClass('custom-field');
        var inputTextElementDay = jQuery('<input>').attr({'name': inputNameDay, 'type': 'text', 'class': 'input-title full-w class_' + rowCount, 'placeholder' : ITIRENARY_DAY_TITLE});
        var inputTextElementDesc = jQuery('<input>').attr({'name': inputNameDesc, 'type': 'text', 'cols': 6, 'class': 'textarea-text full-w class_' + rowCount, 'placeholder' : ITIRENARY_DAY_DESCRIPTION});
        if(value) {
            if (value.length > 0) {
                inputTextElement.val(value);
            }
        }
        var deleteElement = jQuery('<div>' + DELETE_ITIRENARY_DAY + '</div>').addClass('deleteRow full-w-delete');
        customFieldBlock.append(inputTextElementDay, deleteElement);
        customFieldBlock.append(inputTextElementDesc, deleteElement);
        $this.before(customFieldBlock);
        rowCount++;
    }

    $.REcreateItirenaryFields = function (element, valueDay, valueDesc) {
        var $this = element;
        var inputNameDay = "jform[params][itirenary][" + rowCount + "][day]";
        var inputNameDesc = "jform[params][itirenary][" + rowCount + "][desc]";
        var customFieldBlock = jQuery('<div></div>').addClass('custom-field');
        var inputTextElementDay = jQuery('<input>').attr({'name': inputNameDay, 'type': 'text', 'class': 'input-title full-w class_' + rowCount, 'placeholder' : ITIRENARY_DAY_TITLE});
        var inputTextElementDesc = jQuery('<input>').attr({'name': inputNameDesc, 'type': 'text', 'cols': 6, 'class': 'textarea-text full-w class_' + rowCount, 'placeholder' : ITIRENARY_DAY_DESCRIPTION});
        if (valueDay.length > 0) {
            inputTextElementDay.val(valueDay);
        }
        if (valueDesc.length > 0) {
            inputTextElementDesc.val(valueDesc);
        }
        var deleteElement = jQuery('<div>' + DELETE_ITIRENARY_DAY + '</div>').addClass('deleteRow full-w-delete');
        customFieldBlock.append(inputTextElementDay, deleteElement);
        customFieldBlock.append(inputTextElementDesc, deleteElement);
        $this.before(customFieldBlock);
        rowCount++;
    }

    $.reCreateItirenary = function (title, containerDiv, jsonData) {
        try {
            var data = JSON.parse(jsonData);
        } catch (e) {
            alert('Invalid JSON');
        }

        jQuery.each(data, function( index, value ) {
            $.REcreateItirenaryFields(jQuery(containerDiv), value.day, value.desc);
        });
    }

    $.reCreateFields = function (title, containerDiv, jsonData) {
        try {
            var data = JSON.parse(jsonData);
        } catch (e) {
            alert('Invalid JSON');
        }

        jQuery.each(data, function( index, value ) {
            $.createFields(jQuery(containerDiv), title, value);
        });
    }

    $.deleteRow = function (element) {
        element.parent().remove();
    }

    jQuery('#includes-btn').on('click', function() {
        $.createFields(jQuery(this), 'includes');
    });

    jQuery('#excludes-btn').on('click', function() {
        $.createFields(jQuery(this), 'excludes');
    });

    jQuery('#itirenary-btn').on('click', function() {
        $.createItirenaryFields(jQuery(this));
        // jQuery('textarea').trumbowyg();
    });

    jQuery('div#toolbar-apply').hover(function() {
        jQuery('textarea').val().replace(/\r\n|\r|\n/g,"<br />")
    });

    jQuery('body').on('click', '.deleteRow', function() {
        $.deleteRow(jQuery(this));
    });
    var rules_basic = {
        condition: 'AND',
        rules: []
    };

    var filters = [
//            {
//            id: 'day',
//            label: 'Days',
//            type: 'string',
//            operators: ['equal', 'not_equal', 'less', 'less_or_equal', 'greater', 'greater_or_equal', 'between', 'not_between']
//        }, {
        {
            id: 'participants',
            label: 'Participants',
            type: 'string',
            operators: ['equal', 'less', 'greater']
//                operators: ['equal', 'not_equal', 'less', 'less_or_equal', 'greater', 'greater_or_equal', 'between', 'not_between']
        },
        {
            id: 'price',
            label: 'Price',
            type: 'string',
            operators: ['equal', 'less', 'greater']
        }
//            ,
//            {
//                id: 'user',
//                label: 'User',
//                type: 'string',
//                input: 'select',
//                values: userGroups,
//                operators: ['equal']
//            }
    ];
    var newElem = function builder(elem) {
        jQuery('#' + elem).queryBuilder({
            filters: filters
        });
    }

    jQuery('#condition').click(function() {
        var length = jQuery('.query').length + 1;
        var id = 'builder' + length;
        jQuery('#discounts').append('<div class="discount_block"><h2>Add discount</h2><div class="delete_discount">Delete</div><div class="query" id="' + id + '"></div><div class="discount_add"><select class="type" name="type"><option value="">Select discount type</option><option value="percentage">Percentage</option><option value="fixed">Fixed</option></select> <input type="text" class="amount"  name="amount" placeholder="Enter discout amount"/></div>');
        newElem(id);
    });

    jQuery('#toolbar-disable-days button').attr('onclick', '');
    jQuery('#toolbar-hour-days button').attr('onclick', '');
    jQuery('#toolbar-availability button').attr('onclick', '');
    jQuery('#toolbar-discounts button').attr('onclick', '');
    jQuery('#toolbar-parameters button').attr('onclick', '');

    var paramToggler = jQuery();

    function check(elemValue, value)
    {
        if(Array.isArray(elemValue)) {
            if(elemValue.indexOf(value) > -1)
            {
                return true;
            }
        } else {
            if(value == elemValue) {
                return true;
            }
        }
    }

    function toggler(toggler, element, elemValue) {
        jQuery(toggler).change(function() {
            check(elemValue, jQuery(this).val()) ? jQuery(element).show() : jQuery(element).hide();
        });
    }

    toggler('#jform_params_use_params', '#parameter-option', 1);
    simpleCheck('#jform_params_use_params', '#parameter-option', 1);
    checkP(jQuery('#jform_params_type').val());

    jQuery('#jform_params_type').change(function() {
        var val = jQuery(this).val();
        checkP(val);
    });

    function checkP(val) {
        if(val == 1) {
            jQuery('#flex, #fixed').hide();
        } else {
            if (val == 3) {
                jQuery('#flex').hide();
                jQuery('#fixed').show();
            } else if (val == 4 || val == 2) {
                jQuery('#fixed').hide();
                jQuery('#flex').show();
            }
        }
    }

    function simpleCheck(toggler, element, elemValue) {
        var val = jQuery(toggler).val();
        check(elemValue, val) ? jQuery(element).show() : jQuery(element).hide();
    }


    function regex(phrase) {
        var myRegexp = /[0-9]{0,4}-[0-9]{0,2}-[0-9]{0,2}/;
        var match = myRegexp.exec(phrase);
        if (match) {
            return match[0];
        }
        else {
            return 0;
        }
    }

    function pulsate() {
        setTimeout(function () {
            jQuery('.customlaikas .item input, .customlaikas .item select').addClass('pulse');
        }, 100);
        setTimeout(function () {
            jQuery('.customlaikas .item input, .customlaikas .item select').removeClass('pulse');
        }, 200);

    }

    jQuery('BODY').on('click', '.delete', function() {
        jQuery(this).parent().remove();
    });


    function newElemFilters(elem, newRules) {
        var select = '<select id="_' + elem +'" class="type" name="type"><option value="">Select discount type</option><option value="percentage">Percentage</option><option value="fixed">Fixed</option></select>';
        jQuery('#discounts').append('<div class="discount_block"><h2>Add discount</h2><div class="delete_discount">Delete</div><div class="query" id="' + elem + '"></div><div class="discount_add">' + select + ' <input type="text" class="amount"  name="amount" value="' + newRules.rules[0].amount + '" placeholder="Enter discout amount"/></div>');
        jQuery("#_" + elem).val(newRules.rules[0].discount);
        jQuery('#' + elem).queryBuilder({
            filters: filters,
            rules: newRules
        });
    }

    window.save = function() {

        var queries = {};

        jQuery('.query').each(function() {
            var elem = jQuery(this).attr('id');
            var rules = getRules(elem);
            queries[elem] = rules;
        });

        jQuery('#discount').val(JSON.stringify(queries));
    }

    window.checkRules = function() {
        var blog = true;
        jQuery('#discounts input, #discounts select').each(function() {
            if(!jQuery(this).val() || jQuery(this).val() == '-1') {
                jQuery(this).focus();
                blog = false;
            }
        });
        return blog;
    }

    if(jsonas) {
        var keys = Object.keys(jsonas);
        jQuery(keys).each(function( i, key) {
            newElemFilters(key, jsonas[key]);
        });
    }

    function getRules(elem) {
        elem = '#'+elem;
        var rules = jQuery(elem).queryBuilder('getRules');
        var type = jQuery(elem).parent().find('.type').val();
        var amount = jQuery(elem).parent().find('.amount').val();
        rules.rules[0].discount = type;
        rules.rules[0].amount = amount;

        return rules;
    }

    jQuery('body').on('click', '.delete_discount', function() {
        jQuery(this).parent().remove();
    });

    jQuery('#toolbar-discounts').click(function() {
        var elem = jQuery('.discount-container');
        if(elem.is(':visible')) {
            elem.slideUp();
        } else {
            elem.slideDown();
        }
    });

    JSONtoFields();

    function getValuesFromInput()
    {
        var startHour = jQuery('#start-hour').val();
        var startMinutes = jQuery('#start-minutes').val();
        var endHour = jQuery('#end-hour').val();
        var endMinutes = jQuery('#end-minutes').val();
        var childPrice = jQuery('.time').attr('data-child-price');
        var adultPrice = jQuery('.time').attr('data-adult-price');
        var slots = jQuery('#slots').val();
    }

    function generateTime()
    {
        return jQuery('#start-hour').val() + ':' + jQuery('#start-minutes').val() + ' - ' + jQuery('#end-hour').val() + ':' + jQuery('#end-minutes').val();
    }

    function generateTimeFromValues(startHour, startMinutes, endHour, endMinutes)
    {
        return startHour + ':' + startMinutes + ' - ' + endHour + ':' + endMinutes;
    }

    function generateFieldFromValues(startHour, startMinutes, endHour, endMinutes, child, adult, slots, day)
    {
        var timeslot =
            jQuery('<div>')
                .addClass('timeslot')
                .html(generateTimeFromValues(startHour, startMinutes, endHour, endMinutes))
                .append('<i class="fa fa-clock-o edit-timeslot"></i>')
                .append('<span class="closex">x</span>')
                .attr('start-hour', startHour)
                .attr('start-minutes', startMinutes)
                .attr('end-hour', endHour)
                .attr('end-minutes', endMinutes)
                .attr('child-price', child)
                .attr('adult-price', adult)
                .attr('slots', slots)
                .attr('day', day);

        return timeslot;
    }

    function checkFieldsEmptyness()
    {
        var returnF = true;
        jQuery('.customlaikas input, .customlaikas select').each(function() {
            if(jQuery(this).val() == '') {
                jQuery(this).addClass('red-borders');
                returnF = false;
            } else {
                jQuery(this).removeClass('red-borders');
            }
        });
        return returnF;
    }

    function generateField()
    {
        var timeslot =
            jQuery('<div>')
                .addClass('timeslot')
                .html(generateTime())
                .append('<i class="fa fa-clock-o edit-timeslot"></i>')
                .append('<span class="closex">x</span>');

        jQuery('.customlaikas input, .customlaikas select').each(function() {
            timeslot.attr(this.name, jQuery(this).val());
        });

        return timeslot;
    }

    function deleteDuplicate(day, timeslot)
    {
        return jQuery('.d' + day).find('div[start-hour=' + timeslot.attr('start-hour') + '][start-minutes=' + timeslot.attr('start-minutes') + ']').remove();
    }

    function createDayDiv(day)
    {
        if(jQuery("[data-day="+day+"]").length === 0)
        {
            var dayDiv = '<div class="dd z1 valandos keeper'+day+'"><div data-day="'+day+'" class="dayc active">'+day+'<div class="turn"></div> </div> <div class="beatify"></div><div class="dayblock d'+day+'" data-day="'+day+'"></div></div>';
            jQuery('#custom-dates').append(dayDiv);
        }
    }

    function addToDay(day, timeslot)
    {
        deleteDuplicate(day, timeslot);

        if(day.length > 2)
        {
            createDayDiv(day);
        }

        jQuery('.d' + day).append(timeslot);
    }

    function fillInputFromTimeslot(timeslot)
    {
       timeslot.each(function() {
            jQuery.each(this.attributes, function() {
                jQuery('#' + this.name).val(this.value);
            });
        });
    }

    function generateTimeIntervals(start, end, everyNMinutes, day, adult, child, slots) {

        var totalHours = end - start;

        for (hours = 0; hours < totalHours; hours++) {
            for (i = 0; i < everyNMinutes; i++) {

                var startingMinutes = 60 / everyNMinutes;
                var minutes = startingMinutes * i;

                var startHour = moment(start, 'HH').add(minutes, 'minutes').add(hours, 'hours').format('HH');
                var startMinutes = moment(start, 'HH').add(minutes, 'minutes').add(hours, 'hours').format('mm');

                var endHour = moment(start, 'HH').add(minutes + startingMinutes, 'minutes').add(hours, 'hours').format('HH');
                var endMinutes = moment(start, 'HH').add(minutes + startingMinutes, 'minutes').add(hours, 'hours').format('mm');

                var timeslot = generateFieldFromValues(startHour, startMinutes, endHour, endMinutes, child, adult, slots, day);
                addToDay(day, timeslot);
            }
        }
    }

    function JSONtoFields()
    {
        var JSON = jQuery.parseJSON(jQuery('#jform_times').val());

        if(JSON)
        {
            jQuery(Object.keys(JSON)).each(function( i, day ) {
                jQuery(JSON[day]).each(function( i, val ) {
                    jQuery(Object.keys(val)).each(function( i, unix ) {
                        var startHour = val[unix]['start-hour'];
                        var startMinutes = val[unix]['start-minutes'];
                        var endHour = val[unix]['end-hour'];
                        var endMinutes = val[unix]['end-minutes'];
                        var child = val[unix]['child-price'];
                        var adult = val[unix]['adult-price'];
                        var slots = val[unix]['slots'];
                        var timeslot = generateFieldFromValues(startHour, startMinutes, endHour, endMinutes, child, adult, slots, day)
                        addToDay(day, timeslot);
                    });
                });
            });
        }
    }

    function generateObject(item)
    {
        var object = {};
        jQuery(item).each(function() {
            jQuery.each(this.attributes, function() {
                object[this.name] = this.value;
            });
        });
        return object;
    }

    function convertToJSON()
    {
        var newJSON = {};
        jQuery('.dayblock').each(function() {
            var day = jQuery(this).data('day');
            jQuery(this).find('.timeslot').each(function() {
                var startUnix = jQuery(this).attr('start-hour') + '' + jQuery(this).attr('start-minutes');
                var endUnix = jQuery(this).attr('end-hour') + '' + jQuery(this).attr('end-minutes');
                var time = newJSON[day] ? newJSON[day] : {};
                time[startUnix] = generateObject(this);
                time[startUnix]['start'] = startUnix;
                time[startUnix]['end'] = endUnix;
                newJSON[day] = time;
            });
        });

        jQuery('#jform_times').val(JSON.stringify(newJSON));
    }

    function removeWholeDay(day, timeslot)
    {
        if(day.length > 2)
        {
            var timeslots = jQuery('.dayblock.d' + day).find('.timeslots').length;
            if(timeslots === 0)
            {
                jQuery('.keeper'+day).remove();
            }
        }
    }

    function orderTimeslots(day)
    {
        jQuery(".dayblock.d"+day+" .timeslot").sort(function (a, b) {
            return parseInt(a.id) > parseInt(b.id);
        }).each(function () {
            var elem = jQuery(this);
            elem.remove();
            jQuery(elem).appendTo(".dayblock.d"+day);
        });
    }

    jQuery('body').on('click', '.edit-timeslot', function() {
        var curItem = jQuery(this).parent();
        fillInputFromTimeslot(curItem);
    });

    jQuery('body').on('click', '.closex', function() {
        var timeslot = jQuery(this).parent();
        timeslot.remove();
        var day = timeslot.attr('day');
        removeWholeDay(day, timeslot);
    });

    jQuery('.add').click(function() {
        if(checkFieldsEmptyness())
        {
            var timeslot = generateField();
            day = jQuery('#date').val();
            addToDay(day, timeslot);
        }
    });

    jQuery('#sbm').click(function() {
        var start = jQuery('#starting-time').val();
        var end = jQuery('#ending-time').val();
        var everyNMinutes = jQuery('#every-n-minutes').val();
        var day = jQuery('#date-generator').val();
        var adult = jQuery('#adult-price-generator').val();
        var child = jQuery('#child-price-generator').val();
        var slots = jQuery('#slots-generator').val();
        generateTimeIntervals(start, end, everyNMinutes, day, adult, child, slots)
    });

    jQuery('#toolbar-hour-days, #toolbar-Hour-generator').click(function () {
        if (jQuery('.generatetimes').hasClass('show')) {
            jQuery('.generatetimes').removeClass('show').fadeIn(444);
        } else {
            jQuery('.generatetimes').addClass('show').fadeOut(444);
        }
    });

    jQuery('#toolbar-availability').click(function () {
        jQuery('a[href="#times"]').click();
        if (!jQuery('.availablity-intervals').is(':visible')) {
            jQuery('.availablity-intervals').fadeIn(444);
        } else {
            jQuery('.availablity-intervals').fadeOut(444);
        }
    });

    jQuery('div#toolbar').hover(function () {
        convertToJSON();
    });

});
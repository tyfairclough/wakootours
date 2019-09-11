var min_res_slots = 0;

(function ($) {
    $(function () {

        var clock = 'HH:mm';
        var dateFormat = 'MMMM DD hh:mm';

        $.getAvailableSlots = function() {
            return parseInt($('.time.selected .available-slots').html());
        }

        $.checkSlots = function () {
            var total = parseInt($('#children').val()) + parseInt($('#adults').val());
            if($.getAvailableSlots() < total) {
                $.warningAlert(translations['NO_MORE_SLOTS']);
                setTimeout(function () {
                    $('.ss').html($.getAvailableSlots());
                }, 1111);
                return false;
            }
            else {
                return true;
            }
        }

        $.hideOptions = function() {
            var slots = $.getAvailableSlots();
            $('#adults option').hide();
            $('#children option').hide();
            for(i=0;i<=slots;i++)
            {
                $('#adults option:eq(' + i + ')').show();
                $('#children option:eq(' + i + ')').show();
            }
            $('#children option:eq(' + slots + ')').hide();
        }

        $.addTime = function(date, days, hours, minutes, format) {

            date = moment(date);

            if(days)
            {
                date.add(days, 'days');
            }

            if(hours)
            {
                date.add(hours, 'hours');
            }

            if(minutes)
            {
                date.add(minutes, 'minutes');
            }

            if(format)
            {
                return date.format(format);
            }
            else
            {
                return date.format(dateFormat);
            }
        }

        $.tourLength = function(date) {

            var ts = $('.time.selected');
            var newDateEnd = $.addTime(date, tourInDays, ts.attr('data-start-hour'), ts.attr('data-start-minutes'), 'MMMM DD');
            var endTime = $.addTime(date, tourInDays, ts.attr('data-end-hour'), ts.attr('data-end-minutes'), 'HH:mm');

            var newDateStart = $.addTime(date, 0, ts.attr('data-start-hour'), ts.attr('data-start-minutes'), 'MMMM DD');
            var startTime = $.addTime(date, 0, ts.attr('data-start-hour'), ts.attr('data-start-minutes'), 'HH:mm');

            var end = $.addTime(date, tourInDays, ts.attr('data-end-hour'), ts.attr('data-end-minutes'), 'YYYY-MM-DD HH:mm');

            if(tourInDays > 0)
            {
                var lengthText = newDateStart + ' <span class="hour-small"><small>' + startTime + '</small></span> <svg viewBox="0 0 18 18" style="fill: rgb(118, 118, 118);" class="marged10"><path d="m4.29 1.71a1 1 0 1 1 1.42-1.41l8 8a1 1 0 0 1 0 1.41l-8 8a1 1 0 1 1 -1.42-1.41l7.29-7.29z" fill-rule="evenodd"></path></svg> ' + newDateEnd + ' <span class="hour-small"><small>' + endTime + '</small></span>';
            }
            else
            {
                var lengthText = newDateStart + ' <small>(' + startTime + '- ' + endTime + ')</small>';
            }

            $('#endtime').val(end);
            $('.tour-length').html(lengthText);
        }

        $.allFilled = function() {
            if($.todayDate() && $('.time.selected') !== 0 && ($('select#adults').val() || $('select#children').val()))
            {
                $.calculatePrice();
            }
        }

        $.addZeroes = function(oldnum) {
            num = parseFloat(oldnum);
            return num.toFixed(2);
        }

        $.calculatePrice = function() {
            var children = $('#children').val(), adults = $('#adults').val(), adultPrice = $('.time.selected').data('adult-price'), childPrice = $('.time.selected').data('child-price'), depositSum = 0.00;

            var adultSum = adultPrice * adults;
            var childSum = childPrice * children;
            var totalSum = adultSum + childSum;

            adultPrice = $.addZeroes(adultPrice);
            childPrice = $.addZeroes(childPrice);
            childSum = $.addZeroes(childSum);
            adultSum = $.addZeroes(adultSum);
            totalSum = $.addZeroes(totalSum);
            depositSum = $.addZeroes((totalSum/100)*deposit_percentage);

            // SINGLE PRICE
            $('.single-adult-sum').html(adultPrice);
            $('.single-child-sum').html(childPrice);

            // PARTICIPANTS * SINGLE PRICE
            $('.total-adult-price').html(adultSum);
            $('.total-child-price').html(childSum);

            // TOTAL ORDER PRICE
            $('.total-order-sum, #total-price').html(totalSum);
            $('.deposit-total-order-sum').html(depositSum);
        }

        $.setVariable = function(variable) {
            return typeof variable === 'string' && variable.trim() ? variable : '';
        }

        var max_slots = 9999, elms = 1, elems = 1, cart = {}, cartMode = 1;

        var operators = {
            'equal': function (a, b) {
                return a == b
            },
            'not_equal': function (a, b) {
                return a != b
            },
            'less': function (a, b) {
                return a < b
            },
            'less_or_equal': function (a, b) {
                return a <= b
            },
            'greater': function (a, b) {
                return a > b
            },
            'greater_or_equal': function (a, b) {
                return a >= b
            },
            'between': function (a, b) {
                return a + b
            },
            'not_between': function (a, b) {
                return a + b
            }
        };

        $.setVar = function (variable) {
            return hours[variable];
        }

        $.checkConsecutiveMinimumSlots = function () {
            return $.reservedTimes();
        }

        $.getParams = function () {
            var params = $.setVar('params');
            var activated = params['use_params'];
            if (params) {
                if (activated == 1) {
                    mode = params['type'];
                    min_slots = params['fixed_slots'];
                }
                if (mode == 2 || mode == 4) {
                    max_slots = params['slots'];
                    min_res_slots = params['min_slots'];
                }
            } else {
                min_res_slots = 0;
                max_slots = 9999, mode = defaultMode;
            }
        }

        $.maxSlots = function () {
            return $.reservedTimes() <= max_slots;
        }

        $.reservedTimes = function () {
            if (mode == 4) {
                return $('.time.selected').length;
            } else {
                return elms;
            }
        }

        $.scanDay = function (date) {
            var day = moment(date).locale(locale).format('E');

            currentHours = $.setVar('times');

            if (currentHours[date]) {
                return currentHours[date];
            }
            if (currentHours[day]) {
                return currentHours[day];
            }
            return false;
        }

        $.setAttr = function (attributes) {
            var attrs = '';
            $(Object.keys(attributes)).each(function (data, value) {
                attrs += 'data-' + value + '="' + attributes[value] + '"';
            });
            return attrs;
        }

        $.toMiliseconds = function(hour, minutes) {
            var hourInMS = hour * 60 * 60;
            var minutesInMS = minutes * 60;
            return hourInMS + minutesInMS;
        }

        $.blockDaysWithoutSlots = function() {
            $.iterateDays(function (day, elem) {
                var totalSlots = 0, availableSlots = 0;
                var todayHours = $.scanDay(day);
                var keys = Object.keys(todayHours);
                var totalHours = keys.length;

                keys.map(function(objectKey, index) {
                    var hourObject = todayHours[objectKey];
                    var time = $.startingHour(hourObject['start'], 'HH:mm:ss');
                    var date = day + ' ' + time;
                    totalSlots += parseInt(hourObject['slots']);
                    availableSlots += parseInt(reservations[date]);
                    totalHours = totalSlots <= availableSlots ? totalHours = totalHours - 1 : totalHours;
                });

                if(totalSlots <= availableSlots)
                {
                    elem.addClass('blocked');
                }

                $.showAvailableTimes(elem, totalHours);

            });
            var JTooltips = new Tips($$('.hasTip'),
                { fixed: false });
        }

        $.generateTime = function(timeObject) {
            var start = moment(timeObject['start'], 'HHmm').locale(locale).format(clockFormat);
            var end = moment(timeObject['end'], 'HHmm').locale(locale).format(clockFormat);
            return start + ' - ' + end;
        }

        $.startingHour = function(hour, format) {
            return moment(hour, 'HHmm').locale(locale).format(format);
        }

        $.displayHours = function () {
            var html = '', todayHours = $.scanDay($.todayDate()), text= 'Reserve';
            jQuery.each(todayHours, function(i) {
                var timeObject = todayHours[i];
                var time = $.generateTime(timeObject);
                var clickedHour = $.startingHour(timeObject['start'], 'HH:mm:ss');
                var bookedSlots = $.getReservedHour($.todayDate(), clickedHour);
                var available = timeObject['slots'] - bookedSlots;
                var slotText = available > 0 ? '<i class="fa fa-clock-o"></i> ' + time + ' <small>' + translations['SLOTS_AVAILABLE'] + '<span class="available-slots">' + available + '</span></small>': '<i class="fa fa-times"></i><span class="line-through">' + time + '</span> <small>' + translations['NO_SLOTS_LEFT'] + '</small>';
                var klass = available > 0 ? 'available' : 'sold-out';
                html += '<div class="time ' + klass + '" ' + $.setAttr(timeObject) + '><div class="reserve">' + text + '</div><div class="hours">' + slotText + '</div></div>';
            });
            
            $('.asset-timeslots').hide().html(html).slideDown(555);
        }

        $.getReservedHour = function(clickedDate, clickedHour) {
            var bookedSlots = 0;
            $(Object.keys(reservations)).each(function(i,val) {
                var booking = reservations[val];
                var split = val.split(' ');
                var date = split[0];
                var hour = split[1];
                if(clickedDate === date) {
                    if(clickedHour === hour) {
                        bookedSlots = booking;
                    }
                }
            });
            return bookedSlots;
        }

        $.sum = function () {
            $.getTimes();
            var sum = 0, times = 1;
            for (var el in obj) {
                if (obj.hasOwnProperty(el)) {
                    sum += parseFloat(obj[el]);
                }
            }

            sum += (cartMode != 1 ? $.hourPrice() : $.totalPrice());

            var multiplier = $.slotsMultiple() * $.priceMultiplier();
            sum = sum * multiplier;

            var exclPrice = $.calcExcluded();

            sum = sum - exclPrice[1];
            sum = sum + exclPrice[0];

            sum = $.hourDiscount(sum);
            $.disablePayments(sum);

            if (coupon) {
                sum = $.calculate(sum);
            }

            if (hours_or_cost) {
                $.showHours($.totalTime());
            }

            $.highlight('.total');
            $.showSum(sum);
        }

        $.getType = function (elem) {
            var name = elem.attr('name'),
                type = elem.attr('type'),
                price = 0;

            switch (type) {
                case 'select':
                    price = elem.find('option:selected').attr('price');
                    break;
                case 'radio':
                    price = $('input[name=' + name + ']:checked').attr('price');
                    break;
                default:
                    price = (elem.is(':checked')) ? elem.attr('price') : 0;
                    break;
            }

            return parseFloat(price);
        }

        $.slotsMultiple = function () {
            if (slots_multiply && sloten) {
                var times = $('.ssx').val();

                if ($.isInt(times)) {
                    return times;
                }
            }
            return 1;
        }

        $.priceMultiplier = function () {
            var ref = $('.rendered-form').find("[multiplier]");
            if (ref.length) {
                multiply = ref.find('option:selected').attr('price');
                return multiply;
            }
            return 1;
        }

        $.eachObject = function (object) {
            var prices = 0;
            var keys = Object.keys(object);

            $(keys).each(function (x, key) {
                prices += parseFloat(object[key]);
            });

            return prices;
        }

        $.calcExcluded = function () {
            var price = {};
            var multiplier = $.slotsMultiple() * $.priceMultiplier();

            $('[exclude=true]').each(function () {
                price[$(this).attr('name')] = $.getType($(this));
            });

            var excludePriceTotal = $.eachObject(price);

            return [excludePriceTotal, excludePriceTotal * multiplier];
        }

        $.hourDiscount = function (price) {
            discount = $.setVar('discount');
            var values = {};
            var fields = ['discount', 'value', 'operator', 'amount', 'field'];
            var compare = {price: price, minutes: $.totalTime(true)};
            var keys = Object.keys(discount);

            $(keys).each(function (x, key) {
                if (!jQuery.isEmptyObject(discount[key])) {
                    values = discount[key].rules[0];
                    if (operators[values['operator']](compare[discount[key].rules[0]['field']], values['value'])) {
                        price = $.calcType(price, values['amount'], values['discount']);
                    }
                }
            });

            return (price < 0 ? 0 : price);
        }

        $.calcType = function (total, amount, type) {
            if (type == 'percentage') {
                return ((100 - amount) * total) / 100;
            }
            else {
                return total - amount;
            }
        }

        $.calculate = function (total) {
            total = $.addZeroes(total);
            return $.calcType(total, cprice, type);
        }

        $.alterCouponPrices = function(discount) {
            $('.discount-line').slideDown();
            $('.total-price').addClass('line-through');
            var discounted = $.addZeroes($.calcType($('.total-order-sum').html(), cprice, type ));
            $('.discount-total-order-sum').html(discounted);
        }

        $.createSlots = function (reservedHours, timeObject) {
            var reservations = timeObject['reserved'], klass = 'slts', total = timeObject['slots'];
            if (total instanceof Object == true) {
                total = 1;
            }
            if (parseInt(reservations) >= parseInt(total)) {
                var klass = 'slts full';
            }
            return '<div class="' + klass + '">' + reservations + '/' + total + '</div>';
        }

        $.iterateDays = function (functions) {
            $('.day').each(function () {
                var day = $.regex($(this).attr('class'));
                functions(day, this);
            });
        }
        
        $.showAvailableTimes = function(elem, timePerDay) {
            if(elem)
            {
                var toolTipText = timePerDay + ' hour(s) for booking available';
                $(elem).find('.day-contents').attr('title', toolTipText).addClass('hasTip');
            }

        }

        $.eachT = function (variable, string) {
            var count = 0;
            $(Object.keys(variable)).each(function (i, val) {
                var amount = variable[val][string];
                if (amount instanceof Object == true) {
                    amount = 1;
                }
                count += parseInt(amount);
            });

            return count;
        }

        $.eachT = function (variable, string) {
            var count = 0;

            $(Object.keys(variable)).each(function (i, val) {
                var amount = variable[val][string];
                if (amount instanceof Object == true) {
                    amount = 1;
                }
                count += parseInt(amount);
            });

            return count;
        }

        $.checkAvailableTimes = function (elem, day) {
            var newHours = $.setVar('times'),
                newReserved = reservations,
                dates = (newHours[day] ? newHours[day] : newHours[moment(day).locale(locale).format('E')]);

            if (dates == 0 || $.isEmptyObject(dates)) {
                $(elem).addClass('blocked');
            }
        }

        $.saveTimes = function () {
            var dates = [];
            $(Object.keys(cart)).each(function (i, date) {
                $(Object.keys(cart[date])).each(function (n, time) {
                    if (date != 0) {
                        dates.push(date + ' ' + time + ':00');
                    }
                });
            });

            return dates;
        }

        $.addMissing0 = function (time) {
            return time;
            return time.replace('0:', '00:');
        }

        $.createDates = function () {
            var dates = $.saveTimes(), html = '';
            for (i = 0; i < dates.length; i++) {
                html += '<div class="date-list">' + $.addMissing0(dates[i]) + '</div>';
            }
            return html;
        }

        $.hmsToSecondsOnly = function (str) {
            var p = str.split(':');
            var hs, ms;
            hs = p[0] * 60 * 60;
            ms = p[1] * 60;
            return hs + ms;
        }

        $.displaySlots = function (elem) {
            if (sloten) {
                var slots_options = '';
                var maxSlots = $.getMin($('.time.selected'));
                if(maxSlots == 'Infinity') {
                    maxSlots = 1;
                }
                if (maxSlots < 1) {
                    slots_options = '<option>No slots</option>';
                } else {

                    $('#slots_av, .slots_av').slideDown();
                }
            }
        }

        $.getMin = function (elem) {
            var availableSlots = [];
            elem.each(function () {
                var reserved = $(this).data('reserved');
                var available = $(this).data('slots');
                var current = available - reserved;
                availableSlots.push(current);
            });
            return Math.min.apply(Math, availableSlots);
        }

        $.eachingTimes = function (functions) {
            $(Object.keys(cart)).each(function (i, date) {
                if (date != 0) {
                    $(Object.keys(cart[date])).each(function (n, time) {
                        functions(date, time);
                    });
                }
            });
        }

        $.checkBlocked = function () {
            $('.day').removeClass('blocked');
            $.iterateDays(function (day, elem) {
                $.isBanned(elem);
                $.checkAvailableTimes(elem, day);
                if(interval) {
                    $.checkInterval(elem, day);
                }
            });
        }

        $.totalTime = function (minutes) {
            var totalTimes = 0;
            $.eachingTimes(function (date, time) {
                var dateObject = $.defineDate(date);
                var first = dateObject[time]['time'];
                var last = dateObject[time]['timeUntil'];
                first = moment.locale(locale).utc(first, clockFormat);
                last = moment.locale(locale).utc(last, clockFormat);
                totalTimes = parseInt(totalTimes) + parseInt(+moment.locale(locale).duration(last.diff(first)).asMinutes());
            });

            return (minutes ? totalTimes : moment.locale(locale).utc().startOf('day').add(totalTimes, 'minutes').format(clockFormat));
        }

        $.highlightElems = function (start, end) {
            $('.time:eq(' + start + '):not(.unavailable)').addClass('selected');
            elms = 1;
            while (start < end) {
                elms++;
                start++;
                if ($.maxSlots()) {
                    $('.time:eq(' + start + '):not(.unavailable)').addClass('selected');
                }
            }
        }

        $.addFixed = function (elem) {
            $('.time').removeClass('selected');
            elem.addClass('selected');
            var num = min_slots - 1;
            var first = elem.parent().next();
            var back = elem.parent().prev();
            var first_num = Math.round(num / 2);
            var back_num = Math.floor(num / 2);

            for (i = 1; i <= first_num; i++) {
                if (!first.find('.time').hasClass('unavailable')) {
                    first.find('.time').addClass('selected');
                }
                first = first.next();
            }

            for (i = 1; i <= back_num; i++) {
                if (!back.find('.time').hasClass('unavailable')) {
                    back.find('.time').addClass('selected');
                }
                back = back.prev();
            }

            var length = $('.time.selected').length;
            if (length < min_slots) {
                $.warningAlert(min_slots_translate + ' ' + min_slots);
                $('.time.selected').removeClass('selected');
            } else {
                $.displaySlots($(this));
            }
        }

        $.totalPrice = function () {
            var slotPrices = 0;
            $.eachingTimes(function (date, time) {
                var dateObject = $.defineDate(date);
                var price = dateObject[time]['price'];
                slotPrices += parseInt(price);
            });

            return slotPrices;
        }

        $.hourPrice = function () {
            var totals = 0;
            $('.time.selected').each(function () {
                totals += $(this).data('price');
            });

            return totals;
        }

        $.getTimes = function () {
            var hours = {};

            if (cartMode != 1) {
                cart = {};
            }

            $('.time.selected').each(function () {
                hours[$(this).data('time')] = $(this).data('timeuntil');
            });

            if (!jQuery.isEmptyObject(hours)) {
                cart[$.todayDate()] = hours;
            }

            if (!jQuery.isEmptyObject(hours) && cartMode == 1) {
                $('#clear-time').slideDown();
            }
        }

        $.dayElement = function(date) {
            return $('.calendar-day-' + date);
        }

        $.displayTimes = function () {
            $('.time').removeClass('this selected da');
            $(Object.keys(cart)).each(function (i, date) {
                $('.calendar-day-' + date).addClass('booked');
                $(Object.keys(cart[date])).each(function (n, time) {
                    if (date == $.todayDate()) {
                        $('.time[data-time="' + time + '"]').addClass('this selected');
                    }
                });
            });
        }

        $.checkCoupon = function (code) {
            $.ajax({
                type: "GET",
                url: url + 'index.php?option=com_modern_tours&tmpl=component&task=checkcoupon&code=' + code + '&Itemid=' + itemid,
                success: function (response) {
                    if (response.success) {
                        type = response.data.type;
                        cprice = response.data.price;
                        coupon = 1;
                    }
                    $('.coupon_message').html(response.message).slideDown();
                    $.alterCouponPrices();
                }
            });
        }

        $.calcType = function (total, amount, type) {
            if (type == 'percentage') {
                return ((100 - amount) * total) / 100;
            }
            else {
                return total - amount;
            }
        }

        $.toHours = function (totalSeconds) {
            cc = Math.floor(totalSeconds / 3600);
            totalSeconds %= 3600;
            dd = Math.floor(totalSeconds / 60);
            cc = $.pad2(cc);
            dd = $.pad2(dd);
            if (cc == 00) {
                cc = 0;
            }
            if (dd == 00) {
                dd = 0;
            }

            return cc + ':' + dd;
        }

        $.defineDate = function (date) {
            currentHours = $.setVar('times');

            day = ($.checkDay(date) == 0 ? 7 : $.checkDay(date));
            var daynumber = moment(day).locale(locale).format('E');

            if (currentHours[$.regex(date)]) {
                return currentHours[$.regex(date)];
            }
            if (currentHours[daynumber]) {
                return currentHours[daynumber];
            }
        }

        $.disablePayments = function (price) {
            if (price == 0) {
                $("input[name=payment]:not(#cash)").prop("disabled", true);
                if (coupon) {
                    $(".payment").hide(555);
                }
            } else {
                $(".payment").show(555);
                $("input[name=payment]:not(#cash)").prop("disabled", false);
            }
        }

        $.doForm = function () {
            $('#payment-form').submit(function (e) {
                smbTxt = $('#stripe-pay').text();
                $('#stripe-pay').addClass('loadingas').html('<img src="' + url + 'media/com_modern_tours/img/ajax-loading.gif">');
                e.preventDefault();
                Stripe.setPublishableKey(public_key);
                Stripe.card.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val(),
                    name: $('.card-holder-name').val(),
                }, stripeResponseHandler);
            });

            function stripeResponseHandler(status, response) {
                var $form = $('#payment-form');
                if (response.error) {
                    $form.find('.payment-errors').text(response.error.message);
                    $form.find('button').prop('disabled', false); // Re-enable submission
                    alert(response.error.message);
                    $('#stripe-pay').removeClass('loadingas').html(smbTxt);
                } else {
                    var token = response.id;
                    $('#tok').html(token);
                    $.acceptPayment();
                }
            }
        }

        $.acceptPayment = function () {
            var form = $('.secret form');
            var url = $(form).attr('action');
            var data = $(form).serialize();
            var order_id = $('#order_id').val();
            $.ajax({
                type: "POST",
                url: 'index.php?option=com_modern_tours&task=payment.pay&method=stripe&Itemid=' + itemid + '&stripeToken=' + $('#tok').html() + '&order_id=' + order_id,
                success: function (data) {
                    $('.step').removeClass('active');
                    swal.close();
                    if (data == 'OK') {
                        swal({
                            title: translations['SUCCESSFUL_REGISTRATION'],
                            html: translations['CHECK_EMAIL'],
                            type: 'success',
                            timer: 3000
                        });
                        $('#success').addClass('active');
                        $('#loading-block').html(translations['RESERVATION_COMPLETE']);
                        $.disabled();
                    } else {
                        swal({
                            title: translations['ERROR_WITH_PAYMENT'],
                            html: translations['CONTACT_ADMINISTRATOR'],
                            type: 'error',
                            timer: 5000
                        });
                        $('#stripe-text, #loading-block').hide();
                        $('#confirm-booking, .wait-loading').show();
                    }
                }
            });
        }

        forbid_reservation = 1;

        $.checkInterval = function (elem, day) {
            var month = moment(day).format('M');
            var dday = moment(day).format('D');

            if(month > endMonth || month < startMonth || (month == endMonth && dday > endDay) || (month == startMonth && dday < startDay))
            {
                $(elem).addClass('blocked');
            }
        }

        $.isBanned = function (elem) {
            bannedHours = $.setVar('bandates');
            var date = $(elem).attr('class');
            for (i = 0; i < forbid_reservation; i++) {
                bannedHours[moment().locale(locale).add(i, 'day').format('YYYY-MM-DD')] = 1;
            }
            day = ($.checkDay(date) === 0 ? 7 : $.checkDay(date));
            if (bannedHours[day] || bannedHours[$.regex(date)]) {
                $(elem).addClass('blocked');
            }
        }

        $.setDayNames = function (locale) {
            $('.header-days .header-day').each(function(i,val) {
                $(this).html(moment(i+1, 'd').locale(locale).format('ddd'));
            });
        }

        $.courtains = function () {
            setTimeout(function () {
                $('.hiddexn').val(thank_you);
                $('.msgloading').fadeIn(666);
                $.disabled();
            }, 500);
        }

        $.checkDay = function (phrase) {
            var myRegexp = /(dow)-(.*)/;
            var match = myRegexp.exec(phrase);
            if (match) {
                return match[2];
            } else {
                return phrase;
            }
        }

        $.pad2 = function (number) {
            return (number < 10 ? '0' : '') + number
        }

        $.todayDate = function (withTime) {
            var today = $.regex($('.placeholder').attr('data-date'));

            if(withTime)
            {
                var startHour = $('.time.selected').attr('data-start-hour');
                var startMinute = $('.time.selected').attr('data-start-minutes');

                return today + ' ' + startHour + ':' + startMinute;
            }
            else
            {
                return today;
            }
        }

        $.validateForm = function(form) {
            if(form)
            {
                var valid = true;
                jQuery(form + ' input, ' + form + ' select, ' + form + ' textarea').each(function() {
                    var validField = jQuery(this).parsley().validate();
                    if(validField != true)
                    {
                        valid = false;

                    }
                });
                return valid;
            }
            else
            {
                return true;
            }
        }

        $.finalisePayment = function(data) {
            data = JSON.parse(data);
            var action = data.action;

            if(!action) {
                $('#loading-block').html(translations['RESERVATION_COMPLETE']);
            }

            if (action == 'redirect') {
                $('#payment-data').html(data.content).find('form').submit();
            }

            if (action == 'popup') {
                swal({
                    title: translations['ENTER_CARD_DETAILS'],
                    type: 'info',
                    html: '<div class="secret">' + data.content + '</div>',
                    showCloseButton: false,
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '',
                    width: '450px',
                    allowOutsideClick: false,
                    customClass: 'payment-modal'
                });
                $.doForm();
                $('#stripe-text').show();
                $('.wait-loading').hide();
            }
        }

        $.geneFields = function(number, form) {
            var fields = form.find('input, select');
            var cloneForm = form.clone();
            var clonedFields = fields.clone();
            var finalHTML = '';

            for (i = 0; i < number; i++) {
                clonedFields.each(function () {
                    var name = $(this).attr('id');
                    cloneForm.find('#' + name).attr('name', 'travellersData[' + i + '][' + name + ']');
                });
                finalHTML += '<li class="traveller-item">' + cloneForm.html() + '</li>';
            }

            $('.travellers-list .rendered-form').html(finalHTML);
        }

        $.getTypeValue = function (elem) {
            var name = elem.attr('name'),
                type = elem.attr('type');

            var item = {};

            switch (type) {
                case 'text':
                    value = $('input[name=' + name + ']').val();
                    break;
                case 'select':
                    value = elem.find('option:selected').val();
                    break;
                case 'radio':
                    value = $('input[name=' + name + ']:checked').val();
                    break;
                case 'checkbox':
                    value = (elem.is(':checked')) ? elem.val() : 0;
                    break;
                default:
                   break;
            }

            return value;
        }

        $.regex = function (phrase) {
            var myRegexp = /[0-9]{0,4}-[0-9]{0,2}-[0-9]{0,2}/;
            var match = myRegexp.exec(phrase);
            if (match) {
                return match[0];
            }
            else {
                return 0;
            }
        }

        $.isInt = function (n) {
            return n % 1 === 0;
        }

        $.IsJsonString = function (str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        }

        $.errorRedirect = function (url) {
            $.warningAlert('There\'s error with reservation. Now you will be redirected to error page, please send screenshot of that page to support@unikalus.com so we can help you. Click okay to procceed.');
            location.href = url;
        }

        $.isPositiveInteger = function (n) {
            return n >>> 0 === parseFloat(n);
        }

        $.showSum = function (sum) {
            $('.total').slideDown();
            $('.sum').html($.addZeroes(sum));
            setTimeout(function () {
                $('.currency').html(currency);
            }, 100);
        }

        $.showHours = function (hours) {
            $('.total').slideDown();
            $('.total .hours').html(hours);
        }

        $.headChange = function () {
            var momentDate = moment($.todayDate()).locale(locale);
            var month = momentDate.locale(locale).format('MMMM');
            var day = momentDate.locale(locale).format('Do');
            $('.calheader').html('<div class="calmonth">' + month + '</div><div class="calday">' + day + '</div>');
        }

        $.highlight = function (el) {
            $(el).addClass('highlight');
            setTimeout(function () {
                $(el).removeClass('highlight');
            }, 300);
        }

        $.minimumSlots = function () {
            if ($('.time.selected').length < min_res_slots || (mode === 2 || mode === 4)) {
                $.warningAlert(min_slots_translate + ' ' + min_res_slots);
            } else {
                return true;
            }
        }

        $.check = function () {
            if ($('#terms-services').length) {
                if (!$('#terms-services').is(':checked')) {
                    $.warningAlert(terms_services_alert);
                    return false;
                }
            }
            return true;
        }

        $.clearCart = function () {
            $('.booked').removeClass('booked');
            $('.time.selected').removeClass('selected');
            $('#clear-time').slideUp();
            cart = {};
        }

        $.disabled = function () {
            $('.calcon').addClass('disabled');
            $('.calcon input, .calcon select, .calcon textarea, .timecontainer .time').attr('disabled', 'disabled');
        }

        $.confirmDialog = function() {
            swal({
                title: CONFIRM_TEXT,
                html: $.createDates(),
                type: 'warning',
                confirmButtonClass: 'non-hide',
                showCancelButton: true,
                cancelButtonText: CANCEL,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: CONFIRM_BUTTON,
            }).then(function () {
                $.startBooking();
            })
        }

        $.warningAlert = function(text) {
            swal({
                title: '<i>Warning</i>',
                type: 'error',
                html: text,
                showCloseButton: true,
                showCancelButton: true,
                cancelButtonColor: '#d33',
                cancelButtonText: 'Close'
            });
        }

        $.success = function () {
            $('.success').show()
            setTimeout(function () {
                window.location.reload();
            }, 1200)
        }

        $.userEmail = function() {
            var form = $('#send-user-email');
            var url = $(form).attr('action');
            var data = $(form).serialize();
            $.ajax({
                type: "GET",
                data: data,
                url: url,
                success: function (response) {
                    if (response.success) {

                    }
                }
            });
        }

        // SLOT & DAY SELECTING


    })
})(jQuery);
(function ($) {
    $(function () {

        var currentHours = $.setVar('times'), bannedHours = $.setVar('bandates'), agentSelected, serviceSelected, dateSelected, warns = {1: select_agent, 2: select_service, 3: select_date, 4: fill_form}, types = {'SELECT': 1, 'INPUT': 1, 'TEXTAREA': 1}, cartMode = 0, discount, hovering = false, clickFixed = true;

        $('#rendered-form').formRender();
        $('.load').removeClass('active');

        if (step == 2) {
            agentSelected = true;
            $('.service').show();
        }

        moveToStep(step);

        $('body').on('click', '.next.active, .prev.active', function () {
            if ($('.step4').hasClass('act') && !checkForm()) {
                return false;
            }
            if ($('.step4').hasClass('act') && checkForm()) {
                $('.step5, .progress__step:nth-child(6)').removeClass('not-activated');
            }
            if ($('.step3').hasClass('act') && $(this).hasClass('next') && $('.time.selected').length < 1) {
                $.warningAlert(warns[3]);
                return false;
            }
            step = ($(this).hasClass('next') ? step + 1 : step - 1);
            moveToStep(step);
        });

        $('body').on('click', '.progress__step', function () {
            if ($(this).hasClass('not-activated')) {
                var index = $('.not-activated:not(.completed)').index() - 1;
                $.warningAlert(warns[index]);
                return false;
            }
            if ($(this).hasClass('active') && !$(this).hasClass('completed')) {
                return false;
            }
            step = $(this).index();
            moveToStep(step);
        });

        function checkForm() {
            var retur = true;
            var ref = $('#form1 .rendered-form').find("[required]");
            $(ref).each(function () {
                if (types[$(this).prop('nodeName')]) {
                    if ($($(this).prop('nodeName') + '[name="' + $(this).attr('name') + '"]').val() == '') {
                        $(this).focus();
                        retur = false;
                    }
                }
            });
            if (retur == false) {
                $.warningAlert('Required field should not be blank');
                return false;
            } else {
                return true;
            }
        }

        function setArrow(step) {
            $('.next, .prev').addClass('active');
            if (step == 1) {
                $('.prev').removeClass('active');
            } else if (step == 6) {
                $('.next').removeClass('active');
            }
        }

        function moveToStep(move) {
            if (only_services) {
                if (step == 2) {
                    $('.prev').hide();
                } else {
                    $('.prev').show();
                }
            }

            if (move == 3 || move == 4) {
                $('.next.btn').show();
            } else {
                $('.next.btn').hide();
            }

            if ($('.time.selected').length > 0 && move == 4) {
                $('.step4, .progress__step:nth-child(5)').removeClass('not-activated');
            }
            if (move == 5) {
                if (checkForm()) {
                    $('.step5, .progress__step:nth-child(5)').removeClass('not-activated');
                }
            }
            if (move == 3) {
                $('#day-s').fadeIn(555);
            } else {
                $('#day-s').fadeOut(555);
            }
            if (move == 5) {
                $('.confirm-btn').show();
                $('.btn.next').hide();
            } else {
                $('.confirm-btn').hide();
            }
            setArrow(move);
            $('.progress__step').removeClass('active').removeClass('completed');
            var curStep = $('.progress__step:first');
            if (move == 1) {
                curStep.addClass('active');
            } else {
                curStep.nextUntil('.progress__step:eq(' + step + ')').addClass('active');
                curStep.nextUntil('.progress__step:eq(' + (step - 1) + ')').addClass('completed');
                curStep.addClass('completed');
            }
            cutTitle();
            if (agentSelected) {
                $('.step2, .progress__step:nth-child(3)').removeClass('not-activated');
            }
            if (serviceSelected) {
                $('.step3, .progress__step:nth-child(4)').removeClass('not-activated');
            }
            jQuery('[timeslots="true"]').parent().hide();
        }

        function cutWords(text, klass) {
            var html = '';
            text = text.split(' ');
            for (i = 0; i < text.length; i++) {
                html += '<div class="word">' + text[i] + ' </div>';
            }
            $('.title--section').html(html);
            $('.word').addClass('animated ' + klass);
        }

        function cutTitle(text) {
            var height = $('.step' + step).height();
            $('.content').removeClass('act');
            $('.step' + step).addClass('act');
            $('#calendar-content').animate({'height': height});
            $('.tofade').addClass('fadeOutDown');
            $('.step' + step + ' .tofade').removeClass('fadeOutDown').addClass('fadeInUp');
            var words = $('.title--section').text();
            cutWords(words, 'fadeOutUp');
            setTimeout(function () {
                var title = titles[step];
                cutWords(title, 'fadeInDown');
            }, 400);
        }

        $('.agent').click(function () {
            var value = $(this).data('agent');
            var agentName = $('select[agents=true] option[agents=' + value + ']').val();
            $('select[agents=true]').val(agentName);
            var servicesList = services[value];
            $('.service').hide();
            $(Object.keys(servicesList)).each(function (i, val) {
                $('.service[id=' + val + ']').show();
            });
            agentSelected = true;
            $('select[timeslots=true]').val($('select[timeslots=true] option:not(.hidden)').val()).trigger('change');
        });

        $('body').on('click', '.paymethod', function () {
            $('.paymethod').removeClass('active');
            $(this).addClass('active');
            var payment = $(this).data('payment');
            $('input:radio[name=payment][value=' + payment + ']').prop("checked", true);
        });

        $('.service').click(function () {
            var id = $(this).attr('id');
            serviceSelected = true;
            $('#cln').removeClass('fadeOutDown').addClass('fadeInUp');
            $('#timetable, #day-s').removeClass('fadeInUp').addClass('fadeOutDown');
            $('select[timeslots=true] option[timetable=' + id + '], select[services=true] option[timetable=' + id + ']').prop('selected', true).trigger('change');
            $.getParams();
            $.checkBlocked();
        });

        $('body').on('click', '#cln .day:not(".past"):not(".blocked"), .progress__step:eq(2)', function () {
            setTimeout(function () {
                var daysLength = Object.keys($.scanDay()).length;
                var multiply = daysLength < 8 ? daysLength : 8;
                $('#calendar-content').animate({'height': multiply * 47});
            }, 1);
        });

        $('body').on('click', '.day:not(.past):not(.blocked), #day-s', function (e) {
            $('#timetable, #day-s').removeClass('fadeOutDown').addClass('fadeInUp');
            $('#cln').addClass('fadeOutDown');
        });

        $('.confirm-btn').click(function () {
            if($('#paymeths').is(":visible") && $('.paymethod.active').length == 0) {
                $.warningAlert('Please select payment method');
            } else {
                $('#form1').submit();
            }
        });

        $('.mCustomScrollbar').mCustomScrollbar();

        $.totalTime = function (minutes) {
            var totalTimes = 0;
            $.eachingTimes(function (date, time) {
                var dateObject = $.defineDate(date);
                var first = dateObject[time]['time'];
                var last = dateObject[time]['timeUntil'];
                first = moment.utc(first, "HH:mm");
                last = moment.utc(last, "HH:mm");
                totalTimes = parseInt(totalTimes) + parseInt(+moment.duration(last.diff(first)).asMinutes());
            });

            return (minutes ? totalTimes : moment.utc().startOf('day').add(totalTimes, 'minutes').format('HH:mm'));
        }

        function disablePayments(price) {
            if (price == 0) {
                $("#paymeths").hide(555);
            } else {
                $("#paymeths").show(555);
            }
        }

        function highlight(el) {
            $(el).addClass('highlight');
            setTimeout(function () {
                $(el).removeClass('highlight');
            }, 300);
        }

        function check() {
            if (!$('.time').hasClass('selected')) {
                alert(choose_time2);
                return false;
            }
            if ($('#terms-services').length) {
                if (!$('#terms-services').is(':checked')) {
                    alert(terms_services_alert);
                    return false;
                }
            }
            return true;
        }

        function disabled() {
            $('.calcon').addClass('disabled');
            $('.calcon input, .calcon select, .calcon textarea').attr('disabled', 'disabled');
        }

        function loading() {
            $('.hiddexn').val('Loading.');
            setTimeout(function () {
                $('.hiddexn').val('Loading..');
            }, 250);
            setTimeout(function () {
                $('.hiddexn').val('Loading...');
            }, 500);
        }

        $('body').on('change', '#ssx', function () {
            $.sum();
        });

        $('body').on('change keyup paste', '#coupon', (function (e) {
            $.checkCoupon($(this).val());
        }));

        $('.overlay').click(function () {
            $('.overlay, .loading').removeClass('add');
            setTimeout(function () {
                $('.loading').css({'visibility': 'hidden'});
            }, 500);
            location.reload();
        });

        function addTimes() {
            var dates = [];
            var dateClass = $('.day.selected').attr('class');
            var year = dateClass.match(/([0-9]+-[0-9]+-[0-9]+)/g);
            $('.time.selected').each(function () {
                var hour = $(this).data('time') + ':00';
                var date = year + ' ' + hour;
                dates.push(date);
            });
            $('input[name=start]').val(dates.join(','));
        }

        function courtains() {
            setTimeout(function () {
                $('.hiddexn').val(thank_you);
                $('.calcon h3').fadeOut(666);
                $('.msgloading').fadeIn(666);
            }, 500);
        }

        $('body').on('submit', '#form1', (function (e) {
            var payment = $('input[name=payment]:checked').val();
            addTimes();
            var start = $('input[name=start]').val();
            var end_time = $('.time.selected:last').data('timeuntil');
            console.log(end_time);
            $('input[name=end_time]').val(end_time);
            // if (!start || start == 0) {
            //     var date = $.regex($('.day.selected').attr('class'));
            //     var hour = $('.time.selected').data('hour');
            //     $('input[name=start]').val(date + ' ' + hour);
            // }
            if (payment == 'mollie') {

            } else {
                e.preventDefault();
                if (check()) {
                    loading();
                    $('.secretas').show();
                    $('.overlay').show();
                    $('.loading').css({'visibility': 'visible'});
                    var email = $('input[type=email]').val();
                    data = $(this).serialize();
                    $.ajax({
                        type: "GET",
                        url: url + 'index.php?' + data,
                        success: function (data) {
                            console.log('disabled');
                            var data = JSON.parse(data);
                            var paymentContent = data.content;
                            var action = data.action;
                            $('.secretas').hide();
                            if (action == 'popup') {
                                swal({
                                    title: '<i>Enter your card details</i>',
                                    type: 'info',
                                    html: '<div class="secret">' + paymentContent + '</div>',
                                    showCloseButton: false,
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: '',
                                    customClass: 'forma'
                                });
                                $.doForm();
                                $('.secretas, .overlay2').hide();
                            }
                            else {
                                action = (!action ? 'cash' : action);
                                (action == 'cash' || action == '' ? $.success() : $('.redirect').show());
                                $('.secret').html(paymentContent);
                                $('.secret').find('form').submit();
                                courtains();
                                $('.overlay, .loading').addClass('add');
                                // var visibleStep = $('.col-xs-12.lg25.progress__step:eq(1)').is(':visible');
                                // step = !visibleStep ? 1 : 2;
                                // moveToStep(step);
                                // addBooking();
                                disabled();
                            }
                        }
                    });
                }
            }
        }));

        $.doForm = function () {
            $.sum();
            $('#payment-form').submit(function (e) {
                smbTxt = $('#stripe-pay').text();
                $('#stripe-pay').addClass('loadingas').html('<img src="' + url + 'media/com_modern_tours/img/loading.gif">');
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
                    $('#token').html(token);
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
                url: 'index.php?option=com_modern_tours&task=payment.pay&method=stripe&Itemid=' + itemid + '&stripeToken=' + $('#token').html() + '&order_id=' + order_id,
                success: function (data) {
                    $('.step').removeClass('active');
                    swal.close();
                    if (data == 'OK') {
                        swal({
                            title: 'You have successfully registered',
                            html: 'Check your email for more information',
                            type: 'success'
                        });
                        $('#success').addClass('active');
                        disabled();
                        setTimeout(function () {
                            window.location.reload();
                        }, 1200);
                    } else {
                        swal({
                            title: 'There\'s error within payment',
                            html: 'Please contact adminstrator',
                            type: 'error'
                        });
                    }
                }
            });
        }

        var clndr = $('#cln').clndr({
            forceSixRows: true,
            daysOfTheWeek: months,
            adjacentDaysChangeMonth: false,
            weekOffset: 1,
            doneRendering: function () {
                $.checkBlocked();
                $('input[name=start]').val(0);
            },
            clickEvents: {
                onMonthChange: function (month) {
                    $.checkBlocked();
                }
            }
        });

        $('.form-group input[type=checkbox], .form-group input[type=radio], .form-group select').click(function () {
            $.getElems();
        });

        $('#modal').click(function () {
            $('#modal').removeClass('md-show');
            $('#modal').attr('class', "md-modal md-effect-1");
            $('.overlay2').fadeOut(333);
        });

        $('body').on('click', '.slider-block .time', function () {
            $('.block-content').removeClass('selected');
            $(this).parent().addClass('selected');
        });

        $('body').on('click', '.day:not(.past):not(.blocked)', function () {
            $('.day').removeClass('selected');
            $(this).addClass('selected');
            $.headChange();
            $.addReserved();
            $('.day_clnd').html(moment($.todayDate()).format('DD'));
            $('.slots_av').slideUp();
            $.sum();
            selecta = false;
            $('#cln').addClass('fadeOutDown');
            $('#timetable, #day-s').removeClass('fadeOutDown').addClass('fadeInUp');
        });

        $('body').on('click', '.time:not(.unavailable):not(.selected)', function () {
            if (!clickFixed && mode == 2) {
                $('.selected.time').removeClass('selected');
            }
            if (mode != 3) {
                $('.time').find('.reserve').hide();
                if (!hovering) {
                    $('.time').removeClass('selected da');
                    hovering = true;
                }
                if (mode == 1) {
                    $('.time').removeClass('selected da');
                    $(this).find('.reserve').show();
                }
                $(this).addClass('selected');
                $.sum();
                if (mode == 4) {
                    if (!$.maxSlots()) {
                        $(this).removeClass('selected');
                    }
                }
                if (mode == 2) {
                    setTimeout(function () {
                        if (!$.maxSlots()) {
                            $('.time').removeClass('selected da');
                        }
                    }, 11);
                }
            }
        });

        $('body').on('mouseover', '.time:not(.unavailable)', function () {
            if (mode == 3 && clickFixed) {
                $('.time').removeClass('hover');
                $(this).addClass('hover');
                var num = min_slots - 1;
                var first = $(this).parent().next();
                var back = $(this).parent().prev();
                var first_num = Math.round(num / 2);
                var back_num = Math.floor(num / 2);
                first_num = num;
                for (i = 1; i <= first_num; i++) {
                    if (!first.find('.time').hasClass('unavailable')) {
                        first.find('.time').addClass('hover');
                    }
                    first = first.next();
                }

                var hovered = $('.time.hover').length;
                var last = parseInt($('.time').length - 1);
                if ($('.time.hover').length < num) {
                    for (i = 0; i <= num; i++) {
                        $('.time:eq(' + (last - i) + ')').addClass('hover');
                    }
                }
            }
            else {

                if (mode == 2) {
                    $('.time').find('.reserve').hide();
                    if (hovering) {
                        $('.time').removeClass('selected da');
                        var end = $(this).parent().index();
                        $.highlightElems(indexas, end);
                        $.sum();
                    }
                }
            }
        });

        $('body').on('click', '.time.hover', function () {
            $('.time').removeClass('selected');
            $('.time.hover').addClass('selected');
            $.sum();
        });

        $('body').on('click', '.time:not(.unavailable)', function () {
            if (mode != 1) {
                indexas = $(this).parent().index();
            }
            $.displaySlots($(this));
        });

        $('body').on('click', '.time.selected', function () {
            if (mode != 3) {
                $('.time.selected').addClass('da');
                if (mode == 2) {
                    hovering = hovering ? hovering = false : $('.time').removeClass('selected');
                }
                if (mode == 4) {
                    $(this).removeClass('selected');
                }
                $('.time').find('.reserve').hide();
            }
            $.sum();
        });
    })
})(jQuery);
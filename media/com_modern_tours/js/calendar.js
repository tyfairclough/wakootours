(function ($) {
    $(function () {

        var clickFixed = true, hovering = false;

        $('.load').removeClass('active');
        $("#owl-example").owlCarousel({
            items: 1, nav: true, loop: true, navText: [
                "<i class='fa fa-chevron-left'></i>",
                "<i class='fa fa-chevron-right'></i>"
            ]
        });

        var owl = $("#owl-text").owlCarousel({items: 1, nav: false, loop: true, drag: false, mouseDrag: false});

        $('.owl-next').click(function () {
            owl.trigger('next.owl.carousel');
        });

        $('.owl-prev').click(function () {
            owl.trigger('prev.owl.carousel');
        });

        owl.on('translated.owl.carousel', function () {
            var value = $('#owl-example').find('.active .service').data('value');
            $('select[timeslots=true]').val(value);
            $('select[timeslots=true]').change();
        });

        $('body').on('change', 'select[timeslots=true]', function () {
            $.addReserved();
            $.getParams();
        });

        $('body').on('change', 'select[agents=true]', function () {
            if(!$('select[timeslots=true]').length) {
                $.addReserved();
                $.getParams();
            }
        });

        $('body').on('change', 'select[agents=true]', function () {
            var value = $("option:selected", this).attr('agents');
            var servicesList = services[value];
            $('select[timeslots=true] option').each(function () {
                (!$.isInt(servicesList[$(this).attr('timetable')]) ? $(this).addClass('hidden') : $(this).removeClass('hidden'));
            });
            $('select[timeslots=true]').val($('select[timeslots=true] option:not(.hidden)').val()).trigger('change');
        });

        $('body').on('change', '.ssx', function () {
            $.sum();
        });

        function show() {
            $('.overlay2').fadeIn(333);
            $('#modal').addClass('md-show');
        }

        function addLocales() {
            var momentDate = moment().locale(locale);
            var year = momentDate.format('YYYY');
            var month = momentDate.format('MMMM');
            var day = momentDate.format('Do');
            var datex = moment(month._d).locale(locale).format('MMMM');
            $('.calheader').html('<div class="calmonth">' + month + '</div><div class="calday">' + day + '</div>');
            $('.month').html('<span class="clndr-previous-button btx"></span>' + datex + ' <span class="year">' + year + '</span> <span class="clndr-next-button btx"></span>');
            $('.clndr-controls').append('<div class="day_clnd"></div>');
        }

        $('body').on('change keyup paste', '#coupon', (function (e) {
            $.checkCoupon($(this).val());
        }));

        $('body').on('click', '.reserve', (function (e) {
            $('.selected').addClass('da');
        }));

        $('.overlay').click(function () {
            $('.overlay, .loading').removeClass('add');
        });

        $('body').on('submit', '.ajax-form', (function (e) {
            e.preventDefault();
            if(cartMode == 1) {
                $.confirmDialog();
            }  else {
                !$.minimumSlots() ? '' : $.startBooking();
            }
        }));

        $('.calendar').each(function() {
            var fbRender = $('#form-render');
            var formRenderOpts = { 'formData' : forms, dataType: 'xml' };
            fbRender.formRender(formRenderOpts);
            makeClndr();
        });

        function makeClndr(elem) {
            console.log('Make clndr');
            $('.mCustomScrollbar').mCustomScrollbar();
            $('.calendar-monthly').clndr({
                daysOfTheWeek: months,
                adjacentDaysChangeMonth: true,
                weekOffset: 1,
                forceSixRows: true,
                doneRendering: function (e, r) {
                    addLocales();
                    $('.day_clnd').html(day);
                    $.checkBlocked();
                    setTimeout(function () {
                        var today = $('.day:not(.adjacent-month):not(.blocked):not(.past):first');
                        (today.length > 0 ? today.click() : $('.laikas').html(''));
                    }, 551);
                    $.getElems();
                    $.getParams();
                },
                clickEvents: {
                    onMonthChange: function (month) {
                        var datem = moment(month._d).locale(locale).format('MMMM');
                        var year = moment(month._d).locale(locale).format('YYYY');
                        $('.month').html('<span class="clndr-previous-button btx"></span>' + datem + ' <span class="year">' + year + '</span> <span class="clndr-next-button btx"></span>');
                    }
                }
            });
        }


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
                num = num+1;
                var hovered = $('.time.hover').length;
                var last = parseInt($('.time').length);
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
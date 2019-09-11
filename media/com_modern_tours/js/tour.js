(function ($) {
    $(function () {

            // $('#open-calendar').click();
            // setTimeout(function () {
            //     $('.calendar-day-2019-02-16').click();
            // }, 111);
            // setTimeout(function () {
            //     $('.time').click();
            // }, 222);
            // setTimeout(function () {
            //     $('#adults').val(1).click().change();
            // }, 333);
            // setTimeout(function () {
            //     $('#book-now').click();
            //
            // }, 555);

        var newTips = new Tips($$('.isDisabled'));
        // $('#cover-carousel').owlCarousel({'width': '100%', 'autoplay': true, 'items': 1, singleItem:true, margin: 0, animateOut: 'fadeOut'});


        $('#user-data-form').formRender({'formData': userData, dataType: 'xml'});
        if(emailFields)
        {
            $('#email-form').formRender({'formData': emailFields, dataType: 'xml'});
        }
        if(travellersData)
        {
            $('#travellers-data-form').formRender({'formData': travellersData, dataType: 'xml'});
        }
        $("#keeper").stick_in_parent();
        $('#lightgallery').lightGallery({ download: false });


        $('body').on('change keyup paste', '#coupon-input', (function (e) {
            $.checkCoupon($(this).val());
        }));

        $('#gogo').click(function() {
            swal({
                type: 'info',
                customClass: 'send-email-form',
                html: $('.email-form').html(),
                showCloseButton: true,
                showConfirmButton: true,
                focusConfirm: false,
                confirmButtonText:
                    '<i class="fa fa-envelope"></i> ' + translations['CONFIRM_BUTTON_TEXT'],
                cancelButtonText: translations['CANCEL_BUTTON_TEXT'],
                confirmButtonTetx: 'Send Email',
                showCancelButton: true,
                cancelButtonColor: '#d33'
            }).then(function(result) {
                if(result.value)
                {
                    $.userEmail();
                }
            });
        });

        // if(force_deposit) {
        //     $('input[name="deposit"]').val(1);
        // }

        // var form, startdate = interval ? moment().format('YYYY') + '-' + startMonth + '-' + startDay : moment();
        var form, startdate = interval ? moment().format('YYYY') + '-' + (moment().month()+1) + '-' + startDay : moment();
        console.log("start date is: " + startdate);
        // startdate = "2019-9-1";

        setTimeout(function () { form = $('#ccc .rendered-form'); }, 1555);

        $('#view-gallery, #view-photos').click(function () {
            $('#lightgallery a').click();
        });

        $('body').on('click', '.payment-modal .swal2-close', function () {
            $('#stripe-text, #loading-block').hide();
            $('#confirm-booking, .wait-loading').show();
        });

        $('#deposit .deposit-button').click(function() {
            $('.deposit-button').removeClass('active');
            $(this).addClass('active');

            if($(this).hasClass('deposit-price'))
            {
                $('.deposit-line').slideDown();
                $('.total-price').addClass('line-through');
                $('input[name="deposit"]').val(1);
            }
            else
            {
                $('.deposit-line').slideUp();
                $('.total-price').removeClass('line-through');
                $('input[name="deposit"]').val(0);
            }
        });

        $('#clndr').clndr({
            adjacentDaysChangeMonth: true,
            weekOffset: 1,
            startWithMonth: startdate,
            forceSixRows: true,
            clickEvents: {
                click: function (target) {
                    var date = moment(target.date._i).locale(locale).format('MMMM DD');
                    $('.placeholder').html(date).attr('data-date', moment(target.date._i).locale(locale).format('YYYY-MM-DD'));
                    $('#clndr').hide();
                    $('.overlay').removeClass('fadeIn').delay(250).remove();
                    $.displayHours();
                    $.checkBlocked();
                    $('.tip-wrap').slideUp(666, function(){
                        $(this).remove();
                    });
                    $('.isDisabled').show();
                },
                onMonthChange: function (month) {
                    $('.month').html(moment(month).locale(locale).format('MMMM YYYY'));
                    $.setDayNames(locale);
                    $.checkBlocked();
                    $.showAvailableTimes();
                    $.blockDaysWithoutSlots();
                }
            },
            doneRendering: function () {
                $('.month').html(moment(startdate).locale(locale).format('MMMM YYYY'));
                $.setDayNames(locale);
                $.checkBlocked();
                $.showAvailableTimes();
                $.blockDaysWithoutSlots();
            }
        });

        $('body').on('click', '.time', function () {
            $('.time').removeClass('selected');
            $(this).addClass('selected');
            var date = $.todayDate();
            $.tourLength(date);
            $('#children, #adults').slideDown();
            $.allFilled();
            $('.isDisabled').hide();
            $.hideOptions();

        });

        $('#open-calendar').click(function () {

            $('div#clndr').show();
            $('.overlay').remove();
            $('body').append('<div class="overlay"><div class="close">x</div></div>');
            setTimeout(function () {
                $('.overlay').addClass('show-overlay');
            }, 1);
            $.allFilled();
        });

        $('.itenary-title').click(function () {
            var content = $(this).next('.itenary-content');
            if(!content.is(':visible'))
            {
                content.slideDown(500);
            }
            else
            {
                content.slideUp(500);
            }
        });

        $('body').on('click', '.overlay', function () {
            $('div#clndr').hide();
            $('.overlay').remove();
        });

        $('#booking-data').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
            $('.bs-callout-info').toggleClass('hidden', !ok);
            $('.bs-callout-warning').toggleClass('hidden', ok);
        });

        $('.next-btn, .prev-btn').click(function () {
            var stepContainer = $(this).parents('.checkout-step');
            var curStep = stepContainer.index();

            if($.validateForm($(this).attr('validate')))
            {
                stepContainer.hide();

                if($(this).hasClass('prev-btn'))
                {
                    stepContainer.prev().show();
                    curStep = curStep - 1;
                }
                else
                {
                    stepContainer.next().show();
                    curStep = curStep + 1;
                }

                $('.asset-steps li').removeClass('active');
                $('.asset-steps li:eq(' + curStep + ')').addClass('active');
            }
        });


        $('.close').click(function () {
            $('.checkout-modal').fadeOut(222);
            $('body').removeClass('no-overflow');
        });

        $('#adults').change(function () {
            var value = $(this).val();
            $('.singular-adult, .plural-adult').hide();
            if (value > 0) {
                $('.participant-line-adult').show();
                if (value == 1) {
                    $('.singular-adult').show();
                }
                else {
                    $('.adult-count').html(value);
                    $('.plural-adult').show();
                }
            } else {
                $('.participant-line-adult').hide();
            }
            $('[name="adults"]').val(value);
        });

        $('#children').change(function () {
            var value = $(this).val();
            $('.singular-child, .plural-child').hide();
            if (value > 0) {
                $('.participant-line-child').show();
                if (value == 1) {
                    $('.singular-child').show();
                }
                else {
                    $('.children-count').html(value);
                    $('.plural-child').show();
                }
            } else {
                $('.participant-line-child').hide();
            }
            $('[name="children"]').val(value);
        });

        $('#adults, #children').change(function () {
            var value1 = $('#adults').val();
            var value2 = $('#children').val();
            var participants = parseInt(value1) + parseInt(value2);
            $('.singular-participant, .plural-participant').hide();

            if (participants === 1) {
                $('.singular-participant').show();
            }
            else {
                $('.plural-participant').show();
                $('.participant-count').html(participants);
            }
            $('#participants').html(participants);
            $.allFilled();
            $.checkSlots();
        });

        $('#booking-data').submit(function (e) {
            e.preventDefault();
        });

        $('.confirm-btn').click(function () {
            if(!$('#payment').val())
            {
                $('.step3').show();
                $.warningAlert(translations['CHOOSE_PAYMENT_METHOD']);
                return false;
            }
            var form = $('#booking-data');
            var url = form.attr('action');
            var data = form.serialize();
            $('#loading-block, .step3').show();
            $('div#confirm-booking').hide();
            data += '&price=' + $('.total-order-sum').html();
            data += '&people=' + $('#participants').html();
            data += '&starttime=' + $.todayDate(true);

            $.ajax({
                type: "GET",
                data: data,
                url: url,
                success: function (data) {
                    if (!$.IsJsonString(data)) {
                        $.errorRedirect('');
                    }
                    $.finalisePayment(data);
                }
            });
        });

        $('body').on('click', '.time.sold-out', function () {
            swal({
                title: translations['SOLD_OUT_CLICKED'],
                type: 'warning',
                html: translations['SOLD_OUT_CLICKED_DESCRIPTION'],
                showCloseButton: true,
                showCancelButton: true,
                cancelButtonColor: '#d33',
                cancelButtonText: translations['SOLD_OUT_OKAY']
            });
        });

        $('#book-now').click(function (e) {
          console.log("start date is: " + startdate);
          alert("hi world")
            e.preventDefault();
            var value1 = $('#adults').val();
            var value2 = $('#children').val();
            var totalParticipants = parseInt(value1) + parseInt(value2);
            var date = $('#open-calendar').val();

            if (value1 == 0 && value2 == 0) {
                $.warningAlert(translations['SELECT_PARTICIPANTS']);
                return false;
            }

            if(!$.checkSlots()) {
                return false;
            }

            if (!$.todayDate() || $('.time.selected.available').length === 0) {
                $.warningAlert(translations['SELECT_DATE']);
                return false;
            }

            $('.checkout-modal').fadeIn(666);
            $('body').addClass('no-overflow');
            $.calculatePrice();
            if(travellersData)
            {
                $.geneFields(totalParticipants, form);
            }
            if(force_deposit)
            {
                $('.deposit-price').click();
            }
        });

    })
})(jQuery);

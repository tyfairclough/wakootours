jQuery(document).ready(function() {

    jQuery('.cancel-booking').click(function() {
        var url = '?option=com_modern_tours&task=cancelBooking';
        var id = jQuery(this).data('id');
        jQuery(this).parents('.booking-inner').find('.booking-status').html(CANCELED);
        ajax(url, id, jQuery(this));
        alertSuccess(SUCCESSFUL, SUCCESSFULLY_CANCELED);
    });

    jQuery('.review-action').click(function() {
        var url = '?option=com_modern_tours&task=deleteReview';
        var id = jQuery(this).data('id');
        ajax(url, id, jQuery(this));
        jQuery(this).parents('.asset-booking').hide(500);
    });

    function alertSuccess(title, message)
    {
        swal({
            title: title,
            html: message,
            type: 'success'
        });
    }

    function ajax(url, id, btn)
    {
        btn.html(PLEASE_WAIT);
        jQuery.ajax({
            type: "GET",
            data: 'id=' + id,
            url: url,
            success: function (response) {
                btn.html(BOOKING_CANCELED);
            }
        });
    }
});


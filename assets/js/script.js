;(function($){

    $('#avur-registration-form').on('submit', function(e){

        e.preventDefault();

        data = $(this).serializeArray();
        
        $.ajax({
            type: 'POST',
            url: avurForm.ajaxUrl,
            data: {
                action: "avur_user_registration",
                _nonce: avurForm.avur_nonce,
                data: data,
            },
            beforeSend: function () {
            },
            success: function (response) {
                if ( response.data.error ) {
                    $('.avur-error-message').show();
                    $('.avur-error-message').html( response.data.error );
                }
                if ( response.data.message ) {
                    $('#avur-registration-form').html(`<div class="avur-success-message">${response.data.message}</div>`);
                }
            },
            error: function (data) {
                $('.avur-error-message').show();
                $('.avur-error-message').html( 'Something went wrong! Please try again later.' );
            }
        });
    });

    $('#avur-registration-form').on('change, input', function(e){
        $('.avur-error-message').hide();
    });

})(jQuery);
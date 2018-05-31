require(['jquery'], function($) {
    $('.testimonial-form-button').click(function () {
       $('.testimonials-form-wrapper form').toggle('fast');
    });

    $('.testimonial-modal-button').click(function () {
        $('.testimonials-block .login-wrapper').toggle();
    });
    $('.testimonials-block .login-container .modal-close').click(function () {
        $('.testimonials-block .login-wrapper').toggle();
    });
});

require([
    "jquery",
    "mage/mage"
],function($) {
    $(document).ready(function() {
        $('.testimonial-loading').hide();
        $('#testimonials-form-data').mage(
            'validation',
            {
                submitHandler: function() {
                    $.ajax({
                        url: "index/store/",
                        data: $('#testimonials-form-data').serialize(),
                        type: 'POST',
                        success: function() {
                            $('.testimonial-loading').hide();
                            $('.testimonials-form-wrapper textarea').val('');
                        },
                        error: function (xhr, status, errorThrown) {
                            console.log('Error happens. Try again.');
                            console.log(errorThrown);
                        },
                        complete: function () {
                            $('.testimonials-form-wrapper form').toggle('fast');

                        }
                    });
                }
            }
        );
    });
});
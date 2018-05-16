require(['jquery'], function($) {
    jQuery('.testimonial-form-button').click(function () {
       jQuery('.testimonials-form-wrapper form').toggle('fast');
    });

    jQuery('.testimonial-modal-button').click(function () {
        jQuery('.testimonials-block .login-wrapper').toggle();
    });
    jQuery('.testimonials-block .login-container .modal-close').click(function () {
        jQuery('.testimonials-block .login-wrapper').toggle();
    });


});
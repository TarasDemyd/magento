require(['jquery'], function($) {
    jQuery('.testimonial-button').click(function () {
       jQuery('.testimonials-form-wrapper form').toggle('fast');
    });


});
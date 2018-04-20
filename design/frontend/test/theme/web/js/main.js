require(['jquery'], function($) {
    jQuery(".block.widget .products-grid .product-items").owlCarousel({
        loop: true,
        responsiveClass: true,
        autoplay: true,
        autoplayHoverPause: true,
        nav: true,
        navText: [],
        dots: true,
        margin: 10,
        responsive:{
            0:{
                items: 1,
                nav: false
            },
            425:{
                items: 2,
                nav: false
            },
            768:{
                items: 3,
                nav: false
            },
            1000:{
                items: 4,
                dots: false,
                stagePadding: 10
            }
        }
    });


});



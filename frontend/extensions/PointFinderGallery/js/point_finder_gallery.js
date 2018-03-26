$(function() {
    'use strict';

    $('.owl-carousel').each(function () {
        $(this).owlCarousel({
            items: 4,
            navigation: true,
            pagination: true,
            autoPlay: true,
            stopOnHover: true,
            slideSpeed: 500,
            mouseDrag: true,
            touchDrag: true,
            itemSpaceWidth: 17,
            itemBorderWidth: 0,
            autoHeight: false,
            responsive: true,
            itemsScaleUp: false,
            navigationText: false,
            theme: 'owl-theme',
            singleItem: false,
            itemsDesktop: [1210, 3],
            itemsTablet: [990, 2],
            itemsTabletSmall: false,
            itemsMobile: [479, 1]
        });
    });
});
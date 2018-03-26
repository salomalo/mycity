$(function () {
    'use strict';
    
    var quantity = $('#business-quantity_rating');
    var total = $('#business-total_rating');
    var rating = $('input[name="rating"]');

    quantity.on('input', function (e) {
        changeStars();
    });
    total.on('input', function (e) {
        changeStars();
    });

    var changeStars = function () {
        var q = parseInt(quantity.val());
        var t = parseInt(total.val());
        var rate = (q === 0) ? 0 : (t / q);
        rating.rating('update', rate);
    };
});
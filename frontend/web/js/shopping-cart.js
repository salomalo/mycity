$(function () {
    'use strict';

    var checkTotal = function checkTotal() {
        var total_sum = 0;
        $('.cena_sum').each(function () {
            total_sum = total_sum + parseInt($(this).text());
        });
        $('.cena_total').text(total_sum);
    };

    checkTotal();

    $('.cena input').on("change", function (e) {
        e.preventDefault();

        var value = parseInt($(this).val());
        var id = $(this).data('id');

        $.ajax('/shopping-cart/update-shopping-cart', {
            type: 'post',
            dataType: 'json',
            data: {id: id, count: value},
            success: function (data) {
                $(this).val(data);
                checkTotal();
            }
        });

        var parent = $(this).parent().parent().parent();
        var cena_one = parent.find('.cena_one').text();

        parent.find('.cena_sum').text(value * cena_one);

        checkTotal();
    });
});
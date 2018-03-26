$(function($) {
    'use strict';

    //Всплывающие окно для отзывов магазина
    $( ".b-review-info__link" ).hover(
        function() {
            $('#popup_Z2D9FB9CA-491D-4938-B42A-490E28149888').removeClass( "hidden" );
        }, function() {
            $('#popup_Z2D9FB9CA-491D-4938-B42A-490E28149888').addClass( "hidden" );
        }
    );

    $( "#popup_Z2D9FB9CA-491D-4938-B42A-490E28149888" ).hover(
        function() {
            $('#popup_Z2D9FB9CA-491D-4938-B42A-490E28149888').removeClass( "hidden" );
        }, function() {
            $('#popup_Z2D9FB9CA-491D-4938-B42A-490E28149888').addClass( "hidden" );
        }
    );

    //Ограничуем тело отзыва к магазину на 500 символов
    $('#comment-text').on('keyup', function() {
        var outText = $('.b-text-hint__length-counter');
        var maxLength = outText.attr('data-maxlength-max');
        var curLength = $(this).val().length;

        $(this).val($(this).val().substr(0, maxLength));

        var remaning = maxLength - curLength;
        if (remaning < 0) remaning = 0;
        outText.html(remaning);
    });
});

$(function($) {
    'use strict';

    $('.btn-add-to-wish-list').on('click', function(e) {
        e.preventDefault();

        var toggler = $(this);
        var action = toggler.hasClass('marked') ? 'remove' : 'add';
        var idProduct = toggler.data('listing-id');
        var data = {action: action, id: idProduct, type: toggler.data('type')};

        $.ajax(toggler.data('ajax-url'), {data: data}).done(function () {
            var listSameWishBtn = $('[data-listing-id="' + idProduct + '"]');

            listSameWishBtn.each(function() {
                var currentItem = $(this);

                currentItem.toggleClass('marked');
                var span = currentItem.children('span');
                span.data('toggle', span.text());
                span.text(span.data('toggle'));
            });
        });
    });
});
$(function () {
    'use strict';
    
    var cityPopup = $('div.city-popup');

    //Открытие попапа при нажатии на город
    $('a.show-city-popup').on('click', function (e) {
        e.preventDefault();
        cityPopup.show();
    });

    //Скрытие попапа при клике вне окошка
    $(document).on('click', function (e) {
        var item = $(e.target);

        if (!(item.hasClass('show-city-popup') || item.hasClass('city-popup') || item.parent().hasClass('city-popup'))) {
            cityPopup.hide();
        }
    });
});
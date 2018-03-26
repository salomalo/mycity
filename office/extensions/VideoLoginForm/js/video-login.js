$(function () {
    'use strict';
    $('#video').YTPlayer();

    $('#field_city_select').select2({
        placeholder: 'Город:',
        formatNoMatches: 'No result found!',
        allowClear: true
    });

    $('#field_category_select').select2({
        placeholder: 'Категория:',
        formatNoMatches: 'No result found!',
        allowClear: true
    });
});

$(function () {
    'use strict';

    var container = $('.pf-container');

    var setLeftToContainer = function () {
        var left = (window.innerWidth - container.width()) / 2;
        container.css('left', left + 'px');
    };

    setLeftToContainer();

    $(window).on('resize', setLeftToContainer);
});
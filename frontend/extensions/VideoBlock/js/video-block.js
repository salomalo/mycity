$(function () {
    'use strict';
    
    $('#video').YTPlayer();

    var inputs = [$('#field_city_select'), $('#field_category_select')];

    inputs.forEach(function (selector, index, array) {
        selector.select2({
            placeholder: selector.data('pf-plc'),
            formatNoMatches: 'No result found!',
            allowClear: true
        });
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
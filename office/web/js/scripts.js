$(document).ready(function () {
    'use strict';

    $('#post-button').click(function(e){
        e.preventDefault();
        var a = $(this);
        var isConfirm = confirm(a.data('confirm'));

        if (isConfirm) {
            $.ajax(a.attr('href'), {
                method: 'POST',
                statusCode: {
                    302: function () {
                        a.parent().parent().remove();
                    }
                }
            });
        }
    });


    var initSelect2 = function (select2, config, data) {
        return select2.html('').select2({
            data: data,
            allowClear: config.allowClear,
            closeOnSelect: config.closeOnSelect,
            dropdownAutoWidth: config.dropdownAutoWidth,
            maximumInputLength: config.maximumInputLength,
            maximumSelectionLength: config.maximumSelectionLength,
            minimumInputLength: config.minimumInputLength,
            minimumResultsForSearch: config.minimumResultsForSearch,
            multiple: config.multiple,
            placeholder: config.placeholder,
            selectOnClose: config.selectOnClose,
            theme: config.theme,
            translations: config.translations,
            url: config.url,
            width: config.width
        });
    };

    $('#ads-idcategory').on('change', function (e) {
        var selectCategory = $(this);
        var selectCompany = $('#productCompany');

        var idCategory = selectCategory.val();

        if (idCategory === null) {
            var options = selectCompany.data('select2').options.options;
            initSelect2(selectCompany, options, []);
        } else {
            $.ajax(selectCompany.data('url'), {
                data: {id: idCategory},
                success: function (data) {
                    var options = selectCompany.data('select2').options.options;
                    initSelect2(selectCompany, options, $.parseJSON(data));
                }
            });
        }
    });

    //Нотификации
    var notification = $('.dropdown.notifications-menu').find('a[data-toggle="dropdown"]');

    //Получение списка событий в html
    notification.click(function () {
        var menu = $(this).parent('li').find('ul.menu');
        $.ajax('/site/notification', {
            success: function (data) {
                menu.html(data);
            }
        });
    });

    var notification_label = notification.find('.label');
    var ads_badge = $('#ads_badge');
    var business_badge = $('#business_badge');
    var support_badge = $('#support_badge');
    var url = notification.data('url');

    //Получение каунтов событий
    setInterval(function() {
        $.ajax(url, {
            dataType: 'json',
            success: function (data) {
                notification_label.html(data.notification);
                ads_badge.html(data.ads);
                business_badge.html(data.business);
                support_badge.html(data.support);
            }
        });
    }, 60000);
});
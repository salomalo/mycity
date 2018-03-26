$(function () {
    "use strict";

    var form = $('form.form-with-disabling-submit');
    var buttonF = form.find('button:submit');

    if (buttonF.length) {
        buttonF.on('click', function (e) {
            e.preventDefault();
            buttonF.attr('disabled', true);
            form.submit();
        });

        var enableButton = function () {
            buttonF.attr('disabled', false);
        };

        $.each(form.children().find('input, select, option, textarea'), function (key, value) {
            $(value).on('change', enableButton);
        });

        if (typeof CKEDITOR !== 'undefined') {
            $.each(CKEDITOR.instances, function (key, value) {
                value.on('change', enableButton);
            });
        }
    }
});

//Добавление поля для значения кастомфилда продукта
$(function () {
    "use strict";
    
    $('#add-product-customfield-value').on('click', function (e) {
        e.preventDefault();
        var link = $(this);
        
        $.ajax(link.data('url'), {
            data: {customfield: link.data('customfield')},
            success: function (html) {
                $('.cf-list').append(html);
            }
        });
    });
});
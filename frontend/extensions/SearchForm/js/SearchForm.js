$(function () {
    'use strict';

    var form = $('#find_business');

    //При нажатии "поиск" направить браузер на pretty url поиска
    form.find('input[type=submit]').click(function (e) {
        e.preventDefault();

        var searchText = form.find('input:text[name="s"]').val();
        var pid = form.find('input:hidden[name="pid"]').val();
        var action = form.attr('action');

        window.location.href = action + (pid ? ('/category/' + pid + '/') : '/') + encodeURI(searchText);
    });
});
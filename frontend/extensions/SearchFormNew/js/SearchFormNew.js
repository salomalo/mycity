$(function () {
    'use strict';

    var form = $('#find_business');
    form.find('input[type=submit]').click(function (e) {
        e.preventDefault();
        var url = form.attr('action') + '/';
        var pid = form.find('input:hidden[name="pid"]').val();
        if (pid) {
            url += 'category/' + pid + '/';
        }
        url += encodeURI(form.find('input:text[name="s"]').val());
        window.location.href = url;
    });
});
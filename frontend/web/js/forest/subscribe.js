/**
 * Created by bogdan on 25.11.16.
 */
$(function () {
    'use strict';

    $('form.subscribe-form').on('submit', function (e) {
        e.preventDefault();

        var form = $(this);

        $.ajax(form.attr('action'), {data: form.serialize(), method: form.attr('method')})
            .done(function() {alert(form.data('success'));})
            .fail(function() {alert(form.data('error'));});
    });
});
$(function(){
    'use strict';

    var grid = $("#business-index");

    var doCheck = function () {
        var arr = [];
        var i = 0;
        grid.find(".selection_ids:checked").each(function() {
            arr[i] = $(this).val();
            i++;
        });

        var res = JSON.stringify(arr);

        grid.find(".deletelist").attr("data-listdel", res);
    };

    $('input.select-on-check-all').on('change', function () {
        var selection_ids = grid.find('.selection_ids');

        if ($(this).is(':checked')) {
            selection_ids.attr('checked', true);
            doCheck();
        } else {
            selection_ids.removeAttr('checked');
            doCheck();
        }
    });

    $('input.selection_ids').on('change', function (e) {
        e.preventDefault();
        if ($(this).is(':checked')) {
            $(this).attr('checked', true);
            doCheck();
        } else {
            $(this).removeAttr('checked');
            doCheck();
        }
    });

    $('a.deletelist').on('click', function (e) {
        e.preventDefault();
        var list = $(this).data('listdel');
        var url = $(this).data('url');

        if (typeof list !== 'undefined' && list.length > 0) {
            $.ajax(url, {type: 'POST', data: {idDel: list}}).done(function (data) {
                location.reload();
            });
        }
    });
});
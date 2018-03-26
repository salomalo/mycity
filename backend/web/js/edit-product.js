$(function() {
    'use strict';

    $("#productCategory").on('change', function() {
        $('#formAction').val(1);
        $('#productForm').submit();
    });
});
var doChange = true;

$(document).on("change", "#region-id", function() {
    doChange = false;
});

$(document).on("change", "#city-id", function() {
    var sel = parseInt($(this).val());

    if ((doChange === true) && (sel > 1)) {
        $('#form-city').submit();
    } else if (sel === 0) {
        window.location.href = $(this).data('home');
    }
    doChange = true;
});
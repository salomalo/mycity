$(function () {
    var datepicker = $('#my-datepicker');
    var city = $('#city_id');

    var disabled_dates_url = datepicker.data('url');
    var object_id = datepicker.data('id');

    var pos_input = datepicker.data('pos');
    var city_input = city.val();

    if (pos_input && city_input) {
        $.ajax({
            url: disabled_dates_url,
            dataType: 'json',
            data: {pos: pos_input, id: object_id, city: city_input},
            success: function (data) {
                initDatePicker(data, false);
            }
        });
    }

    city.on('change', function (e) {
        var city_input = city.val();

        if (pos_input && city_input) {
            $.ajax({
                url: disabled_dates_url,
                dataType: 'json',
                data: {pos: pos_input, id: object_id, city: city_input},
                success: function (data) {
                    initDatePicker(data, true);
                }
            });
        }
    });

    var initDatePicker = function (disableDates, destroy) {
        var inputs = datepicker.data('fields').split(',');

        inputs.forEach(function (item, i, array) {
            var datepickerInput = $('#' + item);
            datepickerInput.removeAttr('readonly');
            if (destroy) {
                datepickerInput.val('').datepicker('destroy');
            }
            datepickerInput.datepicker({
                autoclose: true,
                dateFormat: 'yy-mm-dd',
                minDate: new Date(),
                beforeShowDay: function (date) {
                    var curDate = $.datepicker.formatDate('yy-mm-dd', date);
                    return [($.inArray(curDate, disableDates) === -1)];
                }
            });
        });
    }
});
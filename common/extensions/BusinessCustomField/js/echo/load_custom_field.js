$(document).ready(function () {
    var baseId = 'select-2-';
    var baseName = 'customfield';
    var theme = 'krajee';

    var div = $('#business-custom-field');
    var button = $('#load-custom-field');

    var formatData = function (data) {
        var format = [];
        $.each(data, function (i, option) {
            format.push({id: option.id, text: option.text});
        });
        return format;
    };

    var getHtmlElement = function (select, i) {
        var divElem = document.createElement('div');
        var selectElem = document.createElement('select');
        var label = document.createElement('label');

        divElem.style = 'width: 80%; margin-left: 30px;';

        label.innerText = select.name;
        divElem.appendChild(label);

        selectElem.id = 'select-2-' + i;
        selectElem.style = 'width: 100%';
        selectElem.name = baseName + '[' + select.id + ']';
        if (select.multiple) {
            selectElem.name = selectElem.name + '[]';
            selectElem.multiple = 'multiple';
        }
        divElem.className = 'form-group';
        divElem.appendChild(selectElem);

        return divElem;
    };

    var ajax = function (cats) {
        $.ajax({
            type: 'POST',
            url: div.data('url'),
            data: {'business_id': div.data('business_id'), 'cats': cats},
            dataType: "json",
            success: function (data) {
                if (data !== null) {
                    //Контейнер селектов для вставки в div
                    var fragment = document.createDocumentFragment();

                    //Опции инициализации Select2
                    var options = [];

                    //Генерация селектов и опций для Select2
                    $.each(data, function (i, select) {
                        var option = {
                            data: [],
                            theme: theme,
                            allowClear: true
                        };

                        //Приводим данные к виду Select2
                        option.data = formatData(select.data);
                        if (option.data.length === 0) {
                            option.tags = true;
                        }
                        options.push(option);

                        //Создаем <select>
                        fragment.appendChild(getHtmlElement(select, i));
                    });

                    //Вставка сгенерированных селектов в div
                    div.append(fragment);

                    //Инициализация Select2
                    for (var i = 0; i < data.length; i++) {
                        var sel2 = $('#' + baseId + i);
                        if (data[i].selected && (options[i].data.length === 0)) {
                            options[i].data = Array.isArray(data[i].selected) ? data[i].selected : [data[i].selected];
                            sel2.select2(options[i]);
                        }
                        sel2.select2(options[i]);

                        if (data[i].selected) {
                            sel2.val(data[i].selected).trigger('change');
                        }
                    }
                } else {
                    console.log('Нет ни одного дополнительного поля!');
                }
            },
            error: function () {
                console.log('Возникла ошибка при ajax запросе дополнительных полей');
            }
        })
    };

    button.click(function (e) {
        var categories = $('#business-idcategories').val();
        if (categories !== null) {
            div.html('');
            ajax(categories);
        } else {
            console.log('Не выбрана ни одна категория!');
        }
    });

    button.click();
});
$(document).ready(function () {
    'use strict';

    //Сворачиваем пустые (с классом hidden) атрибуты сразу после загрузки страницы
    (function () {
        $('.cf_filter_spoiler_block.hidden').hide().removeClass('hidden');
    }());

    //Класс отвечающий за стрклочку вниз\вверх на кнопке спойлера, принимает селектор span элемента стрелочки
    function Chevron(selector) {
        this.e = selector;

        //Bootstrap классы стрелочек для span
        this.classes = {
            up: 'glyphicon-chevron-up',
            down: 'glyphicon-chevron-down'
        };

        //Разворачиваем стрелочку вверх
        this.up = function () {
            this.e.removeClass(this.classes.down).addClass(this.classes.up);
        };

        //Разворачиваем стрелочку вниз
        this.down = function () {
            this.e.removeClass(this.classes.up).addClass(this.classes.down);
        };

        //Разворачиваем стрелочку в обратном направлении
        this.toggle = function () {
            this.e.hasClass(this.classes.down) ? this.up() : this.down();
        };
    }

    //По клику на кнопку спойлера
    $('.cf_filter_spoiler').click(function () {
        var spoiler = $(this);

        //Переворачиваем стрелку
        var chevron = new Chevron(spoiler.find('span'));
        chevron.toggle();

        //Показываем\скрываем блок под спойлером
        spoiler.next('.cf_filter_spoiler_block').toggle('normal');
    });

    //По клику по кнопку сброса формы
    $('#cf_filter').on('reset', function(e) {
        e.preventDefault();
        var form = $(this);

        //Сбрасываем форму
        form.find('input').val('').removeAttr('checked');
        form.find('select').val('').removeAttr('selected');

        //Сбрасываем select2
        form.find('select').select2('val', '');

        //Применяем пустой фильтр
        form.submit();
    });
});
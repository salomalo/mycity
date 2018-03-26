;$(function() {
    $(document).on("click", "a.activator", function(e){
        e.preventDefault();
        //класс для установки visibility: hidden
        var hidden = 'hidden';
        //получаем id инпута из кнопки по которой нажали (times2D или times3D)
        var field = $(this).data('field');
        //получаем div внутри которого инпут
        var times = $('#' + field);

        if (times.hasClass(hidden)) {
            //если инпут скрыт - отображаем его
            times.removeClass(hidden);
            //подсвечиваем кнопку
            if ($(this).hasClass('btn-default')) {
                $(this).removeClass('btn-default');
            }
            if (!$(this).hasClass('btn-success')) {
                $(this).addClass('btn-success');
            }
        } else {
            //если инпут виден - скрываем его
            times.addClass(hidden);
            //обнуляем инпут
            times.find('select').select2('val', '');
            //убираем подсветку кнопки
            if ($(this).hasClass('btn-success')) {
                $(this).removeClass('btn-success');
            }
            if (!$(this).hasClass('btn-default')) {
                $(this).addClass('btn-default');
            }
        }
    });
});
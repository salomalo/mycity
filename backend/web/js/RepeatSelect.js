;$(function() {
    $(document).on("change", "#afisha-repeat", function(e){
        //что выбрал пользователь 1 = повторять каждую неделю
        var selected = $(this).val();
        //div с инпутом для дней недели
        var input = $('#repeat-days');
        //если выбрано повторять каждую неделю
        if (selected == 1) {
            //показываем инпут
            if (input.hasClass('hidden')) {
                input.removeClass('hidden');
            }
        } else {
            //скрываем инпут
            if (!input.hasClass('hidden')) {
                input.addClass('hidden');
            }
            //обнуляем инпут
            input.find('select').select2('val', '');
        }
    });
});
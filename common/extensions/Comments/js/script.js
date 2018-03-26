$(document).ready(function(){
    var comment = {
        selector: {
            form: $('.form-comment-add'),
            text: $('#comment-text'),
            button: $('.form-comment-add :submit')
        },
        url: {
            update: '/ru/comment/update',
            add: '/ru/comment/add',
            rating: '/ru/comment/rating'
        }
    };

    var upButton = {
        selector: $('#upRatingButton'),
        dataDo: 'like',
        div: 'div_ratingup'
    };
    var downButton = {
        selector: $('#downRatingButton'),
        dataDo: 'unlike',
        div: 'div_ratingdown'
    };
    var clearButton = {
        selector: null,
        dataDo: 'clear',
        div: 'div_ratingclear'
    };

    var updateButtons = function ($buttons) {
        $buttons.forEach(function (item) {
            item.selector.data('do', item.dataDo);
            item.selector.parent().attr('class', item.div);
        });
    };

    var getNewButton = function (action) {
        var buttons = null;
        switch (action) {
            case 'unlike':
                clearButton.selector = downButton.selector;
                buttons = [upButton, clearButton];
                break;
            case 'like':
                clearButton.selector = upButton.selector;
                buttons = [clearButton, downButton];
                break;
            case 'clear':
                buttons = [upButton, downButton];
                break;
        }
        return buttons;
    };

    $(document).on('click', '.comment_reply', function(e){
        e.preventDefault();

        var parent = $(this).parent().parent();
        var idcom = parent.data('id');
        var nesting = parent.data('nesting');
        var limit = parent.data('limit');

        $('.reply_form').remove();

        $(this).parent().parent().append('<form class="reply_form" action="" method="post">'+
        '<div class="form-group field-comment-text required">'+
        '<textarea id="comment-text" class="form-control" name="text" rows="3"></textarea>'+

        '<div class="help-block"></div>'+
        '</div>'+
        '<input type="hidden" name="parent" value="'+ idcom +'">'+
        '<input type="hidden" name="nesting" value="'+ nesting +'">'+
        '<input type="hidden" name="limit" value="'+ limit +'">'+
        '<div class="form-group"> <button type="submit" class="btn btn-success btn-sm reply">Ответить</button>    </div>'+
        '</form>');
    });

    $(document).on('click', '.add-comm', function(e){
        e.preventDefault();
        comment.selector.button.attr('class', 'btn btn-success comment-btn');
    });

    $(document).on('click', '.comment_update', function(e){
        e.preventDefault();
        var id = $(this).parent().parent().data('id');

        jQuery.ajax({
            url: comment.url.update,
            type: 'POST',
            dataType: "text",
            data: 'id=' + id,
            success: function(response) {
                //Вставляем комментарий в инпут
                comment.selector.text.val(response);
                //Сохраняем id комментрия
                comment.selector.button.data('id', id);
                //Переключаемся в режим редактирования (стиль+событие)
                comment.selector.button.attr('class', 'btn btn-success comment-btn comment_send_update');
            }
        });
    });

    $(document).on('click', '.comment_send_update', function(e){
        var text = comment.selector.text.val();
        var id = $(this).data("id");
        
        jQuery.ajax({
            url: comment.url.update,
            type: 'POST',
            dataType: 'html',
            data: 'id=' + id + '&text=' + text,
            async: false,
            success: function(response) {
                //изменяем текст комментария
                $('[data-id="'+id+'"]').find('.comm-text').html(text);
                //Очищаем инпут
                comment.selector.text.val('');
                //Переключаемся в режим коментирования (стиль+событие)
                comment.selector.button.attr('class', 'btn btn-success comment-btn');
                //Удаляем запомненное id
                comment.selector.button.removeData('id');
            }
        });
    });
      
    $('#form-comment-add').on('beforeSubmit', function(){
        var form = $(this);
        
        jQuery.ajax({
            url: comment.url.add,
            type: 'POST',
            dataType: 'html',
            data: form.serialize() + '&mongo=' + $('#comments').data('mongo'),
            async: false,
            success: function(response) {
                comment.selector.form.after(response);
                comment.selector.text.val('');
            }
        });

        return false;
    });
    
    $(document).on('click', '.comment_delete', function(e){
        e.preventDefault();
        message = $(this).data('message');

        if(confirm(message)){
            var com = $(this).parent().parent();
            var idcom = com.data("id");
            var url = $(this).data("url");
           
            jQuery.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: 'id=' +  idcom,
                async: false,
                success: function(response) {
                    $.each(response, function(i, item) {
                        $(document).find('div[data-id=' + item + ']').remove();
                    });
                    com.remove();
                }
            });
        }
    });

    $(document).on('click', '.comment_like', function(e){
        e.preventDefault();
        var comment_id = $(this).parent().parent().parent().parent().data("id");
        var comment_action = $(this).data("do");
        var rating = $('[data-id="'+comment_id+'"]').find('.rating-val');

        jQuery.ajax({
            url: comment.url.rating,
            type: 'POST',
            dataType: 'json',
            data: {id:comment_id, action:comment_action},
            async: false,
            success: function(response) {
                rating.html(response);
                if (parseInt(response) > 0) {
                    rating.attr('class', 'rating-val');
                } else {
                    rating.attr('class', 'rating-val bad');
                }
                updateButtons(getNewButton(comment_action));
            }
        });
    });
});
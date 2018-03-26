$(function(){
    var multi_select_lexa = $('.multi_select_lexa');
    var multi_select_lexa_slct = $('.multi_select_lexa .slct');

    multi_select_lexa_slct.on('click','.remove',function(e){
        e.stopPropagation();
        var parent_ul = $(this).parents('ul');
        var id = $(this).parent('li').data('id');
        $(this).parent('li').remove();
        multi_select_lexa.find("option[value='"+id+"']").remove();
        if (parent_ul.find('li').length == 1){
            parent_ul.find('li.placeholder').css({'display':'inline-block'});
        }
        multi_select_lexa.find('select').trigger('change');
    });

    multi_select_lexa_slct.on('click','li',function(e){
        e.stopPropagation();
    });

    function getlist(pid, ul, search) {
        var output = false;
        var url = ul.data("url");
        var old = ul.data("old");
        ul.html("");
        $.ajax({
            type: 'POST',
            url: url,
            cache: false,
            async: false,
            data: {'pid': pid, 'old': old, 'search': search},
            dataType: "json",
            success: function (data) {
                var urls = [
                    '/business/product-category-list',
                    '/business/category-list',
                    '/product-category/product-category-list'
                ];
                if ((pid !== null) && (pid !== undefined) && (pid !== 0) && ($.inArray(url, urls) >= 0)) {
                    $('<li id="backbutton" data-id="' + data.back_id + '">Назад</li>').appendTo(ul);
                }
                if ($.inArray(url, urls) >= 0) {
                    data = data.items;
                }
                var count = 0;
                $.each(data, function (i, item) {
                    $("<li data-id='" + item.id + "' >" + item.title + "</li>").appendTo(ul);
                    count = count + 1;
                });
                if (count) {
                    output = true;
                    ul.data("old", pid);
                } else {
                    output = false;
                }
            },
            error: function (xhr, str) {
                alert("Возникла ошибка");
            }
        });
        return output;
    }

    multi_select_lexa.on('click', '.slct',function(e){
        e.stopPropagation();
        var dropBlock = $(this).parent().find('.drop');
        var searchBlock = $(this).parent().find('.ul_saerch');
        getlist(null,dropBlock);
        if( dropBlock.is(':hidden') ) {
            dropBlock.slideDown();
            searchBlock.css({'display':'block'});
            searchBlock.find('input.drop_search').focus();
        } else {
            $(this).removeClass('active');
            dropBlock.slideUp();
            searchBlock.find('input.drop_search').val(null);
            searchBlock.find('input.drop_search_pid').val(null);
            searchBlock.css({'display':'none'});
        }
        return false;
    });

    multi_select_lexa_slct.on('click', '.placeholder',function(e){
        e.stopPropagation();
        var dropBlock = $(this).parents('.multi_select_lexa').find('.drop');
        var searchBlock = $(this).parents('.multi_select_lexa').find('.ul_saerch');
        getlist(null,dropBlock);
        if( dropBlock.is(':hidden') ) { 
            dropBlock.slideDown();
            searchBlock.css({'display':'block'});
            searchBlock.find('input.drop_search').focus();
        } 
        return false;
    });

    multi_select_lexa_slct.on('click', '#backbutton', function(e){
        e.stopPropagation();
        var dropBlock = $(this).parents('.multi_select_lexa').find('.drop');
        getlist($(this).data("id"),dropBlock);
        return false;
    });
    
    multi_select_lexa.on('click', '.ul_saerch>input', function(e){
        e.stopPropagation();
    }); 
    
    multi_select_lexa.on('keyup', '.ul_saerch>input', function(e){
        e.stopPropagation();
        var search = $(this).val(); // search text
        var searchBlock = $(this).parent();
        var pid;
        if(e.which === 13) { // press Enter
            searchBlock.find('input.drop_search').val(null);
            searchBlock.find('input.drop_search_pid').val(null);
            searchBlock.css({'display':'none'});
        }
        if(pid = searchBlock.find('input.drop_search_pid').val()){}

        var dropBlock = $(this).parent().parent().find('.drop');
        getlist(pid, dropBlock, search);
    }); 
    
    multi_select_lexa.on('click', '.drop>li',function(e){
        e.stopPropagation();
        var li = $(this);

        var parent_msl = li.parents('.multi_select_lexa');
        var searchBlock = li.parent().parent().find('div.ul_saerch');

        searchBlock.find('input.drop_search_pid').val(li.data('id'));
        searchBlock.find('input.drop_search').val(null);
        searchBlock.find('input.drop_search').focus();
        
        var dropBlock = li.parent("ul");
        var status = getlist(li.data("id"), dropBlock);

        if (status == false){
            var selectResult = li.html();
            var selectResultid = li.data('id');
            var close = "<span class='remove glyphicon glyphicon-remove'></span>";

            if (!parent_msl.find('select').attr('multiple')) {
               parent_msl.find(".slct li:not('.placeholder')").remove();
               parent_msl.find('select').html('');
            }
            if (parent_msl.find('.slct .el_' + selectResultid).length==0){
                var container_slct = parent_msl.find('.slct');
                var container_select = parent_msl.find('select');

                $('<li class="el_' + selectResultid + '" data-id="' + selectResultid + '">'+close+selectResult + '</li>').appendTo(container_slct);
                parent_msl.find('.placeholder').css({'display': 'none'});
                $('<option value="'+selectResultid + '" selected>' + selectResultid + '</option>').appendTo(container_select);
            }
            dropBlock.slideUp();
            searchBlock.find('input.drop_search').val(null);
            searchBlock.find('input.drop_search_pid').val(null);
            searchBlock.css({'display': 'none'});

            multi_select_lexa.find('select').trigger('change');
        }
    });

    $(document).click(function(event) {
        var multi_select_lexa_ul = $(".multi_select_lexa .ul_saerch");
        if ($(event.target).closest(".multi_select_lexa .drop").length) return;
        $(".multi_select_lexa .drop").slideUp(); 
        multi_select_lexa_ul.find('input.drop_search').val(null);
        multi_select_lexa_ul.find('input.drop_search_pid').val(null);
        multi_select_lexa_ul.css({'display':'none'});
        event.stopPropagation();
  });
});
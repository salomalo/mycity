$(function(){    
    $(document).on('click','.remove',function(e){
        e.stopPropagation();
        var parent_ul = $(this).parents("ul");
        var id = $(this).parent('li').data("id");
        $(this).parent('li').remove();
        console.log(id);
        $('.multi_select_lexa').find("option[value='"+id+"']").remove();
        if (parent_ul.find("li").length == 1){ 
            parent_ul.find('li.placeholder').css({"display":"inline-block"});
        }
    })   
    $(document).on('click','li',function(e){
        e.stopPropagation();
    })     

     function getlist(pid,ul,search){
         var output = false;
         var url = ul.data("url");
         var old = ul.data("old");
         ul.html("");
            var jqxhr = $.ajax({
              type: 'POST',
              url: url,
              cache: false,
              async: false,
              data: {'pid':pid,'old':old,'search':search},
              dataType: "json",
              success: function(data) {
                var count = 0;
                $.each(data,function(i, item) {
                    $("<li data-id='"+item.id+"' >"+item.title+"</li>").appendTo(ul);
                    count=count+1;
                });
                if (count){
                    output = true;
                    ul.data("old",pid);
                }else{
                    output = false;
                }

              },
              error:  function(xhr, str){
                    alert ("Возникла ошибка");
                }
            });
            
             return output;
     }

     $(document).on("click",'.slct',function(){
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

//    $('.multi_select_lexa').on("click",'.slct',function(){
//        var dropBlock = $(this).parent().find('.drop');
//        var searchBlock = $(this).parent().find('.ul_saerch');
//        getlist(null,dropBlock);
//        if( dropBlock.is(':hidden') ) {
//            dropBlock.slideDown();
//            searchBlock.slideDown();
//        } 
//        return false;
//    });

    $(document).on("click",'.placeholder',function(){
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
    
    $(document).on("click",".ul_saerch>input",function(e){    
        e.stopPropagation();
    }); 
    
    $(document).on("keyup",".ul_saerch>input",function(e){    
        e.stopPropagation();
        search = $(this).val(); // search text
        var searchBlock = $(this).parent();
        if(e.which === 13) { // press Enter
            searchBlock.find('input.drop_search').val(null);
            searchBlock.find('input.drop_search_pid').val(null);
            searchBlock.css({'display':'none'});
        }
        pid = null;
        if(pid = searchBlock.find('input.drop_search_pid').val()){
   
        }
        
        var dropBlock = $(this).parent().parent().find('.drop');
        getlist(pid, dropBlock, search);
    }); 
    
    $(document).on("click",".drop>li",function(e){    
        e.stopPropagation();
        var parent_msl = $(this).parents(".multi_select_lexa");
        
        var searchBlock = $(this).parent().parent().find("div.ul_saerch");
//        searchBlock.attr('data-pid', $(this).data("id"));
        searchBlock.find('input.drop_search_pid').val($(this).data("id"));
        searchBlock.find('input.drop_search').val(null);
        searchBlock.find('input.drop_search').focus();
        
        var dropBlock = $(this).parent("ul"); 
        var status = getlist($(this).data("id"),dropBlock);
        //console.log(status);
        if (status==false){
            var selectResult = $(this).html();
            var selectResultid = $(this).data("id");
            var close = "<span class='remove glyphicon glyphicon-remove'></span>";
             
            if (!parent_msl.find('select').attr("multiple")) {
               parent_msl.find(".slct li:not('.placeholder')").remove();
               parent_msl.find('select').html("");
            }
            if (parent_msl.find(".slct .el_"+selectResultid).length==0){
                var container_slct = parent_msl.find('.slct');
                $("<li class='el_"+selectResultid+"' data-id='"+selectResultid+"'>"+close+selectResult+"</li>").appendTo(container_slct);
                parent_msl.find('.placeholder').css({"display":"none"});

                var container_select = parent_msl.find('select');
                $("<option value='"+selectResultid+"' selected>"+selectResultid+"</option>").appendTo(container_select);
            }
            dropBlock.slideUp();
            searchBlock.find('input.drop_search').val(null);
            searchBlock.find('input.drop_search_pid').val(null);
            searchBlock.css({'display':'none'});
        }        
    });     
    $(document).click(function(event) {
        if ($(event.target).closest(".multi_select_lexa .drop").length) return;
        $(".multi_select_lexa .drop").slideUp(); 
        $(".multi_select_lexa .ul_saerch").find('input.drop_search').val(null);
        $(".multi_select_lexa .ul_saerch").find('input.drop_search_pid').val(null);
        $(".multi_select_lexa .ul_saerch").css({'display':'none'});
        event.stopPropagation();
  });
});
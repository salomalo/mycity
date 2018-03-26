$(document).ready(function(){
    
    $(document).find('.ad-gallery').adGallery();
    
    $("#form-select-gallery").on("pjax:end", function() {
            $(document).find(".ad-gallery").adGallery();
        });
        
    //$('.ad-gallery ul.ad-thumb-list').css('width', '100%');
    //$('.ad-gallery').slideUp();
    
});

$(document).on("change", '#select-gallery', function(e){
    $(this).submit();
//    if(!$(this).next('div.ad-gallery').is(':visible')){
//        $('.ad-gallery').slideUp();
//        $(this).next('div.ad-gallery').slideDown(); 
//    }  
});

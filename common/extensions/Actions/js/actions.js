$(document).ready(function(){
    
    $("#business-actions").on("pjax:end", function() {
            $("body,html").animate({
                scrollTop:0
        }, 800);
    });
    
});
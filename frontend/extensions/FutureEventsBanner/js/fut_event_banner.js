$(function () {
    var block = $('.future-event');
    block.hover(function() {
        $(this).css('cursor', 'pointer');
    }, function() {
        $(this).css('cursor', 'default');
    });
    block.mousedown(function(e){
        this.mouseX = e.pageX;this.mouseY = e.pageY;
    }).mouseup(function(e){
        if((this.mouseX === e.pageX) && (this.mouseY === e.pageY)){
            // window.location.href = $(this).data("url");
            window.open($(this).data("url"), '_blank');
        }
    });
});
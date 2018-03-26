$(function(){
    $(".addDays").on('click',function(){

        $('.days').text('Понедельник');
        $('.addDays').hide();
        $('.workDays').slideDown(500);
    });
    
    $(".weeckend_addDays").on('click',function(){
        $('.weeckend_addDays').hide();
        $('.weeckend').slideDown(500);
    });
    
});

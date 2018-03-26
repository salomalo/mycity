
$(function(){
    $("#productCategory").on('change',function(){
        $('#formAction').val(1);
        $('#productForm').submit();
    });
});




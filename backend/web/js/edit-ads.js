
$(function(){
    $("#productCompany, #productModel").on('change',function(){
        $('#formAction').val(1);
        $('#productForm').submit();
    });
});




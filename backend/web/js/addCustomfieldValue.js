/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).on("click", '.addcustomfieldValue', function(){
    $(this).before('<div class="form-group field-productcustomfieldvalue-value required"><label class="control-label" for="productcustomfieldvalue-value">Value </label>'+
            '<a href="#" class="delcustomfieldValue">X</a>'+
            '<input type="text" id="productcustomfieldvalue-value" class="form-control" name="ProductCustomfieldValue[][value]" value=""></div>');
    return false;
});


$(document).on("click", '.delcustomfieldValue', function(){
    $(this).parent('div').remove();
    return false;
});

/*
$('.addcustomfield').click(function(){
    $(this).before('<div style="border: 1px solid #000000;padding: 5px; display: table; margin-bottom: 5px;">'+
    '<div style="width: 250px; display: table-cell;">'+
    
            '<div class="form-group field-productcustomfieldvalue-value required">'+
'<label class="control-label" for="productcustomfieldvalue-value">Value</label>'+ 
'<a href="#" class="delcustomfieldValue"> X</a>'+
'<input type="text" id="productcustomfieldvalue-value" class="form-control" name="ProductCustomfieldValue[][value]" value=""></div>'+
            
    '</div>'+
                
                '<div style="width: 250px; display: table-cell;">'+
                
             '<div class="form-group field-productcustomfieldvalue-value required">'+
'<label class="control-label" for="productcustomfieldvalue-value">Value</label>'+ 
'<a href="#" class="delcustomfieldValue"> X</a>'+
'<input type="text" id="productcustomfieldvalue-value" class="form-control" name="ProductCustomfieldValue[][value]" value=""></div>'+
                    '<a href="#" class="addcustomfieldValue">Добавить value</a>'+
                '</div>'+
            '</div>');
    
});
*/
var sel = $("#idCat").val();
if(sel){
    if($("#productCategory").length > 0){
        $("#productCategory").select2().select2('val',sel);
    }
}
 
 
 $("#productCategory").on('change',function(){
     $('#sel-customfields').submit();
 });
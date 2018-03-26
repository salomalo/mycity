$(document).on("click", ".reset_button", function(e){
    e.preventDefault();

    $('.business_phone').val('');
    $('#business-title').val('');
    $('.business-logo').remove();
    
    $('input[name^=start_time]').val('00:00');
    $('input[name^=end_time]').val('00:00');
    
    $('select#business-idcity option').remove();
    $('select#business-idcity').html('<option value=""></option>');
    
    $('.field-business-idcity .multi_select_lexa ul.slct li').remove();
    $('.field-business-idcity .multi_select_lexa ul.slct').html('<li class="placeholder" style="display:block;">Выберите город ...</li>');
    
    $('select#business-idproductcategories option').remove();
    $('select#business-idproductcategories').html('<option value=""></option>');
    
    $('.field-business-idproductcategories .multi_select_lexa ul.slct li').remove();
    $('.field-business-idproductcategories .multi_select_lexa ul.slct').html('<li class="placeholder" style="display:block;">Выберите категории продуктов ...</li>');
    
    $('select#business-idcategories option').remove();
    $('select#business-idcategories').html('<option value=""></option>');
    
    $('.field-business-idcategories .multi_select_lexa ul.slct li').remove();
    $('.field-business-idcategories .multi_select_lexa ul.slct').html('<li class="placeholder" style="display:block;">Выберите категории бизнеса ...</li>');
    
    id = $(this).data('id');
    url = $(this).data('url');
    
    $.ajax({
        type:'POST',
        data: 'id='+ id,
        url: url, //'index.php?r=site/showmodal-login',
        success: function(data)
            {

            }
    });
});


$(document).on("click", ".full_week", function(e){
    
    if ($(this).is(":checked"))
    {   
        start_time = $('input[name="start_time[1]"]').val();
        end_time = $('input[name="end_time[1]"]').val();
        
        $('input[name="start_time[6]"]').val(start_time);
        $('input[name="end_time[6]"]').val(end_time);
        
        $('input[name="start_time[7]"]').val(start_time);
        $('input[name="end_time[7]"]').val(end_time);
        
    }
    else{
        
        start_time = '00:00';
        end_time = '00:00';
        
        $('input[name="start_time[6]"]').val(start_time);
        $('input[name="end_time[6]"]').val(end_time);
        
        $('input[name="start_time[7]"]').val(start_time);
        $('input[name="end_time[7]"]').val(end_time);
    }

});
var doChange = true;

$(document).on("change", "#region-id", function(e){
    
   doChange = false;
});

$(document).on("change", "#city-id", function(e){
    
    if(doChange == true){
        if($(this).val() > 1){
            $('#form-city').submit();
        }   
    }
    
    doChange = true;
});

    $(".<?=$Class_outer?> select").select2({
        theme: 'krajee',
        placeholder: "Выберите категорию",
        allowClear: true
    });

$(".<?=$Class_outer?>").on("change", "select", function(e){
    
var id = $("option:selected", this).val();
//получить  атрибут name 
var element = $(this).parents(".select");
var name = "<?=$name?>";
element.nextAll().remove();
 $('.<?=$Class_outer?> select').attr('name',''); 
 $('.<?=$Class_outer?> select').attr('id',''); 

if (id>0){
   // $('.<?=$Class_outer?>>input').val(id); 
    $(this).attr('name',name); 
    $(this).attr('id',"<?=$id?>"); 

    var request = $.ajax({
        url: "<?=$url?>",
        type: "POST",
        data: {pid : id },
        dataType: "json"
    });

    request.done(function(data) {
       var options="";
       var j=0;
       $(data).each(function(i) {
         options +='<option value="' + data[i].id + '">' + data[i].title + '</option>';
         j++;
       });       
       if (j>0){
            options ='<option value=""> <?=$TitleEmptyOptions?></option>'+options;
            var select = "<div class='select'><select class='form-control item-"+id+"' > "+options+"<select> </div> ";
            element.after(select);
            $('select.item-'+id).select2({
                theme: 'krajee',
                placeholder: "Выберите категорию",
                allowClear: true
            });
       }    
    });   
}else{
  var parent_select = $('.<?=$Class_outer?> select').eq(-2);   
  var parent_id = $("option:selected",parent_select).val();
 // $('.<?=$Class_outer?>>input').val(parent_id);      
  }

    
});


$(document).on("change", "select", function(e){
var id = $("option:selected", this).val();
var element = $(this);
 element.nextAll().remove()
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
            options ='<option value="0"> <?=$TitleEmptyOptions?></option>'+options;
            var select = "<select  class='form-control'> "+options+"<select> ";
            element.after(select);
       }    
});   

    
});
function reset(url){
    $.ajax({
        type:'POST',
        url:url, //'index.php?r=site/showmodal-password-reset',
        success: function(data)
            {
                $('#myModal').html(data);
                $('#myModal').modal();
            }
    });
}

function closeModal(){
    $('#myModal').modal('toggle');
}

$(document).on("click", ".reset-button", function(e){

    e.preventDefault();

    email = $('#passwordresetrequestform-email').val();
    var url = $(this).parents("form").attr("action");
    $.ajax({
        type:'POST',
        data: 'email='+ email,
        url:url,
        success: function(data)
            {
                $('#myModal').html(data);
                $('#myModal').modal();       
            }
    });

});

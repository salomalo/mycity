function login(url, redirectUrl) {
    if (!redirectUrl) {
        redirectUrl = '';
    }
    $.ajax({
        type: 'POST',
        url: url, /* 'index.php?r=site/showmodal-login', */
        data: 'redirectUrl=' + redirectUrl,
        success: function (data) {
            $('#myModal').html(data);
            $('#myModal').modal();
        }
    });
}

function signup(url) {
    $.ajax({
        type: 'POST',
        url: url, /*'index.php?r=site/showmodal-signup', */
        success: function (data) {
            $('#myModal').html(data);
            $('#myModal').modal();
        }
    });
}

function reset(url) {
    $.ajax({
        type: 'POST',
        url: url, //'index.php?r=site/showmodal-password-reset',
        success: function (data) {
            $('#myModal').html(data);
            $('#myModal').modal();
        }
    });
}
function contact(url) {
    $.ajax({
        type: 'POST',
        url: url, //'index.php?r=site/showmodal-password-reset',
        success: function (data) {
            $('#myModal').html(data);
            $('#myModal').modal();
        }
    });
}

$(document).on("click", ".message_change_city", function (e) {
    e.preventDefault();
    var url = $(this).data("modalurl");
    $.ajax({
        type: 'POST',
        url: url, //'index.php?r=site/showmodal-message-change-city',
        success: function (data) {
            $('#myModal').html(data);
            $('#myModal').modal();
        }
    });
});

$(document).ready(function () {
    $('.add-to-cart').on("click", function (e) {
        e.preventDefault();

        var id = $(this).data("id");
        $.ajax({
            type: 'POST',
            url: '/shopping-cart/add-shopping-cart',
            data: 'id=' + id,
            success: function (data) {
                var getResult = JSON.parse(data);
                $('.add-shopping-cart-result').html(getResult.result);
                if (getResult.count > 0) {
                    $('.my-cart-count').html('(' + getResult.count + ')');
                    $('a.my-cart').attr("data-count", getResult.count);
                }
//                       $('#myModal').modal();
            }
        });
    });

});

$(document).on("click", ".btn-do-order", function (e) {

    if (!$(this).data("id_user")) {
        e.preventDefault();
        login('site/showmodal-login', 'shopping-cart/do-order');
    }
});

$(document).on("click", ".my-cart", function (e) {

    if ($(this).data("count") < 1) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'site/showmodal-message', //'index.php?r=site/showmodal-message-change-city',
            data: 'text=' + 'Ваша корзина пуста.',
            success: function (data) {
                $('#myModal').html(data);
                $('#myModal').modal();
            }
        });
    }
});

$(document).on("click", ".message_login_auth", function (e) {
    e.preventDefault();
    var url = $(this).data("modalurl");
    $.ajax({
        type: 'POST',
        url: url, //'index.php?r=site/showmodal-message-change-city',
        success: function (data) {
            $('#myModal').html(data);
            $('#myModal').modal();
        }
    });
});

$(document).on("click", ".login-button", function (e) {

    e.preventDefault();

    username = $('#loginform-username').val();
    password = $('#loginform-password').val();
    redirectUrl = $('#redirectUrl').val();
    var url = $(this).parents("form").attr("action");
    $.ajax({
        type: 'POST',
        data: 'username=' + username + '&password=' + password + '&redirectUrl=' + redirectUrl,
        url: url, //'index.php?r=site/showmodal-login',
        success: function (data) {
            $('#myModal').html(data);
            $('#myModal').modal();
        }
    });

});

$(document).on("click", ".send-button", function (e) {

    e.preventDefault();

    idCity = $("#idCity").select2("val");
    name = $('#suggestionsform-name').val();
    email = $('#suggestionsform-email').val();
    text = $('#suggestionsform-text').val();

    var url = $(this).parents("form").attr("action");
    $.ajax({
        type: 'POST',
        data: 'name=' + name + '&email=' + email + '&text=' + text + '&idCity=' + idCity,
        url: url, //'index.php?r=site/showmodal-login',
        success: function (data) {
            $('#myModal').html(data);
            $('#myModal').modal();
        }
    });

});

$(document).on("click", ".reset-button", function (e) {

    e.preventDefault();

    email = $('#passwordresetrequestform-email').val();
    var url = $(this).parents("form").attr("action");
    $.ajax({
        type: 'POST',
        data: 'email=' + email,
        url: url,
        success: function (data) {
            $('#myModal').html(data);
            $('#myModal').modal();
        }
    });

});

$(document).on("click", ".reg-button", function (e) {
    e.preventDefault();
    var url = $(this).parents("form").attr("action");
    var username = $('#signupform-username').val();
    var email = $('#signupform-email').val();
    var password = $('#signupform-password').val();
    var password2 = $('#signupform-password2').val();

    if (!$('#signupform-apply').is(':checked')) {
        $('.field-signupform-apply').find('.help-block-modal').html('Вы не прочитали соглашения');
    } else {
        var apply = 1;
        $('.field-signupform-apply').find('.help-block-modal').html('');
        $.ajax({
            type: 'POST',
            data: 'username=' + username + '&email=' + email + '&password=' + password + '&password2=' + password2 + '&apply=' + apply,
            url: url, //'index.php?r=site/showmodal-signup',
            success: function (data) {
                $('#myModal').html(data);
                $('#myModal').modal();
            }
        });
    }
});

$(document).on("click", ".new-reg-button", function (e) {
    e.preventDefault();
    var form = $('#new-form-signup');
    var url = form.attr("action");
    var formData = form.serialize();
    $('.field-signupform-apply').find('.help-block-modal').html('');
    $.ajax({
        type: 'POST',
        data: formData,
        url: url,
        success: function (data) {
            $('#myModal').html(data);
            $('#myModal').modal();
        }
    });
});

$(document).on("click", ".new-login-button", function (e) {
    e.preventDefault();
    var form = $('#new-login-form');
    var url = form.attr("action");
    var formData = form.serialize();
    $.ajax({
        type: 'POST',
        data: formData,
        url: url,
        statusCode: {
            500: function (response) {
                console.log('Login error. Internal Server Error #500');
            }
        }, success: function (response) {
            if (response === '{login:true}') location.reload();
            else {
                $('#myModal').html(response);
                $('#myModal').modal();
            }
        }
    });
});

//$(document).on("change", "#signupform-apply", function(e){
//    button = $(".reg-button");
//    if($(this).prop("checked")){
//        button.attr("disabled", false);
//    }else{
//        button.attr("disabled","disabled");
//    }
//});
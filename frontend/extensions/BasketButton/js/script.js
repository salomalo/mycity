$(function () {
    'use strict';

    $('#add-to-cart').on('click', function (e) {
        e.preventDefault();

        var form = $(this);

        $.ajax(form.attr('action'), {
            type: 'POST',
            data: form.serialize(),
            success: function (response) {
                var data = JSON.parse(response);
                if (data.result == 'add'){
                    form.css('background-color', '#8dc63f');
                    alert("Товар добавлен в корзину");
                } else if(data.result == 'remove'){
                    form.css('background-color', '#ea823f');
                    alert("Товар удален из корзины");
                }

                document.getElementById("totalCount").innerHTML = data.count;
                if (data.count > 0){
                    $('.basket-number').css('display', 'block');
                } else {
                    $('.basket-number').css('display', 'none');
                }
            }
        });
    });
});

$(function () {
    'use strict';
    var listAdsId = $('*[id^="add-to-cart-"]');
    var len = listAdsId.length, i;

    function changeBtnText(idProduct, resultCode, count){
        //получаем список всех форм с одинаковым id товара
        var listSameIds = $('[id*=' + idProduct + ']');
        listSameIds.each(function() {
            var currentForm = $(this);
            if (currentForm.is("form")) {
                if (resultCode == 'add'){
                    currentForm[0][1].defaultValue = 'В корзине';
                } else if(resultCode == 'remove'){
                    currentForm[0][1].defaultValue = 'Купить';
                }
            }
        });

        if (resultCode == 'add'){
            alert("Товар добавлен в корзину");
        } else if(resultCode == 'remove'){
            alert("Товар удален из корзины");
        } else {
            alert("Ошибка, попробуйте позже");
        }

        $("#kuteshop-notify-cart").text(count);
        $("#totalCount").text(count);

        if (count > 0){
            $('.basket-number').css('display', 'block');
            $("#kuteshop-notify-cart").css('display', 'block');
        } else {
            $("#kuteshop-notify-cart").css('display', 'none');
            $('.basket-number').css('display', 'none');
        }
    }

    function markSelection(e) {
        e.preventDefault();

        var form = $(this);

        $.ajax(form.attr('action'), {
            type: 'POST',
            data: form.serialize(),
            success: function (response) {
                var data = JSON.parse(response);
                var productId = form.attr('product-id');

                changeBtnText(productId, data.result, data.count);
            }
        });
    }

    for (i = 0,len; i < len; i++){
        listAdsId[i].onclick = markSelection;
    }

    $('.bgl-overlay').on('click', function (e) {
        $('.bgl-overlay').addClass("hidden");
    });

});
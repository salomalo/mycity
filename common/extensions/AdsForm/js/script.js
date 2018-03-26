$(function () {
    'use strict';
    
    window.businessChange = function (value) {
        $.ajax('/business/contact', {
            type: 'post',
            data: {idBusiness: value, _csrf: csrfVar},
            success: function (data) {
                $("#ads-contact").val(data.contact);
            }
        });
    };
});

$(function () {
    'use strict';

    $('#ads-image').change(function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('img.ads-main-image').attr('src', e.target.result);
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
});

$("#btn-save-ads-property").click(function(e) {
    'use strict';
    e.preventDefault();

    var idCategory = parseInt($("#idCategory").val());
    var idCompany = parseInt($("#ads-idcompany").val());
    var idBusiness = parseInt($("#idBusiness").val());
    var idProvider = parseInt($("#idProvider").val());

    function isNumeric(num){
        return !isNaN(num)
    }

    function validate() {
        if (!isNumeric(idCategory)){
            alert('Заполните поле "Категория товару"');
            return false;
        } else if (!isNumeric(idCompany)){
            alert('Заполните поле "Производитель"');
            return false;
        } else if (!isNumeric(idBusiness)){
            alert('Заполните поле "Предприятие"');
            return false;
        } else if (!isNumeric(idProvider)){
            alert('Заполните поле "Поставщики"');
            return false;
        }

        return true;
    }

    if (validate()) {
        $.ajax({
            url: '/ads/save-property',
            type: 'post',
            data: {
                idCategory: idCategory,
                idCompany: idCompany,
                idBusiness: idBusiness,
                idProvider: idProvider,
                _csrf: csrfVar
            },
            success: function (data) {
                console.log(data);
                if (data.response === 'success'){
                    alert('Данные для автозаполнения формы сохранены');
                } else {
                    alert('Возникла ошибка при сохранении данных для автозаполнения формы');
                }
            }
        });
    }
});
$(document).ready(function() {
    'use strict';

    var common = {
        ajaxRequest: function (url, postData, okCallback) {  // errorCallBack
            okCallback = okCallback || function () {};
            postData = postData || {};

            var csrfMode = common.ajaxCsrf ? 1 : 0;

            if (csrfMode) postData[common.ajaxTokenName] = common.ajaxCsrf;

            $.ajax(url, {
                type: 'post',
                data: postData,
                dataType: 'json',
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log("error ajax");
                },
                success: function (data, status, jqXHR) {
                    okCallback(data);
                    return true;
                }
            });
            return false;
        }
    };

    $('.edit_gallery_img').on('change', '.add_img .input_files', function () {
        var counter = -1, file;
        var el = $(this);

        while (this.files[++counter]) {
            file = this.files[counter];
            var reader = new FileReader();

            reader.onloadend = (function(file){

                return function() {
                    var image = new Image();

                    image.height = 100;
                    image.title = file.name;
                    image.src =  this.result;

                    var li = el.parent('li');
                    var content = "<img src='" + image.src  + "' title=\"" + image.title + "\"/>";

                    content += '<a href="#" class="btn btn-icon btn-red btn_remove_storefiles"><i class="icon-close-mini-white"></i></a>';
                    content = '<li>' + content + '</li>';

                    li.before(content);
                };
            })(file);

            reader.readAsDataURL( file );
        }
    });

    $('.btn.btn_remove_storefiles').on('click', function (e) {
        e.preventDefault();

        var el = $(this);
        var url = el.attr('href');
        var id = el.data('id');

        if (id) {
            common.ajaxRequest(url, {'ids': [id]}, function (result) {
                if (!result.error) el.parent("li").remove();
            });
        } else {
            el.parent("li").remove();
        }
    });
});
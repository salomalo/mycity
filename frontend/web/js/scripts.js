$(document).ready(function () {

    $(function () {
        $('.fancybox').fancybox();
    });

    $('.block-filter .fields_block .more_field').click(function () {
        var active = $(this).data("active");
        var el = $(this);
        if (active) {
            $(this).parents().children('.fields_hide').slideToggle("slow", function () {
                el.data("active", 0);
                el.children("span").html("Показать все варианты");
            });

        } else {
            $(this).parents().children('.fields_hide').slideToggle("slow", function () {
                el.data("active", 1);
                el.children("span").html("Скрыть все варианты");
            });
        }
        //   $(this).parents().children('.fields_hide').slideToggle("slow");
    });

    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $("#top").fadeIn();
        } else {
            $("#top").fadeOut();
        }
    });

    $('#top').click(function (e) {
        e.preventDefault();
        $("body,html").animate({
            scrollTop: 0
        }, 800);
    });

    $('#owner-btn').click(function (e) {
        e.preventDefault();
        var link = $(this);
        $.ajax({
            url: link.attr('href'),
            data: {id: link.data('id')},
            statusCode: {
                200: function (data) {
                    link.html(data);
                    link.attr('disabled', 'true');
                    link.removeClass('btn-primary');
                    link.addClass('btn-success');
                },
                403: function() {
                    signup("/ru/user/security/login-ajax");
                },
                404: function () {
                    link.html('Опция недоступна');
                    link.attr('disabled', 'true');
                    link.removeClass('btn-primary');
                    link.addClass('btn-danger');
                }
            }
        });
    });
});
$(function () {
    'use strict';

    $('.block-filter .fields_block .more_field').click(function () {
        var active = $(this).data('active');
        var el = $(this);
        var hideFields = el.parents().children('.fields_hide');

        hideFields.slideToggle('slow', function () {
            if (active) {
                el.data('active', 0);
                el.children('span').html("Показать все варианты");
            } else {
                el.data('active', 1);
                el.children('span').html("Скрыть все варианты");
            }
        });
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

        $('body, html').animate({scrollTop: 0}, 800);
    });
});

//отображаем скрытое описание категории продуктов
$(function () {
    $('#short_text_show_link').click(function (e) {
        e.preventDefault();

        $('#hide-description-product-category').removeClass("hidden");
        $(this).addClass("hidden");
    });
});

//sticky Footer
$(function () {
    'use strict';

    var wrapperHeight = $('div.page_wrapper').height();
    var div = document.createElement('div');

    //Calc Footer height
    var footer = 0;
    $('footer').children().each(function (index, element) {
        footer += $(element).height();
    });

    var stickyFooter = function () {
        var windowHeight = $(window).height();

        if (windowHeight > wrapperHeight) {
            //Insert div with needle height
            div.style.height = (windowHeight - wrapperHeight - footer) + 'px';
            $('div.main-inner').append(div);
        }
    };

    stickyFooter();

    $(window).on('resize', stickyFooter);
});

$(function($) {
    'use strict';

    $( ".js-popup" ).hover(
        function() {
            $('#popup_A35351B4-E9FF-43AE-B89F-8CD14B85DD4B').removeClass( "hidden" );
        }, function() {
            $('#popup_A35351B4-E9FF-43AE-B89F-8CD14B85DD4B').addClass( "hidden" );
        }
    );

    $( "#popup_A35351B4-E9FF-43AE-B89F-8CD14B85DD4B" ).hover(
        function() {
            $('#popup_A35351B4-E9FF-43AE-B89F-8CD14B85DD4B').removeClass( "hidden" );
        }, function() {
            $('#popup_A35351B4-E9FF-43AE-B89F-8CD14B85DD4B').addClass( "hidden" );
        }
    );
});

$(function($) {
    'use strict';

    //Всплывающие окно для отзывов магазина
    $( ".b-review-info__link" ).hover(
        function() {
            $('#popup_Z2D9FB9CA-491D-4938-B42A-490E28149888').removeClass( "hidden" );
        }, function() {
            $('#popup_Z2D9FB9CA-491D-4938-B42A-490E28149888').addClass( "hidden" );
        }
    );

    $( "#popup_Z2D9FB9CA-491D-4938-B42A-490E28149888" ).hover(
        function() {
            $('#popup_Z2D9FB9CA-491D-4938-B42A-490E28149888').removeClass( "hidden" );
        }, function() {
            $('#popup_Z2D9FB9CA-491D-4938-B42A-490E28149888').addClass( "hidden" );
        }
    );

    //Ограничуем тело отзыва к магазину на 500 символов
    $('#comment-text').on('keyup', function() {
        var outText = $('.b-text-hint__length-counter');
        var maxLength = outText.attr('data-maxlength-max');
        var curLength = $(this).val().length;

        $(this).val($(this).val().substr(0, maxLength));

        var remaning = maxLength - curLength;
        if (remaning < 0) remaning = 0;
        outText.html(remaning);
    });
});

$(function($) {
    'use strict';

    $('.inventor-favorites-btn-toggle').on('click', function(e) {
        e.preventDefault();

        var toggler = $(this);
        var action = toggler.hasClass('marked') ? 'remove' : 'add';

        var data = {action: action, id: toggler.data('listing-id'), type: toggler.data('type')};

        $.ajax(toggler.data('ajax-url'), {data: data}).done(function () {
            toggler.toggleClass('marked');
            var span = toggler.children('span');
            span.data('toggle', span.text());
            span.text(span.data('toggle'));
        });
    });
});

//функция для отображения номера продавца
$(function () {
    //При нажатии на хедер
    $('.site-info__icon').on('click', function(e) {
        $('.bgl-overlay').removeClass( "hidden" );
    });

    'use strict';
    var listAdsId = $('*[id^="show-all-phones-ID-"]');
    var len = listAdsId.length, i;

    function markSelection(e) {
        e.preventDefault();

        var id = $(this);
        id = id.attr('id').substring(19);

        $.ajax({
            url: '/ads/business-contact',
            type: 'post',
            data: {
                idAds : id,
                _csrf: csrfVar
            },
            success: function (data) {
                $('.bgl-overlay').removeClass( "hidden" );
                document.getElementById("bgl-overlay-seller-phone").innerHTML = data.phone.replace("/\r\n|\r|\n/", '<br/>');
            }
        });
    }

    for (i = 0,len; i < len; i++){
        listAdsId[i].onclick = markSelection;
    }
});

$(function () {
    'use strict';
    $('#owner-support').on('click', function (e) {
        e.preventDefault();
        $.ajax($(this).data('url'), {
            type: 'POST',
            success: function (html) {
                $('body').append(html);
                $('#owner-modal').modal();
            }
        });
    });
});

//функция отображения подкатегорий продуктов
$(function($) {
    'use strict';

    $(".default-product-category-menu ul li").hover(
        function () {
            var id = $(this).attr("data-subcategory-id");
            var subCategory = $("#" + id);
            var realMarginTop = -43;

            if (typeof subCategory.position()  !== "undefined"){
                var botElement = this.getBoundingClientRect().top + subCategory.height();

                if (botElement > $(window).height()){
                    var margin = botElement - $(window).height();
                    subCategory.css('margin-top',realMarginTop - margin);
                } else {
                    subCategory.css('margin-top',realMarginTop);
                }
                subCategory.addClass("visible-sub-category");
            }
        },
        function () {
            var id = $(this).attr("data-subcategory-id");
            var subCategory = $("#" + id);
            subCategory.removeClass("visible-sub-category");
        }
    );

    $(".test-sub-cat").hover(
        function () {
            $(this).addClass("visible-sub-category");

        },
        function () {
            $(this).removeClass("visible-sub-category");
        }
    );

    //////////////////////////////////////////////////////
    // NAVIGATION SEARCH SCRIPT
    //////////////////////////////////////////////////////
    $(function(){
        $('.pull-right.btn-search').hover(function(){
                $('.mini-search').show();
            },
            function(){
                $('.mini-search').hide();
            });
    });

    $(function(){
        $('.mini-search').hover(function(){
                $('.mini-search').show();
            },
            function(){
                $('.mini-search').hide();
            });
    });
});
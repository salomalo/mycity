$(function(){
    'use strict';

    var commentSection = $('#listing-detail-section-reviews');

    $('#form-comment-add').on('beforeSubmit', function(e) {
        e.preventDefault();

        var form = $(this).serialize() + '&mongo=' + commentSection.data('mongo');
        var url = commentSection.data('url');
        
        $.ajax(url, {type: 'POST', dataType: 'html', data: form, success: function (response) {
            var commentList = commentSection.find('ul.review-list');
            var commentFirst = commentList.find('li:first-child');

            if (commentFirst.length > 0) {
                commentFirst.before(response);
            } else {
                commentList.html(response);
            }

            $('#comment-text').val('');
        }});

        return false;
    });
});
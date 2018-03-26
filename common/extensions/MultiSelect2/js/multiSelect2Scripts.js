function getEventChange(mySelect, url)
{
    text = '';
    temp = mySelect.select2('data');
    if(temp[0]){
        text = temp[0]['text'];
    }
    $.ajax({
        type:'POST',
        url: url,
        async: false,
        dataType: 'json',
        data: 'id=' + mySelect.val() + '&text=' + text,
        success: function(responce)
            {
                mySelect.data('json', responce);
            }
    });
    if(mySelect.data('json').length > 1){
        mySelect.find('option')
        .remove()
        .end()
        .append('<option value >Выберите категорию ...</option>');
    }
    mySelect.select2({
        data: mySelect.data('json')
    });

    settings = mySelect.attr('data-krajee-select2');
    settings = window[settings];
    mySelect.select2(settings);

    if(mySelect.data('json').length > 1){
        mySelect.select2('open');
    } else {
        mySelect.select2('close');
    }
}
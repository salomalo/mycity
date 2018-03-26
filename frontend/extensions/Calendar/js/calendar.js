function Calendar2(id, year, month, selDay) {
    var today  = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1;
    var yyyy = today.getFullYear();

    var Dlast = new Date(year,month+1,0).getDate(),
        D = new Date(year,month,Dlast),
        DNlast = new Date(D.getFullYear(),D.getMonth(),Dlast).getDay(),
        DNfirst = new Date(D.getFullYear(),D.getMonth(),1).getDay(),
        calendar = '<tr>',
        month=["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"];
    if (DNfirst != 0) {
      for(var  i = 1; i < DNfirst; i++) calendar += '<td>';
    } else {
      for(var  i = 0; i < 6; i++) calendar += '<td>';
    }
    for(var  i = 1; i <= Dlast; i++) {
    //  if (i == selDay && D.getFullYear() == new Date().getFullYear() && D.getMonth() == new Date().getMonth()) {
        if (i == dd && parseInt(D.getMonth() + 1) == mm && D.getFullYear() == yyyy) {
            calendar += '<td class="current-date item" data-date="' + i + '-' + parseInt(D.getMonth() + 1) + '-' + D.getFullYear() + '">' + i;
        } else if (i == selDay) {
            calendar += '<td class="today item" data-date="' + i + '-' + parseInt(D.getMonth() + 1) + '-' + D.getFullYear() + '">' + i;
        } else {
            calendar += '<td class="item" data-date="' + i + '-' + parseInt(D.getMonth() + 1) + '-' + D.getFullYear() + '">' + i;
        }
      if (new Date(D.getFullYear(),D.getMonth(),i).getDay() == 0) {
        calendar += '<tr>';
      }
    }
    for(var  i = DNlast; i < 7; i++) calendar += '<td>&nbsp;';
    document.querySelector('#'+id+' tbody').innerHTML = calendar;
    //document.querySelector('#'+id+' thead td:nth-child(2)').innerHTML = month[D.getMonth()] +' '+ D.getFullYear();
    document.querySelector('#calendar .month').innerHTML = month[D.getMonth()] +' '+ D.getFullYear();
    document.querySelector('#'+id+' thead td:nth-child(2)').dataset.month = D.getMonth();
    document.querySelector('#'+id+' thead td:nth-child(2)').dataset.year = D.getFullYear();
    if (document.querySelectorAll('#'+id+' tbody tr').length < 6) {  // чтобы при перелистывании месяцев не "подпрыгивала" вся страница, добавляется ряд пустых клеток. Итог: всегда 6 строк для цифр
        document.querySelector('#'+id+' tbody').innerHTML += '<tr><td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;';
    }
}

var selDay = $('table#calendar2').data('day');
var selMonth = $('table#calendar2').data('month');
var selYear = $('table#calendar2').data('year');

Calendar2("calendar2", selYear, selMonth-1, selDay);
// переключатель минус месяц
document.querySelector('#calendar .prev-month').onclick = function() {
  Calendar2("calendar2", document.querySelector('#calendar2 thead td:nth-child(2)').dataset.year, parseFloat(document.querySelector('#calendar2 thead td:nth-child(2)').dataset.month)-1);
};
// переключатель плюс месяц
document.querySelector('#calendar .next-month').onclick = function() {
  Calendar2("calendar2", document.querySelector('#calendar2 thead td:nth-child(2)').dataset.year, parseFloat(document.querySelector('#calendar2 thead td:nth-child(2)').dataset.month)+1);
};


$( "#calendar2" ).on( "click", "td.item", function() {
    var date = $(this).data('date');
    $('#filter_calendar').find('input[name=time]').val(date);
    $('#filter_calendar').submit();

});
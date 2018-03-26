$(function () {
    "use strict";

    var supportButton = $('#support_button');
    var supportChat = $('#support_chat');
    var supportChatCollapse = supportChat.find('button[data-widget="hide"]');
    var supportChatSend = supportChat.find('button[type="submit"]');
    var supportChatText = supportChat.find('input[name="message"]');
    var supportChatMessages = supportChat.find('div.direct-chat-messages');
    var supportChatHeader = supportChat.find('div.box-header');
    var header = $('header');
    var doc = $(document);
    var win = $(window);

    //Формиование нового сообщения для диалога
    var makeMessage = function (msg, isUser) {
        var now = new Date();

        var messageDiv = document.createElement('div');
        var infoDiv = document.createElement('div');
        var usernameSpan = document.createElement('span');
        var timeSpan = document.createElement('span');
        var userImg = document.createElement('img');
        var textDiv = document.createElement('div');

        messageDiv.className = 'direct-chat-msg' + (isUser ? ' right' : '');
        infoDiv.className = 'direct-chat-info clearfix';
        usernameSpan.className = 'direct-chat-name pull-' + (isUser ? 'right' : 'left');
        timeSpan.className = 'direct-chat-timestamp pull-' + (isUser ? 'left' : 'right');
        textDiv.className = 'direct-chat-text';
        userImg.className = 'direct-chat-img';

        usernameSpan.innerHTML = supportChat.data('username');
        timeSpan.innerHTML = now.getHours() + ':' + now.getMinutes() + ' ' + now.getDate() + '.' + (now.getMonth()+1) + '.' + now.getFullYear();
        textDiv.innerHTML = msg;

        userImg.src = isUser ? '/img/avatar5.png' : '/img/avatar3.png';

        infoDiv.appendChild(usernameSpan);
        infoDiv.appendChild(timeSpan);
        messageDiv.appendChild(infoDiv);
        messageDiv.appendChild(userImg);
        messageDiv.appendChild(textDiv);

        return messageDiv;
    };

    //По нажатию на кнопку показываем чат
    supportButton.on('click', function () {
        supportButton.hide('fast', function() {
            supportChat.show('slow');
        });
    });

    //При сворачивании чата показываем кнопку
    supportChatCollapse.on('click', function (e) {
        e.preventDefault();
        supportChat.hide('fast', function () {
            supportButton.show('slow');
        });
    });

    //При нажатии на кнопку отправки сообщения делаем ajax
    supportChatSend.on('click', function (e) {
        e.preventDefault();
        var msg = $.trim(supportChatText.val());
        var url = supportChat.data('url');

        if (msg) {
            $.ajax(url, {method: 'POST', data: {msg: msg}, dataType: 'text'}).done(function (data) {
                supportChatText.val('');
                supportChatMessages.append(makeMessage(msg, true));
                setTimeout(function() {
                    supportChatMessages.append(makeMessage(data, false));
                }, 2000);
            });
        }
    });

    //Для перемещении окна чата
    supportChatHeader.on('mousedown', function(e) {
        //Меняем курсор
        supportChatHeader.css('cursor', 'move');

        //Отключаем встроеный drag'n'drop
        doc.off('dragstart');

        //При отпускании кнопки
        doc.on('mouseup', function() {
            //Меняем курсор
            supportChatHeader.css('cursor', 'pointer');

            //Отвязываем события
            doc.off('mousemove');
            doc.off('mouseup');
        });

        //Обнуляем смещение снизу\справа
        supportChat.css('right', '0');
        supportChat.css('bottom', '0');

        //Расчитываем смещения
        var offset = supportChatHeader.offset();
        var offsetX = e.screenX - offset.left;
        var offsetY = e.screenY - offset.top;

        //Размер хедера
        var headH = header.height();

        //Расчитываем размер окна
        var docW = win.width() - supportChat.width();
        var docH = win.height() - supportChat.height() + headH;


        doc.on('mousemove', function(e) {
            //Координаты с учетом смещения
            var x = e.screenX - offsetX;
            var y = e.screenY - offsetY;

            //Ограничиваем выход за границы окна
            x = (x > 0) ? ((x <= docW) ? x : docW) : 0;
            y = (y > headH) ? ((y <= docH) ? y : docH) : headH;

            //Устанавливаем координаты
            supportChat.css('left', x + 'px');
            supportChat.css('top', y + 'px');
        });
    });
});

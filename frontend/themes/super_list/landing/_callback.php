<head>
    <script type="text/javascript">
        var csrfVar = '<?=Yii::$app->request->getCsrfToken()?>';
    </script>
</head>

<div class="bingc-passive bingc-passive-closed" id="bingc-passive">
    <div class="bingc-passive-overlay"><a class="bingc-passive-close-button" id="bingc-passive-close-button"
                                          href="javascript:void(0);">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                 y="0px" width="24px" height="24px" viewBox="0 0 24 24" enable-background="new 0 0 24 24"
                 xml:space="preserve"><polygon fill="#FFFFFF"
                                               points="24,0.993 22.985,-0.024 12,10.983 1.015,-0.024 0,0.993 10.985,12 0,23.008 1.015,24.023 12,13.017 22.985,24.023 24,23.008 13.015,12 "></polygon></svg>
        </a>
        <div class="bingc-passive-content" id="bingc-passive-content">
            <div class="bingc-we-already-call-you content-hide" id="second-content">Заказ звонка принят!</div>
            <div id="main-content">
                <div class="bingc-we-will-call-you">
                    <div class="bingc-we-will-call-you-alignment">
                        <div class="bingc-we-will-call-you-restriction">Хотите, перезвоним Вам за <b>10</b> минут?
                        </div>
                    </div>
                </div>
                <div class="bingc-passive-get-phone-form">
                    <form id="bingc-passive-get-phone-form" class="bingc-passive-get-phone-form">
                        <div class="bingc-passive-get-phone-form-input-border"></div>
                        <input type="text" id="bingc-passive-get-phone-form-input"
                               class="bingc-passive-get-phone-form-input" placeholder="Ваш номер телефона" value=""
                               autocomplete="off" onfocus="this.value = this.value;"><a href="javascript:void(0);"
                                                                                        id="bingc-passive-phone-form-button"
                                                                                        class="bingc-passive-phone-form-button">Перезвоните</a><span
                            class="bingc-sample-countdown-timer">10:<span>00<span>:00</span></span> <input
                            type="hidden" id="bingc-passive-get-phone-form-description"
                            class="bingc-passive-get-phone-form-description" value=""></form>
                </div>
                <div id="bingc-phone-sample" class="bingc-phone-sample"><span>Например: 067 000 00 00</span></div>
            </div>
        </div>
    </div>
    <div class="bingc-passive-background" id="bingc-passive-background"></div>
</div>


<a class="bingc-phone-button bingc-show" id="bingc-phone-button" style="bottom: 7%; left: 2%;">
    <div id="bingc-phone-button-tooltip" class="bingc-phone-button-tooltip bingc-phone-button-tooltip-bottom-left">
        Хотите, перезвоним Вам<br>за 10 минут?
        <svg version="1.1" class="bingc-phone-button-arrow" xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="18px" height="14px" viewBox="0 0 18 14"
             enable-background="new 0 0 18 14" xml:space="preserve"><polyline fill="#B3B3B3"
                                                                              points="17.977,0 0.083,0 17.977,13.927 "></polyline></svg>
    </div>
    <svg class="bingc-phone-button-circle" version="1.1" xmlns="http://www.w3.org/2000/svg"
         xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="99px" height="99px" viewBox="0 0 100 100"
         enable-background="new 0 0 100 100" xml:space="preserve">
        <circle class="bingc-phone-button-circle-outside" cx="50" cy="50" r="50"></circle>
        <circle class="bingc-phone-button-circle-inside" cx="50" cy="50" r="40"></circle></svg>
        <div id="bingc-phone-button-icon-text" class="bingc-phone-button-icon-text"><span>КНОПКА<br>СВЯЗИ</span></div>
        <svg id="bingc-phone-button-icon-icon" class="bingc-phone-button-icon-icon bingc-phone-button-icon-show" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="17.544px" height="25.215px" viewBox="0 0 17.544 25.215" enable-background="new 0 0 17.544 25.215" xml:space="preserve">
            <path fill-rule="evenodd" clip-rule="evenodd" fill="#fff" d="M12.22,6.784c-0.135,0.871-1.654,4.073-2.084,4.89c-0.576,1.087-2.779,4.344-3.724,5.065l0,0l-0.775,0.532l-1.879-0.616L0,20.653l0.129,1.043l2.123,2.832l0.916,0.687c0,0,13.474-8.596,14.376-24.03c0,0-0.266-0.297-0.777-0.87L13.228,0l-1.16,0.454l-1.029,4.941l1.127,1.23"></path>
        </svg>
</a>

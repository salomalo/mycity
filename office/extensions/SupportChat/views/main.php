<?php
use yii\helpers\Url;
?>

<div id="support_button">
    <img src="/img/question.png" alt="">
    <span class=>Задать вопрос</span>
</div>

<div id="support_chat" style="display: none" data-username="<?= Yii::$app->user->identity->username ?>" data-url="<?= Url::to(['support/create-ajax']) ?>">
    <div class="box box-primary direct-chat direct-chat-primary">
        <div class="box-header with-border" style="cursor: pointer">
            <h3 class="box-title">Поддержка</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="hide"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body" style="display: block;">
            <div class="direct-chat-messages">
                <div class="direct-chat-msg">
                    <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-left">CityLife</span>
                        <span class="direct-chat-timestamp pull-right"><?= date('H:i d.m.Y') ?></span>
                    </div>
                    <img class="direct-chat-img" src="/img/avatar3.png" alt="">
                    <div class="direct-chat-text">
                        Вы можете задать свой вопрос службе поддержки.
                    </div>
                </div>

            </div>
        </div>
        <div class="box-footer" style="display: block;">
            <div class="input-group">
                <input type="text" name="message" placeholder="Введите вопрос ..." class="form-control">
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-primary btn-flat">Спросить</button>
                </span>
            </div>
        </div>
    </div>
</div>

<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div style="font-family: Verdana, Geneva, sans-serif; font-size: 12px; color: #555555; line-height: 14pt;">
    <div style="width: 590px;">
        <div
            style="background: url('<?= $_SERVER['SERVER_NAME'] ?>/img/email/email-top.png') no-repeat; width: 100%; height: 75px; display: block;">
            <div style="padding-top: 30px; padding-left: 50px; padding-right: 50px;"><a href="http://citylife.info/ru"
                                                                                        target="_blank"> <img
                        class="CToWUd" style="border: none;" src="<?= $_SERVER['SERVER_NAME'] ?>/img/logo-bottom.png"
                        width="62" height="59"/></a></div>
        </div>
        <div
            style="background: url('<?= $_SERVER['SERVER_NAME'] ?>/img/email/email-mid.png') repeat-y; width: 100%; display: block;">
            <div style="padding-left: 50px; padding-right: 50px; padding-bottom: 1px;">
                <div style="border-bottom: 1px solid #ededed;">&nbsp;</div>
                <div style="margin: 20px 0px; font-size: 30px; line-height: 30px; text-align: left;">Заказ отменён!</div>
                <div style="margin-bottom: 30px;">
                    <div>Уважаемый <?= isset($content['fio']) && $content['fio'] != '' ? $content['fio'] : $content['userName'] ?>,
                       продавец отменил заказ:
                    </div>
                    <br/>
                    <div style="margin-bottom: 20px; text-align: left;">
                        <strong>Номер заказа:</strong><?= $content['orderNumber'] ?><br/>
                        <strong>Дата заказа:</strong> <?= $content['orderData'] ?>
                    </div>
                </div>

                <div style="margin: 20px 0;">
                    Контактная информация Продавца: <?= $content['buyerUsername']?><br/>
                    ФИО:<?= isset($content['buyerFio']) && $content['buyerFio'] != '' ? $content['buyerFio'] : 'Не известно' ?><br/>
                    Тел:<?= isset($content['buyerPhone']) && $content['buyerPhone'] != '' ? $content['buyerPhone'] : 'Не известно' ?><br/>
                    Адресс:<?= isset($content['buyerAddress']) && $content['buyerAddress'] != '' ? $content['buyerAddress'] : 'Не известно' ?><br/>
                </div>

                <div><strong>С уважением команда CityLife.Info!</strong></div>
                <div style="border-bottom: 1px solid #ededed;">&nbsp;</div>
                <div style="margin: 10px 5px; display: inline-block;">&nbsp;</div>
                <div style="border-bottom: 1px solid #ededed;">&nbsp;</div>
            </div>
        </div>
    </div>
</div>

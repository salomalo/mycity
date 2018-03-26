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
                <div style="margin: 20px 0px; font-size: 30px; line-height: 30px; text-align: left;">Заказ подтверждён!</div>
                <div style="margin-bottom: 30px;">
                    <div>Уважаемый <?= isset($content['fio']) && $content['fio'] != '' ? $content['fio'] : $content['userName'] ?>,
                        теперь вы можете оплатить заказ:
                    </div>
                    <br/>
                    <div style="margin-bottom: 20px; text-align: left;">
                        <strong>Номер заказа:</strong><?= $content['orderNumber'] ?><br/>
                        <strong>Дата заказа:</strong> <?= $content['orderData'] ?>
                    </div>
                </div>
                <div>
                    <div>&nbsp;</div>
                    <table style="width: 100%; margin: 5px 0;">
                        <tbody>
                        <tr>
                            <td style="text-align: left; font-weight: bold; font-size: 12px;">Название</td>
                            <td style="text-align: right; font-weight: bold; font-size: 12px;" width="45"></td>
                            <td style="text-align: right; font-weight: bold; font-size: 12px;" width="70">Цена</td>
                            <td style="text-align: right; font-weight: bold; font-size: 12px;" width="70">К-во</td>
                        </tr>
                        </tbody>
                    </table>
                    <div style="border-bottom: 1px solid #ededed;">&nbsp;</div>
                    <table style="width: 100%; margin: 5px 0;">
                        <tbody>
                        <?php $sum = 0; ?>
                        <?php foreach ($content['ads'] as $business => $ads) :?>
                            <?php foreach ($ads as $ad) :?>
                                <?php $sum += $ad->price * $business ;?>
                                <tr>
                                    <td style="text-align: left; font-size: 12px; padding-right: 10px;">
                                        <a href="<?= 'http://' . $ad->getSubDomain() . Yii::$app->params['appFrontend'] . $ad->getFrontendUrl() ?>" target="_blank">
                                            <?= $ad->title ?>
                                        </a>
                                    </td>
                                    <td style="text-align: left; font-size: 12px;;" width="45">
                                        <img class="CToWUd"
                                             style="border: none;"
                                             src="<?= \Yii::$app->files->getUrl($ad, 'image', 165) ?>"
                                             alt="<?= $ad->title ?>" width="40"
                                             height="40"/>
                                    </td>
                                    <td style="text-align: right; font-size: 12px;" width="70"><?= $ad->price ?>&nbsp;грн.</td>
                                    <td style="text-align: right; font-size: 12px;" width="70"><?= $business ?></td>
                                </tr>
                            <?php endforeach;?>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                    <div style="border-bottom: 1px solid #ededed;">&nbsp;</div>
                    <table style="width: 100%; margin: 5px 0;">
                        <tbody>
                        <tr>
                            <td style="text-align: right; font-size: 12px;" width="150">Итого: <?= $sum ?>&nbsp;грн.</td>
                        </tr>
                        </tbody>
                    </table>
                    <div style="border-bottom: 1px solid #ededed;">&nbsp;</div>
                    <table style="width: 100%; margin: 5px 0 15px 0; padding: 0; border-spacing: 0;">
                        <tbody>
                        <tr>
                            <td style="text-align: left; font-weight: bold; font-size: 12px; vertical-align: top;">
                                Способ оплаты:
                            </td>
                            <td>
                                <table style="margin-left: auto; font-size: 12px;">
                                    <tbody>
                                    <?php /** @var \common\models\UserPaymentType $paymentType */ ?>
                                    <?php foreach ($content['listPaymentSellerType'] as $paymentType) : ?>
                                        <tr>
                                            <td style="font-size: 12px; text-align: right;">
                                                <?= $paymentType->paymentType->title ?><br/>
                                                <strong><?= $paymentType->description; ?></strong>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: left; font-weight: bold; font-size: 12px; vertical-align: top;">
                                Способ доставки:
                            </td>
                            <td>
                                <table style="margin-left: auto; font-size: 12px;">
                                    <tbody>
                                    <tr>
                                        <td style="font-size: 12px; text-align: right;"><?= $content['delivery'] ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
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

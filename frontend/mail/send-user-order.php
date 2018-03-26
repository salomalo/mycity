<?php
use common\models\User;
use yii\helpers\Url;

/**
 * @var $content
 */
?>
<div style="font-family: Verdana, Geneva, sans-serif; font-size: 12px; color: #555555; line-height: 14pt;">
    <div style="width: 590px;">
        <div
            style="background: url('<?= $_SERVER['SERVER_NAME'] ?>/img/email/email-top.png') no-repeat; width: 100%; height: 75px; display: block;">
            <div style="padding-top: 30px; padding-left: 50px; padding-right: 50px;">
                <a href="http://citylife.info/ru" target="_blank">
                    <img class="CToWUd" style="border: none;" src="<?= $_SERVER['SERVER_NAME'] ?>/img/logo-bottom.png" width="62" height="59"/>
                </a>
            </div>
        </div>
        <div
            style="background: url('<?= $_SERVER['SERVER_NAME'] ?>/img/email/email-mid.png') repeat-y; width: 100%; display: block;">
            <div style="padding-left: 50px; padding-right: 50px; padding-bottom: 1px;">
                <div style="border-bottom: 1px solid #ededed;">&nbsp;</div>
                <div style="margin: 20px 0px; font-size: 30px; line-height: 30px; text-align: left;">Спасибо!</div>
                <div style="margin-bottom: 30px;">
                    <div>Уважаемый <?= isset($content['fio']) && $content['fio'] != '' ? $content['fio'] : $content['userName'] ?> Вы совершили покупку
                        <?php if (isset($content['sellerData']) && count($content['sellerData']) > 0) : ?>
                            у
                            <?php foreach ($content['sellerData'] as $seller) : ?>
                                <?php $user = User::findOne($seller->user_id); ?>
                                <?= $user->username . ', ' ?><br/>
                            <?php endforeach; ?>
                        <?php endif; ?>
                         на CityLife.Info.
                    </div>
                    <br/>
                    <div style="margin-bottom: 20px; text-align: left;">
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
                                <?php
                                if (isset($ad->discount) && $ad->discount) {
                                    $sum += $ad->price * (1 - $ad->discount / 100) * $business ;
                                } else {
                                    $sum += $ad->price * $business ;
                                }

                                ?>
                                <tr>
                                    <td style="text-align: left; font-size: 12px; padding-right: 10px;">
                                        <a href="<?= $_SERVER['SERVER_NAME'] . Url::to(['/ads/view','alias'=>$ad->_id.'-'.$ad->url]) ?>" target="_blank">
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
                                    <td style="text-align: right; font-size: 12px;" width="70"><?= $ad->discount ? $ad->price * (1 - $ad->discount / 100) : $ad->price ?>&nbsp;грн.</td>
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
                                    <tr>
                                        <td style="font-size: 12px; text-align: right;"><?= $content['paymentType'] ?></td>
                                    </tr>
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

                <div style="margin: 20px 0;">Ожидайте в скором времени с вами свяжется продавец</div>
                <div style="margin-bottom: 20px;">После завершения сделки следует оставить соответствующий отзыв контрагенту.</div>
                <div><strong>С уважением команда CityLife.Info!</strong></div>
                <div style="border-bottom: 1px solid #ededed;">&nbsp;</div>
                <div style="margin: 10px 5px; display: inline-block;">&nbsp;</div>
                <div style="border-bottom: 1px solid #ededed;">&nbsp;</div>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 */

use common\extensions\ViewCounter\BusinessViewCounter;
use common\models\Business;
use common\models\UserPaymentType;
use frontend\extensions\BusinessFormatTime\BusinessFormatTime;
use frontend\extensions\GoodCategory\GoodCategory;
use frontend\models\BusinessContact;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$image = Yii::$app->files->getUrl($model, 'image', 200);

$vk_url = str_replace(['http://', 'https://'], '', $model->urlVK);
$fb_url = str_replace(['http://', 'https://'], '', $model->urlFB);
$tw_url = str_replace(['http://', 'https://'], '', $model->urlTwitter);

$user = Yii::$app->user->identity;
?>

<div class="col-sm-4 col-lg-3">
    <div id="secondary" class="secondary sidebar" style="margin-top: 50px;">
        <?= GoodCategory::widget(['businessModel' => $model]) ?>

        <div id="listing_details-2" class="widget widget_listing_details">
            <?php
            $userPayments = UserPaymentType::getAll($model->idUser);
            ?>
            <div class="widget-inner">
                <?php if (!empty($userPayments)) : ?>
                    <div>
                        <div class="info-time" style="color: black;">Cпособы оплаты:</div>
                        <div class="time-lines" style="padding-left: 20px;font-size: 14px;color: #4c4c4e;"><br>
                            <?php foreach ($userPayments as $key => $payment) : ?>
                                <?= $payment ?><br>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="right-col-shop-info-times"><?= BusinessFormatTime::widget(['id' => $model->id]) ?></div>

                <?php if ($model->phone) : ?>
                    <div>
                        <div class="info-time" style="color: black;">Контакты:</div>
                        <div class="time-lines" style="padding-left: 20px;font-size: 14px;color: #4c4c4e;"><br>
                            <span class="value"
                                  itemprop="telephone"><?= preg_replace("/\r\n|\r|\n/", '<br/>', $model->phone); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
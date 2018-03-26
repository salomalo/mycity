<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 * @var $pid integer current category id
 */

use common\extensions\ViewCounter\BusinessViewCounter;
use common\models\Action;
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

$timeZone = new \DateTimeZone(Yii::$app->params['timezone']);
$now = new DateTime();
$date = time();

$now->setTimezone($timeZone);
$now->setTimestamp($date);
$nowDate = $now->format('Y-m-d 00:00:00');
$dateTwo = $now->modify('+1 day')->format('Y-m-d 00:00:00');

$actions = Action::find()
    ->where(['idCompany' => $model->id])
    ->andWhere(['>=', 'dateEnd', $nowDate])
    ->andWhere(['<=', 'dateStart', $dateTwo])
    ->groupBy(['action."dateStart"', 'action."id"'])
    ->orderBy(['action."dateStart"' => SORT_ASC])
    ->limit(3)
    ->all();
?>

<div class="col-sm-4 col-lg-3">
    <div id="secondary" class="secondary sidebar" style="margin-top: 30px;">

        <?= GoodCategory::widget(['businessModel' => $model, 'pid' => isset($pid) ? $pid : null]) ?>

        <?php if ($actions) : ?>
            <div id="listings-3" class="widget widget_listings">
                <div class="widget-inner">
                    <h2 class="widgettitle">Акции</h2>
                    <div class="type-small items-per-row-1">
                        <div class="listings-row">
                            <?php foreach ($actions as $item): ?>
                                <?php
                                $start = new DateTime($item->dateStart);
                                $end = new DateTime($item->dateEnd);
                                $alias = $item->id . '-' . $item->url;
                                ?>
                                <div class="listing-container">
                                    <div class="listing-small">
                                        <div class="listing-small-image"
                                             style="background-image: url('<?= \Yii::$app->files->getUrl($item, 'image', 195) ?>');">
                                            <a href="<?= Url::to(['action/view', 'alias' => $alias]) ?>"></a>
                                        </div><!-- /.listing-small-image -->

                                        <div class="listing-small-content">
                                            <h4 class="listing-small-title"><a
                                                    href="<?= Url::to(['action/view', 'alias' => $alias]) ?>">
                                                    <?= $item->title ?>
                                                </a></h4>

                                            <div class="listing-small-location"><a
                                                    href="<?= Url::to(['action/index', 'pid' => $item->category->url]) ?>">
                                                    <?= $item->category->title ?>
                                                </a></div>

                                            <div class="listing-small-price"><span>c <?= $start->format('d.m.Y') ?> по <?= $end->format('d.m.Y') ?></span></div>
                                        </div><!-- /.listing-small-content -->
                                    </div><!-- /.listing-small -->
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

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
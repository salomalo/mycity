<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Afisha
 */

use common\models\Business;
use frontend\extensions\AdBlock;
use frontend\extensions\BusinessFormatTime\BusinessFormatTime;
use yii\helpers\Html;

$image = null;
$alias = ['/business/index'];

$business = null;
if ($model->isFilm) {
    $scheduleQuery = $model->getSchedule_kino();
    if (Yii::$app->request->city) {
        $scheduleQuery->where(['idCity' => Yii::$app->request->city->id]);
    }
    /** @var \common\models\ScheduleKino $schedule */
    $schedule = $scheduleQuery->one();
    $business = isset($schedule->company) ? $schedule->company : null;
} else {
    if (isset($model->idsCompany) && $model->idsCompany[0]  != '') {
        $businessQuery = Business::find()->where(['id' => $model->idsCompany]);
        if (Yii::$app->request->city) {
            $businessQuery->andWhere(['idCity' => Yii::$app->request->city->id]);
        }
        $business = $businessQuery->one();
    }
}

if ($business) {
    $image = $business->image ? Yii::$app->files->getUrl($business, 'image') : null;
    $alias = ['/business/view', 'alias' => "{$business->id}-{$business->url}"];
}
$link = Html::a(Html::img($image), $alias, ['data' => ['background-image' => $image]]);
?>

<div class="col-sm-4 col-lg-3">
    <div id="secondary" class="secondary sidebar" itemprop="location" itemscope itemtype="http://schema.org/Place">
        <div class="quad-block-a"><?= AdBlock::getQuad() ?></div>

        <div id="listing_author-2" class="widget widget_listing_author" style="margin-top: 20px;">
            <div class="widget-inner">
                <div class="listing-author">
                    <p style="font-size: 20px;">
                        <?= empty($model->companys[0]) ? '' : Html::a(Html::tag('span',$model->companys[0]->title,['itemprop'=>'name']), $alias) ?>
                    </p>
                    <?= $link ?>
                </div>
            </div>
        </div>

        <?php if ($business) : ?>
            <div id="listing_details-2" class="widget widget_listing_details">
                <div class="widget-inner" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                    <?php if (isset($business->address[0])) : ?>
                        <p style="color: #248dc1;" itemprop="streetAddress">
                            <i class="fa fa-map-marker"></i><?= $business->address[0]->address ?>
                        </p>
                        <hr>
                    <?php endif; ?>

                    <?= BusinessFormatTime::widget(['id' => $business->id]) ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="left-block-a"><?= AdBlock::getLeft() ?></div>
    </div>
</div>
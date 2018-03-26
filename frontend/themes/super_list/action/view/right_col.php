<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Action
 */

use frontend\extensions\AdBlock;
use frontend\extensions\BusinessFormatTime\BusinessFormatTime;
use yii\helpers\Html;

$image = null;
$alias = ['/business/index'];

if ($model->companyName) {
    $image = $model->companyName->image ? Yii::$app->files->getUrl($model->companyName, 'image') : null;
    $alias = ['/business/view', 'alias' => "{$model->companyName->id}-{$model->companyName->url}"];
}
$link = Html::a(Html::img($image), $alias, ['data' => ['background-image' => $image]]);
?>

<div class="col-sm-4 col-lg-3">
    <div id="secondary" class="secondary sidebar" style="margin-bottom: 0;">
        <div class="quad-block-a"><?= AdBlock::getQuad() ?></div>

        <div id="listing_author-2" class="widget widget_listing_author" style="margin-top: 20px;">
            <div class="widget-inner">
                <div class="listing-author">
                    <p style="font-size: 20px;">
                        <?= empty($model->companyName) ? '' : Html::a($model->companyName->title, $alias) ?>
                    </p>
                    <?= $link ?>
                </div>
            </div>
        </div>

        <?php if ($model->companyName) : ?>
            <div id="listing_details-2" class="widget widget_listing_details">
                <div class="widget-inner">
                    <?php if (isset($model->companyName->address[0])) : ?>
                        <p style="color: #248dc1;">
                            <i class="fa fa-map-marker"></i><?= $model->companyName->address[0]->address ?>
                        </p>
                        <hr>
                    <?php endif; ?>

                    <?= BusinessFormatTime::widget(['id' => $model->companyName->id]) ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="left-block-a"><?= AdBlock::getLeft() ?></div>
    </div>
</div>
<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Action
 */

use frontend\extensions\ActionRemaingTime\ActionRemaingTime;
use yii\helpers\Html;

$alias = "{$model->id}-{$model->url}";
$image = Html::img(Yii::$app->files->getUrl($model, 'image', 165), ['alt' => $model->title]);
?>

<div class="listing-container">
    <div class="listing-row featured">

        <div class="short-image">
            <?= Html::a($image, ['/action/view', 'alias' => $alias], ['class' => "listing-row-image-link"]) ?>
        </div>

        <div class="listing-row-body">
            <h2 class="listing-row-title">
                <?= Html::a($model->title, ['/action/view', 'alias' => $alias], ['class' => 'my-color-link']) ?>
            </h2>
            
            <div class="listing-row-content">
                <p><?= ActionRemaingTime::widget(['model' => $model, 'template' => 'index_super_list']) ?></p>
            </div>
        </div>

        <div class="listing-row-properties">
            <dl>
                <?php if ($model->category): ?>
                    <dt><?= Yii::t('action', 'Category') ?></dt>
                    <dd><?= $model->category->title ?></dd>
                <?php endif; ?>

                <dt><?= Yii::t('business', 'views') ?></dt>
                <dd><?= $model->countView ? $model->countView->count : 0 ?></dd>

                <dt><?= Yii::t('ads', 'Comments') ?></dt>
                <dd><?= $model->getComments($model->id) ?></dd>
            </dl>
        </div>

    </div>
</div>
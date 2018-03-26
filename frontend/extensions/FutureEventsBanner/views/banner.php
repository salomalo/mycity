<?php
/**
 * @var $model \common\models\Afisha
 * @var $this \yii\web\View
 */
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="block future-event" data-url="<?= $model->url ?>">
    <div class="future-event-content">
        <div class="future-event-title"><?= $model->title ?></div>
        <?php if ($model->image) : ?>
            <?php $img = Yii::$app->files->getUrl($model, 'image') ?>
            <?= Html::img($img, ['alt' => $model->title, 'style' => 'width: 100%;']) ?>
        <?php endif; ?>
    </div>
</div>
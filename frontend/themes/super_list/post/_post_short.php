<?php
/**
 * @var $index int
 * @var $model \common\models\Post
 */

use common\models\File;
use frontend\extensions\ThemeButtons\AddFavourite;
use frontend\extensions\ThemeButtons\ShareButton;
use yii\helpers\Html;

mb_internal_encoding('UTF-8');
$date = new DateTime($model->dateCreate);
$image = Html::img(Yii::$app->files->getUrl($model, 'image', 600), ['class' => 'action-img', 'alt' => $model->title]);
?>

<div class="listing-container">
    <div class="listing-row featured">
        <div class="short-image">
            <?= Html::a($image, $model->getRoute()) ?>
        </div>

        <div class="listing-row-body">
            <h2 class="listing-row-title">
                <?= Html::a($model->title, $model->getRoute(), ['class' => 'my-color-link']) ?>
            </h2>
            <div class="listing-row-content">
                <p><?= mb_substr(strip_tags($model->shortText), 0, 100) . ($model->shortText == '' ? '' : '...') ?></p>
                <p><?= Html::a(Yii::t('business', 'Read more'), $model->getRoute(), ['class' => 'title']) ?></p>
            </div>
        </div>

        <div class="listing-row-properties">
            <dl>
                <dt><?= Yii::t('post', 'Date') ?></dt>
                <dd><?= $date->format('d.m.Y, H:i') ?></dd>

                <dt><?= Yii::t('post', 'View') ?></dt>
                <dd><?= ($model->countView) ? $model->countView->count : 0 ?></dd>

                <dt><?= Yii::t('ads', 'Comments') ?></dt>
                <dd><?= $model->comment_count ?></dd>
            </dl>
        </div>
    </div>
</div>
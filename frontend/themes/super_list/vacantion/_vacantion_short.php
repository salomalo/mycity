<?php
/**
 * @var $this \yii\web\View
 * @var \common\models\WorkVacantion $model
 */

use common\models\File;
use frontend\extensions\ThemeButtons\AddFavourite;
use frontend\extensions\ThemeButtons\ShareButton;
use yii\helpers\Html;

$alias = "{$model->id}-{$model->url}";
$image = !empty($model->company->image) ? Html::img(Yii::$app->files->getUrl($model->company, 'image', 165), ['alt' => $model->title, 'class' => 'action-img']) : Html::img('img/noImg.jpg', ['alt' => $model->title, 'class' => 'action-img']);
?>

<div class="listing-container">
    <div class="listing-row featured">
        <div class="short-image">
            <?= Html::a($image, ['/vacantion/view', 'alias' => $alias]) ?>
        </div>

        <div class="listing-row-body">
            <h2 class="listing-row-title">
                <?= Html::a($model->title, ['/vacantion/view', 'alias' => $alias], ['class' => 'title']) ?>
            </h2>
        </div>

        <div class="listing-row-properties">
            <dl>
                <dt><?= Yii::t('business', 'views') ?></dt>
                <dd><?= $model->countView ? $model->countView->count : 0 ?></dd>

                <dt><?= Yii::t('vacantion', 'We_offer_salary') ?></dt>
                <dd><?= $model->salary ?></dd>

                <dt><?= Yii::t('vacantion', 'Working_conditions') ?></dt>
                <dd>
                    <?= ($model->isFullDay) ? Yii::t('vacantion', 'Full_day') : Yii::t('vacantion', 'Not_a_full_day') ?>
                    , <?= ($model->isOffice) ? Yii::t('vacantion', 'office_work') : Yii::t('vacantion', 'work_remotely') ?>
                </dd>

                <?php if (isset($model->company)): ?>
                    <dt><?= Yii::t('vacantion', 'Where') ?></dt>
                    <dd><?= Html::a($model->company->title, ['/business/view', 'alias' => "{$model->company->id}-{$model->company->url}"]) ?></dd>
                <?php endif; ?>
                <?php if ($model->category): ?>
                    <dt><?= Yii::t('vacantion', 'Category') ?></dt>
                    <dd><?= Html::a($model->category->title, ['/vacantion/index', 'pid' => $model->category->url]) ?></dd>
                <?php endif; ?>
            </dl>
        </div>
    </div>
</div>
<?php
/**
 * @var $this \yii\web\View
 * @var \common\models\WorkResume $model
 */

use common\models\File;
use frontend\extensions\ThemeButtons\AddFavourite;
use frontend\extensions\ThemeButtons\ShareButton;
use yii\helpers\Html;

$alias = "{$model->id}-{$model->url}";
$image = empty($model->photoUrl) ? Html::img('img/noImg.jpg', ['alt' => $model->title, 'class' => 'action-img']) : Html::img(Yii::$app->files->getUrl($model, 'photoUrl', 90), ['alt' => $model->title, 'class' => 'action-img']);
?>

<div class="listing-container">
    <div class="listing-row featured">
        <div class="short-image">
            <?= Html::a($image, ['/resume/view', 'alias' => $alias]) ?>
        </div>

        <div class="listing-row-body">
            <h2 class="listing-row-title">
                <?= Html::a($model->title, ['/resume/view', 'alias' => $alias], ['class' => 'title']) ?>
            </h2>
        </div>

        <div class="listing-row-properties">
            <dl>
                <?php if($model->idUser && $model->user) : ?>
                    <dt><?= Yii::t('resume', 'Summary_of') ?></dt>
                    <dd><?= $model->user->username ?></dd>
                <?php endif; ?>

                <?php if ($model->salary) : ?>
                    <dt><?= Yii::t('resume', 'I_count_on_salary') ?></dt>
                    <dd><?= $model->salary ?></dd>
                <?php endif; ?>

                <dt><?= Yii::t('resume', 'Experience') ?></dt>
                <dd><?= $model->experience ?></dd>

                <dt><?= Yii::t('vacantion', 'Working_conditions') ?></dt>
                <dd>
                    <?= ($model->isFullDay) ? Yii::t('vacantion', 'Full_day') : Yii::t('vacantion', 'Not_a_full_day') ?>
                    , <?= ($model->isOffice) ? Yii::t('vacantion', 'office_work') : Yii::t('vacantion', 'work_remotely') ?>
                </dd>

                <?php if ($model->category): ?>
                    <dt><?= Yii::t('vacantion', 'Category') ?></dt>
                    <dd><?= Html::a($model->category->title, ['/vacantion/index', 'pid' => $model->category->url]) ?></dd>
                <?php endif; ?>
            </dl>
        </div>
    </div>
</div>
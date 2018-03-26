<?php
/**
 * @var \common\models\Action $model
 * @var string|null|int $pid
 */

use frontend\extensions\ActionRemaingTime\ActionRemaingTime;
use yii\helpers\Html;
use common\models\User;

$image = $model->image ? Html::img(Yii::$app->files->getUrl($model, 'image', 600), ['class' => 'action-img', 'alt' => $model->title]) : null;
?>

<div class="listing-container">
    <div class="listing-row featured">
        <div class="short-image">
            <div class="img-container">
                <?= Html::a($image, $model->getRoute()) ?>
            </div>
        </div>

        <div class="listing-row-body">
            <h2 class="listing-row-title">
                <?= Html::a($model->title, $model->getRoute(), ['class' => 'my-color-link']) ?>

                <?php if (Yii::$app->user->identity and $model->companyName):?>
                    <?php if (($model->companyName->idUser === Yii::$app->user->identity->id) || (Yii::$app->user->identity->role === User::ROLE_EDITOR)) : ?>
                        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/action/update', 'alias' => "{$model->id}-{$model->url}"])?>
                    <?php endif; ?>
                <?php endif;?>
            </h2>
            <div class="listing-row-content">
                <p><?= ActionRemaingTime::widget(['model' => $model, 'template' => 'index_super_list']) ?></p>
            </div>
        </div>

        <div class="listing-row-properties">
            <dl>
                <dt><?= Yii::t('action', 'Where_is_the')?></dt>
                <dd>
                    <?php if ($model->companyName) : ?>
                        <?= Html::a($model->companyName->title, $model->companyName->getRoute(), ['class' => 'mesto-loc']) ?>
                    <?php endif; ?>
                </dd>

                <?php if($model->price):?>
                    <dt><?= Yii::t('action', 'cost')?></dt>
                    <dd><?= $model->price?></dd>
                    <span class="money"> </span>
                <?php endif;?>

                <dt><?= Yii::t('business', 'views') ?></dt>
                <dd><?= $model->countView ? $model->countView->count : 0 ?></dd>

                <dt><?= Yii::t('ads', 'Comments') ?></dt>
                <dd><?= $model->getComments($model->id)?></dd>

                <?php if(!$pid and is_object($model->category)) : ?>
                    <dt><?= Yii::t('action', 'Category') ?></dt>
                    <dd>
                        <?php if ($model->companyName and $model->companyName->city) : ?>
                            <?= Html::a($model->category->title, $model->category->getRoute($model->companyName->city->subdomain)) ?>
                        <?php endif; ?>
                    </dd>
                <?php endif; ?>
            </dl>
        </div>
    </div>
</div>
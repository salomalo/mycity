<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Advertisement */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('advertisement', 'Advertisements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advertisement-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('advertisement', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('advertisement', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('advertisement', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a(Yii::t('advertisement', 'Index'), ['index'], ['class' => 'btn btn-info']) ?>
        <?= Html::a(Yii::t('advertisement', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user.username',
            [
                'attribute' => 'position',
                'value' => $model->positionLabel,
            ],
            [
                'attribute' => 'status',
                'value' => $model->statusLabel,
            ],
            'title',
            [
                'attribute' => 'image',
                'value' => Html::img(Yii::$app->files->getUrl($model, 'image'), ['style' => 'max-height: 200px']),
                'format' => 'html',
            ],
            'url:url',
            'city.title',
            'date_start:date',
            'date_end:date',
            'created_at:datetime',
        ],
    ]) ?>

</div>

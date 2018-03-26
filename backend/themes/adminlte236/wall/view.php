<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Wall;

/* @var $this yii\web\View */
/* @var $model common\models\Wall */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Walls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wall-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('List', ['index'], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'pid',
            [
                'attribute' => 'type',
                'value' => Wall::$types[$model->type],
            ],
            [
                'attribute' => 'idCity',
                'value' => ($model->city)? $model->city->title : '',
            ],
            'title:ntext',
            'description:html',
            'url',
            [
                'attribute' => 'image',
                'format' => 'raw',
                'value' => ($model->image)? Html::img($model->image, ['width' => '100']) : '',
            ],
            'dateCreate',
        ],
    ]) ?>

</div>

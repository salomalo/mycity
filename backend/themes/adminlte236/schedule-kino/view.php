<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ScheduleKino */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Schedule Kinos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schedule-kino-view">

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
            [
                'attribute' => 'idAfisha',
                'value' => $model->afisha->title,
            ],
            [
                'attribute' => 'idCompany',
                'value' => ($model->company)? $model->company->title : '',
            ],
            [
                'attribute' => 'idCity',
                'value' => $model->city->title,
            ],
            'dateStart',
            'dateEnd',
            [
                'attribute' => 'times',
                'value' => $model->getTimes($model->times),
            ],
            [
                'label' => 'Время 2D',
                'value' => $model->getTimes($model->times2D),
            ],
            [
                'label' => 'Время 3D',
                'value' => $model->getTimes($model->times3D),
            ],
            'price',
        ],
    ]) ?>

</div>

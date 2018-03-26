<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ScheduleKino */

$this->title = $model->afisha->title;
$this->params['breadcrumbs'][] = ['label' => 'Schedule Kinos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schedule-kino-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id, 'idCompany'=>$idCompany], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id, 'idCompany'=>$idCompany], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Create', ['create', 'idCompany'=>$idCompany], ['class' => 'btn btn-success']) ?>
        <?= Html::a('List', ['index', 'idCompany'=>$idCompany], ['class' => 'btn btn-info']) ?>
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
                'value' => $model->company->title,
            ],
            [
                'attribute' => 'idCity',
                'value' => $model->city->title,
            ],
            'dateStart',
            'dateEnd',
            'times',
            'price',
        ],
    ]) ?>

</div>

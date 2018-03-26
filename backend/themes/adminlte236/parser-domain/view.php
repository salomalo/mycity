<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ParserDomain */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Parser Domains', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parser-domain-view">

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
                'attribute' => 'idRegion',
                'value' => ($model->region)? $model->region->title : '',
            ],
            [
                'attribute' => 'idCity',
                'value' => ($model->city)? $model->city->title : '',
            ],
            'domain',
            'mDomain',
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Lang */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Langs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lang-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'url:url',
            'local',
            'name',
            [
                'attribute' => 'default',
                'value' => ($model->default)? 'Да' : ''
            ],
            'date_update',
            'date_create',
        ],
    ]) ?>

</div>

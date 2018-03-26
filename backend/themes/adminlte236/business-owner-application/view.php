<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessOwnerApplication */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Business Owner Applications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="business-owner-application-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Список', ['index'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user.username',
            'business.title',
            'statusLabel',
            'token',
            'created_at:datetime',
        ],
    ]) ?>

</div>
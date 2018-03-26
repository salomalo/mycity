<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ViewCount */

$this->title = 'Просмотры ' . mb_strtolower($model->categoryLabel, 'UTF-8') . ' "' . $model->itemTitle . '"';
$this->params['breadcrumbs'][] = ['label' => 'View Counts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="view-count-view">

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
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Список', ['index'], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label' => 'Месяц',
                'value' => $model->year . '-' . $model->month,
            ],
            [
                'attribute' => 'category',
                'value' =>  $model->categoryLabel,
            ],
            [
                'attribute' => 'item_id',
                'value' =>  $model->itemTitle,
                'format' => 'html',
            ],
            'value',
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessTemplate */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Business Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-template-view">

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Список', ['index'], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'alias',
            [
                'label' => 'Картинка',
                'format' => 'raw',
                'value' =>  Html::img(Yii::$app->files->getUrl($model, 'img', 100)),
            ],
        ],
    ]) ?>

</div>

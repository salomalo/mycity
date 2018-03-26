<?php
/**
 * @var $this yii\web\View
 * @var $model common\models\Counter
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = "Счетчик {$model->title}";
$this->params['breadcrumbs'][] = ['label' => 'Counters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="counter-view">
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
            'title',
            'content:ntext',
            'forOfficeColorLabel:html:Офис',
            'forMainColorLabel:html:Главная страница',
            'forAllCitiesColorLabel:html:Все города',
            'created_at:datetime',
            'citiesString:text:Города',
        ],
    ]) ?>
</div>
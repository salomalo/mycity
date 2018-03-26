<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var common\models\ProductCustomfield $model
 */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Product Customfields', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-customfield-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Create', ['create', 'category'=>$model->idCategory], ['class' => 'btn btn-success']) ?>
        <?= Html::a('List', ['index', 'category' => $model->idCategory], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'category.title:text:Категория',
            'title:text:Название',
            'idCategoryCustomfield' => [
                'label' => 'Категория Customfield',
                'value' => $model->categoryCustomfield ? $model->categoryCustomfield->title : '',
            ],
            'order',
            'alias',
            'type'=>[
                'label' => 'type',
                'value' => ($model->type === $model::TYPE_STRING) ? 'Строка' : 'Список',
            ],
            'isFilter',
        ],
    ]) ?>

    <div style="border: 1px solid #000000;padding: 5px; display: table; margin-bottom: 5px;">
        <div style="width: 200px; display: table-cell;">Значения</div>

        <div style="width: 500px; display: table-cell;">
            <?php foreach ($model->customfieldValue as $customfieldValue) : ?>
                <?= '<div style="border-bottom: 1px dotted #ccc;">', $customfieldValue->value, '</div>' ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
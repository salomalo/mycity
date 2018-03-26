<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var common\models\ProductCategory $model
 */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Категории продуктов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-category-view">


    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены что хотите удалить запись?',
                'method' => 'post',
            ],
        ]) ?>
        <?php
        if($pid != null){
            echo Html::a('Создать', ['add', 'id'=>$pid], ['class' => 'btn btn-success']);
        }
        else echo Html::a('Создать', ['create'], ['class' => 'btn btn-success']);
        
        ?>
        <?= Html::a('Список', ['index'], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',    
            'title',
            'seo_title',
            'seo_description',
            'seo_keywords',            
            [
                'attribute' => 'pid',
                'value' => $parenrTitle,
            ],
            'image',
            'description',
            'hide_description',
        ],
    ]) ?>

</div>

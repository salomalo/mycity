<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\ProductCategory $model
 */

$this->title = 'Создать категорию продукта';
$this->params['breadcrumbs'][] = ['label' => 'Категории продуктов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-category-create">

    Родительская категория: <?=$parent_title?> 

    <?= $this->render('_form', [
        'model' => $model,
        'parent' => $parent,
    ]) ?>

</div>

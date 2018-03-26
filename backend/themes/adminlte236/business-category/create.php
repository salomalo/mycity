<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\BusinessCategory $model
 */

$this->title = 'Create Business Category';
$this->params['breadcrumbs'][] = ['label' => 'Business Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-category-create">

    Родительская категория: <?=$parent_title?> 

    <?= $this->render('_form', [
        'model' => $model,
        'parent' => $parent,
    ]) ?>

</div>

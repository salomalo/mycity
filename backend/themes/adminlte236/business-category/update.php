<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\BusinessCategory $model
 */

$this->title = 'Обновить категорию Предпртиятий: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Категории предприятий', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="business-category-update">

    <?= $this->render('_form', [
        'model' => $model,
//        'modelcategory' => $modelcategory,
//        'nested' => $nested
    ]) ?>

</div>

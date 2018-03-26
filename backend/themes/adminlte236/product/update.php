<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Product $model
 */

$this->title = 'Обновить продукт: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Продукты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="product-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

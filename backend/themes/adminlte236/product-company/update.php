<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\ProductCompany $model
 */

$this->title = 'Update Product Company: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Product Companies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-company-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

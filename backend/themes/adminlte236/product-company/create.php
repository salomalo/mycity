<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\ProductCompany $model
 */

$this->title = 'Create Product Company';
$this->params['breadcrumbs'][] = ['label' => 'Product Companies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-company-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

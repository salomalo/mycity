<?php
/**
 * @var yii\web\View $this
 * @var common\models\Product $model
 */

$this->title = 'Create Ads';
$this->params['breadcrumbs'][] = ['label' => 'Ads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="product-create">
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
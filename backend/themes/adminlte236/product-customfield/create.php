<?php
/**
 * @var yii\web\View $this
 * @var common\models\ProductCustomfield $customfield
 */

$this->title = 'Create Product Customfield';
$this->params['breadcrumbs'][] = ['label' => 'Product Customfields', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="product-customfield-create">
    <?= $this->render('_new_form', ['customfield' => $customfield]) ?>
</div>
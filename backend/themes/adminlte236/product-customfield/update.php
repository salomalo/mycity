<?php
/**
 * @var yii\web\View $this
 * @var common\models\ProductCustomfield $customfield
 */

$this->title = 'Update Product Customfield: ' . ' ' . $customfield->title;
$this->params['breadcrumbs'][] = ['label' => 'Product Customfields', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $customfield->title, 'url' => ['view', 'id' => $customfield->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="product-customfield-update">
    <?= $this->render('_new_form', ['customfield' => $customfield]) ?>
</div>
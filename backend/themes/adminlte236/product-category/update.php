<?php

use kartik\widgets\Alert;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\ProductCategory $model
 * @var bool|string $status
 */

$this->title = 'Обновить категорию продуктов: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Категории продуктов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="product-category-update">
    <?= $status ? Alert::widget([
        'type' => $status,
        'title' => Yii::t('alert', 'title'.$status),
        'icon' => Yii::t('alert', 'icon'.$status),
        'body' => Yii::t('alert', 'body'.$status),
        'showSeparator' => true,
        'delay' => 6000
    ]) : '' ?>

    <?= $this->render('_form', [
        'model' => $model,
        'parent' => '',
    ]) ?>

</div>

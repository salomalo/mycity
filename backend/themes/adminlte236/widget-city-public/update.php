<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\VkWidgetCityPublic */

$this->title = 'Update Vk Widget City Public: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Vk Widget City Publics', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vk-widget-city-public-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

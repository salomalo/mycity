<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Wall */

$this->title = 'Update Wall: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Walls', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="wall-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

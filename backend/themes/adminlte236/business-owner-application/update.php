<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessOwnerApplication */

$this->title = 'Update Business Owner Application: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Business Owner Applications', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="business-owner-application-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', ['model' => $model]) ?>
</div>
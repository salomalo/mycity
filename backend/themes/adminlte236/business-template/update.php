<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessTemplate */

$this->title = 'Бизнесс шаблон: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Business Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="business-template-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

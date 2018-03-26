<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Region */

$this->title = 'Update Region: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Regions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="region-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Action */

$this->title = 'Update Action: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Actions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="action-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

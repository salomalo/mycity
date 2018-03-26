<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Friend */

$this->title = 'Update Friend: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Friends', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="friend-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

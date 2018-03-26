<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ActionCategory */

$this->title = 'Update Action Category: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Action Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="action-category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

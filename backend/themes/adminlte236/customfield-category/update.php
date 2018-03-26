<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CustomfieldCategory */

$this->title = 'Update Customfield Category: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Customfield Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="customfield-category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

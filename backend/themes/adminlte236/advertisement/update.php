<?php

/* @var $this yii\web\View */
/* @var $model common\models\Advertisement */

$this->title = Yii::t('advertisement', 'Update Advertisement') . ": $model->title";
$this->params['breadcrumbs'][] = ['label' => Yii::t('advertisement', 'Advertisements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('advertisement', 'Update');
?>
<div class="advertisement-update">
    <?= $this->render('_form', ['model' => $model]) ?>
</div>

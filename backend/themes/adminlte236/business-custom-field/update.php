<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessCustomField */

$this->title = Yii::t('business_custom_field', 'Update {modelClass}: ', [
    'modelClass' => 'Business Custom Field',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('business_custom_field', 'Business Custom Fields'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('business_custom_field', 'Update');
?>
<div class="business-custom-field-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

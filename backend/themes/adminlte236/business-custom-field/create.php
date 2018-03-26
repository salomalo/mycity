<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BusinessCustomField */

$this->title = Yii::t('business_custom_field', 'Create Business Custom Field');
$this->params['breadcrumbs'][] = ['label' => Yii::t('business_custom_field', 'Business Custom Fields'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-custom-field-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

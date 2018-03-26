<?php

use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ViewCount */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="view-count-form">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-with-disabling-submit']]); ?>

    <?= $form->field($model, 'category')->widget(Select2::className(),[
        'data' => $model::$labels,
        'options' => ['placeholder' => 'Select a category...'],
        'pluginOptions' => ['allowClear' => false],
    ]); ?>
    
    <?= $form->field($model, 'item_id')->textInput() ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'month')->textInput() ?>

    <?= $form->field($model, 'value')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'id' => 'submit_button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

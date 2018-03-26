<?php

use backend\models\AdminComment;
use common\models\Lead;
use kartik\datetime\DateTimePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Lead */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lead-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'utm_source')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'utm_campaign')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->widget(Select2::className(),[
        'data' => Lead::$statusTypes,
        'options' => [
            'placeholder' => 'Выберите статус ...',
            'id' => 'status',
        ],
        'pluginOptions' => ['allowClear' => true]
    ]); ?>

    <?= $form->field($model, 'date_create')->widget(DateTimePicker::className(), [
        'options' => ['placeholder' => 'Выберите дату ...'],
        'pluginOptions' => [
            'autoclose'=>true,
            'todayHighlight' => true,
            'language' => 'ru',
            'pickerPosition' => 'top-right'
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

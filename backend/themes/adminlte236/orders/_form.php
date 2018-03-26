<?php

use common\extensions\CustomCKEditor\CustomCKEditor;
use common\models\User;
use common\models\UserPaymentType;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use common\models\City;
use yii\helpers\ArrayHelper;
use common\models\PaymentType;
use common\extensions\mihaildev\ckeditor\CKEditor as CKEditor2;
use common\extensions\mihaildev\elfinder\ElFinder as ElFinder2;

/* @var $this yii\web\View */
/* @var $model common\models\Orders */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-with-disabling-submit']]); ?>

    <?= $form->field($model, 'idUser')->widget(Select2::className(),[
        'data' => User::getAll(),
        'options' => [
            'placeholder' => 'Select a user ...',
            'id' => 'productUser',
        ],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>
    
    <?= $form->field($model, 'idCity')->widget(Select2::className(), [
        'data' => ArrayHelper::merge(
            Yii::$app->params['adminCities']['select'],
            (($model->idCity and $model->city and !in_array($model->idCity, Yii::$app->params['adminCities']['id'])) ? [$model->idCity => $model->city->title] : [])
        ),
        'options' => ['placeholder' => 'Select a city ...'],
        'pluginOptions' => ['allowClear' => true]
    ]); ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'fio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'paymentType')->widget(Select2::className(), [
        'data' => UserPaymentType::getAll(),
        'options' => [
            'placeholder' => 'Select a type ...',
            'id' => 'paymentType',
        ],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>
    
    <?= $form->field($model, 'description')->widget(CustomCKEditor::className()); ?>

    <?= $form->field($model, 'dateCreate')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use common\models\BusinessOwnerApplication;
use common\models\User;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessOwnerApplication */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="business-owner-application-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->widget(Select2::className(), [
        'data' => User::getAll(),
        'options' => ['placeholder' => 'Выберите пользователя'],
    ]) ?>

    <?= $form->field($model, 'business_id')->widget(Select2::className(), [
        'options' => ['placeholder' => 'Найдите предприятие'],
        'data' => $model->business_id ? [$model->business_id => $model->business->title] : null,
        'pluginOptions' => [
            'minimumInputLength' => 3,
            'language' => ['errorLoading' => new JsExpression("function () { return 'Подождите ...'; }")],
            'ajax' => [
                'url' => Url::to(['business/ajax-search']),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(city) { return city.text; }'),
            'templateSelection' => new JsExpression('function (city) { return city.text; }'),
        ],
    ]) ?>

    <?= $form->field($model, 'status')->widget(Select2::className(), [
        'data' => BusinessOwnerApplication::$statuses,
        'options' => ['placeholder' => 'Выберите статус'],
    ]) ?>

    <?= $form->field($model, 'token')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

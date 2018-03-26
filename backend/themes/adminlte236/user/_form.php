<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use common\models\User;

/**
 * @var $this yii\web\View
 * @var $model common\models\User
 * @var $form yii\widgets\ActiveForm
 */
?>

<div class="user-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $model->isNewRecord ? $form->field($model, 'id')->textInput() : '' ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password_hash', ['enableClientValidation' => false])->passwordInput(['maxlength' => 255, 'value' => '']) ?>

    <?= $form->field($model, 'confirmed_at')->textInput() ?>

    <?= $form->field($model, 'role')->widget(Select2::className(), [
        'data' => User::$roles,
        'options' => ['placeholder' => 'Выберите роль'],
        'pluginOptions' => ['allowClear' => true]
    ]) ?>

    <?= $form->field($model, 'city_id')->widget(Select2::className(), [
        'data' => ArrayHelper::merge(
            Yii::$app->params['adminCities']['select'],
            (($model->city_id and !in_array($model->city_id, Yii::$app->params['adminCities']['id'])) ? [$model->city_id => $model->city->title] : [])
        ),
        'options' => ['placeholder' => 'Выберите город'],
        'pluginOptions' => ['allowClear' => true],
    ]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'public_email')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'bio')->textarea(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
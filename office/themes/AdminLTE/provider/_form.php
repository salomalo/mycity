<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Provider */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="provider-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label(Yii::t('provider', 'Name')) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true])->label(Yii::t('provider', 'Description')) ?>

    <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('provider', 'Create') : Yii::t('provider', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

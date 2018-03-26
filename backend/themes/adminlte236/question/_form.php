<?php

use common\models\QuestionConversation;
use common\models\User;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Question */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="question-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->widget(Select2::className(), [
        'data' => User::getAll(),
        'options' => ['placeholder' => 'Выберите пользователя'],
    ]) ?>

    <?= $form->field($model, 'conversation_id')->widget(Select2::className(), [
        'data' => QuestionConversation::getAll(),
        'options' => ['placeholder' => 'Выберите пользователя'],
    ]) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <div class="form-group"><?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?></div>

    <?php ActiveForm::end(); ?>
</div>


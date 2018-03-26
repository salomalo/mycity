<?php
use common\models\QuestionConversation;
use common\models\User;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this yii\web\View
 * @var $model common\models\QuestionConversation
 * @var $form yii\widgets\ActiveForm
 */

$users = User::getAll();
?>

<div class="question-conversation-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->widget(Select2::className(), [
        'data' => QuestionConversation::$statuses,
        'options' => ['placeholder' => 'Выберите статус'],
    ]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_id')->widget(Select2::className(), [
        'data' => $users,
        'options' => ['placeholder' => 'Выберите пользователя'],
    ]) ?>

    <?= $form->field($model, 'owner_id')->widget(Select2::className(), [
        'data' => $users,
        'options' => ['placeholder' => 'Выберите владельца'],
    ]) ?>

    <?= $form->field($model, 'object_type')->widget(Select2::className(), [
        'data' => QuestionConversation::$object_types,
        'options' => ['placeholder' => 'Выберите тип'],
    ]) ?>

    <?= $form->field($model, 'object_id')->textInput(['maxlength' => true]) ?>

    <div class="form-group"><?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?></div>

    <?php ActiveForm::end(); ?>
</div>


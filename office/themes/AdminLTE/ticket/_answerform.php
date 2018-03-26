<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Ticket */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ticket-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255, 'readonly'=>($model->isNewRecord)? false : true])->label("Заголовок") ?>
    
    <?php foreach ($history as $item): ?>
    <p>
        <?=$item->dateCreate .': '. $item->body?>
    </p>
    <?php endforeach; ?>
    
    <?= $form->field($model, 'body')->textarea()->label("Ответ на тикет") ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Отправить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    
     <?= $form->field($model, 'idUser')->hiddenInput(['value'=>Yii::$app->user->id])->label('') ?>

    <?php ActiveForm::end(); ?>

</div>

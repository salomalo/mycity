<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DateTimePicker;
use common\models\User;
use kartik\widgets\Select2;
use common\models\Comment;

/* @var $this yii\web\View */
/* @var $model common\models\Comment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comment-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-with-disabling-submit']]); ?>

    <?= $form->field($model, 'idUser')->widget(Select2::className(),[
        'data' => User::getAll(),
        'options' => [
            'placeholder' => 'Select a user ...',
            'id' => 'idUser',
            'multiple' => false,
        ],
        'pluginOptions' => ['allowClear' => true],
    ]) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    
    
    <?= $form->field($model, 'type')->widget(Select2::className(),[
        'data' => Comment::$types,
        'options' => [
            'placeholder' => 'Select a type ...',
            'id' => 'type',
        ],
        'pluginOptions' => ['allowClear' => true],
    ]) ?>

    <?= $form->field($model, 'pid')->textInput() ?>
    
    <?= $form->field($model, 'pidMongo')->textInput() ?>

    <?= $form->field($model, 'parentId')->textInput() ?>

    <?= $form->field($model, 'like')->textInput() ?>

    <?= $form->field($model, 'unlike')->textInput() ?>
    
    <?= $form->field($model, 'lastIpLike')->textInput() ?>
    
    <?= $form->field($model, 'rating')->textInput() ?>
    
    <?= $form->field($model, 'ratingCount')->textInput() ?>
    
    <?= $form->field($model, 'lastIpRating')->textInput() ?>
    
    <?= $form->field($model, 'dateCreate')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => 'Enter date and time ...'],
        'pluginOptions' => [
            'autoclose'=>true,
            'todayHighlight' => true,
            'language' => 'ru',
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

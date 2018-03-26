<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PaymentType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-type-form">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-with-disabling-submit']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php if ($model->image): ?>
        <?= Html::img(Yii::$app->files->getUrl($model, 'image', 100)) ?>
        <?= !$model->isNewRecord ? Html::a('<span class="glyphicon glyphicon-trash"></span>',
            ['update', 'id' => $model->id, 'actions' => 'deleteImg'],
            ['title' => 'Delete', 'data-confirm' => 'Are you sure you want to delete this item?']
        ) : null ?>
    <?php endif; ?>

    <?= $form->field($model, 'image')->fileInput()->label('Картинка'); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

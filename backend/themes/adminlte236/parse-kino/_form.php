<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ParseKino */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parse-kino-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'remote_cinema_id')->textInput() ?>

    <?= $form->field($model, 'local_cinema_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

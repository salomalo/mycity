<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="post-form">
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'title')->textInput() ?>

    <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
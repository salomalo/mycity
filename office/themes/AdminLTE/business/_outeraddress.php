<?php
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
?>
<div style="width:500px; float:right;">
<ul>
    <?=$inner?>
</ul>
<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'address')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'phone')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'lat')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'lon')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'idBusiness')->hiddenInput(['value' => $id_parent]) ?>
    <div class="form-group">
        <?= Html::submitButton('Add Address', ['btn btn-success']) ?>
    </div>


<?php ActiveForm::end(); ?>
</div>
<div style="clear:both"></div>
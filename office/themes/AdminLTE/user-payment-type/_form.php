<?php
/**
 * @var $this yii\web\View
 * @var $model \common\models\UserPaymentType
 */

use common\models\PaymentType;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= $form->field($model, 'payment_type_id')->widget(Select2::className(),[
    'data' => PaymentType::getAll(),
    'options' => ['placeholder' => 'Выберите способ'],
    'pluginOptions' => ['allowClear' => false],
]) ?>

<?= $form->field($model, 'description')->textInput() ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
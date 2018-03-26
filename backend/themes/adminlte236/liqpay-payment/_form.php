<?php
/**
 * @var $this yii\web\View
 * @var $model common\models\LiqpayPayment
 * @var $form yii\widgets\ActiveForm
 */

use common\components\LiqPay\LiqPayActions;
use common\components\LiqPay\LiqPayCurrency;
use common\components\LiqPay\LiqPayStatuses;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="liqpay-payment-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->widget(Select2::className(), [
        'data' => LiqPayStatuses::getConstantsLabels(),
        'options' => ['placeholder' => 'Статус'],
        'pluginOptions' => ['allowClear' => true],
    ]) ?>

    <?= $form->field($model, 'order_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'action')->widget(Select2::className(), [
        'data' => LiqPayActions::getConstantsLabels(),
        'options' => ['placeholder' => 'Действие'],
        'pluginOptions' => ['allowClear' => true],
    ]) ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'currency')->widget(Select2::className(), [
        'data' => LiqPayCurrency::getConstantsLabels(),
        'options' => ['placeholder' => 'Валюта'],
        'pluginOptions' => ['allowClear' => true],
    ]) ?>

    <?= $form->field($model, 'data')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
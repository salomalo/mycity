<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use common\models\OrdersAds;
use common\models\City;
use yii\helpers\ArrayHelper;
use common\models\PaymentType;
use common\models\Business;

/* @var $this yii\web\View */
/* @var $model common\models\OrdersAds */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-ads-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-with-disabling-submit']]); ?>

    <?= $form->field($model, 'pid')->textInput() ?>

    <?= $form->field($model, 'idAds')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'countAds')->textInput() ?>

    <?= $form->field($model, 'idBusiness')->textInput() ?>

    <?= $form->field($model, 'status')->widget(Select2::className(), [
        'data' => OrdersAds::$statusList,
        'options' => [
            'placeholder' => 'Select a status ...',
            'id' => 'status',
            'multiple' => false,
        ],
        'pluginOptions' => ['allowClear' => true],
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

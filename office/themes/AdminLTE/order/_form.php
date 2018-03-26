<?php
/* @var $this yii\web\View */
/* @var $model common\models\Orders */
/* @var $form yii\widgets\ActiveForm */

use common\models\Orders;
use common\models\UserPaymentType;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="orders-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'paymentType')->widget(Select2::className(),[
        'data' => UserPaymentType::getAll(Yii::$app->user->id),
        'options' => [
            //'disabled' => true,
            'placeholder' => 'Выберите cпособ оплаты ...',
            'multiple' => false,
        ],
        'pluginOptions' => ['allowClear' => true]
    ])->label('Способ оплаты') ?>

    <?php
    $statusOrder  = [
        Orders::STATUS_NEW => 'Новый',
        Orders::STATUS_CONFIR => 'Подтвержден',
        Orders::STATUS_SENT => 'Отправлен',
        //Orders::STATUS_CANCEL => 'Отменён',
    ];
    ?>

    <?= $form->field($model, 'status')->widget(Select2::className(),[
        'data' => $statusOrder,
        'options' => [
            'placeholder' => 'Выберите статус ...',
            'multiple' => false,
        ],
        'pluginOptions' => ['allowClear' => true]
    ])->label('Статус заказа') ?>

    <div class="form-group">
        <?= Html::submitButton('Подтвердить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
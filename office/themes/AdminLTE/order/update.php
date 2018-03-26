<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use common\models\OrdersAds;

/* @var $this yii\web\View */
/* @var $model common\models\OrdersAds */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('order', 'Edit_of_product_by_order');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="orders-ads-form">

    <?php $form = ActiveForm::begin(); ?>

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
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

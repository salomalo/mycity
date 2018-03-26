<?php
/**
 * @var $this \yii\web\View
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\Lead as Form;

/** @var Form $model */
$model = Yii::createObject(Form::className());
$utm_source = Yii::$app->request->get('utm_source');
$utm_campaign = Yii::$app->request->get('utm_campaign');
?>

    <style>
        #new-form-consultation .form-group {
            margin-bottom: 0;
        }
        #new-form-consultation .form .form-group {
            margin-bottom: 0;
        }
        #new-form-consultation label {
            color: #4b4b4b;
            font-weight: 100;
        }
        #new-form-consultation .help-block-modal {
            color: #ff0000;
        }
        #new-form-consultation .form-group{
            margin-top: 25px;
        }
    </style>

<?php $form = ActiveForm::begin([
    'id' => 'new-form-consultation',
    'enableAjaxValidation' => true,
    'action' => Url::to(['/landing/order-consultation', 'utm_source' => $utm_source, 'utm_campaign' => $utm_campaign]),
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'form lead-form form-light',  'novalidate' => 'novalidate'],
]) ?>

    <div class="col-sm-12">
        <h3 class="editContent highlight">Заказать консультацию</h3>
        <h6 class="call-free-text">Мы перезвоним совершенно бесплатно</h6>
    </div>

<?= $form->field($model, 'name', [
        'template' => "{error}\n{input}\n{label}\n{hint}\n",
        'errorOptions' => ['class' => 'help-block-modal'],
        'inputOptions' => ['placeholder' =>  Yii::t('app', 'Consultation_name'), 'class' => 'modal-input']
    ]
)->label(false) ?>

<?= $form->field($model, 'phone', [
        'template' => "{error}\n{input}\n{label}\n{hint}\n",
        'errorOptions' => ['class' => 'help-block-modal'],
        'inputOptions' => ['placeholder' => Yii::t('app', 'Consultation_phone'), 'class' => 'modal-input']
    ]
)->label(false) ?>

<?= $form->field($model, 'description', [
    'template' => "{error}\n{input}\n{label}\n{hint}\n",
    'errorOptions' => ['class' => 'help-block-modal'],
    'inputOptions' => ['placeholder' => Yii::t('app', 'Consultation_description'), 'class' => 'modal-input']
])->textarea(['rows' => 3])->label(false) ?>

    <div class="modal-footer our-modal-footer">
        <?php /*Html::submitButton(Yii::t('app', 'Sign_up'), [
            'class' => 'new-modal-button new-but-l new-reg-button',
            'name' => 'login-button',
            'style' => 'float: left;',
            'id' => 'btn-for-login-form',
            'disabled' => false,
        ]) */?>

        <?= Html::submitButton('Заказать', [
            'class' => 'new-modal-button new-but-l new-reg-button btn-hover',
            'name' => 'consultation-button',
            'disabled' => false,
        ]) ?>
    </div>

<?php ActiveForm::end(); ?>
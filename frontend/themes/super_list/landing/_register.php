<?php
/**
 * @var $this \yii\web\View
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\RegistrationForm as Form;

/** @var Form $model */
$model = Yii::createObject(Form::className());
$utm_source = Yii::$app->request->get('utm_source');
$utm_campaign = Yii::$app->request->get('utm_campaign');
?>

<style>
    #new-form-signup .form-group {
         margin-bottom: 0;
    }
    #new-form-signup .form .form-group {
         margin-bottom: 0;
    }
    #new-form-signup label {
        color: #4b4b4b;
        font-weight: 100;
    }
    #new-form-signup .help-block-modal {
        color: #ff0000;
    }
</style>

<?php $form = ActiveForm::begin([
    'id' => 'new-form-signup',
    'enableAjaxValidation' => true,
    'action' => Url::to(['user/registration/register-landing', 'utm_source' => $utm_source, 'utm_campaign' => $utm_campaign]),
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'form lead-form form-light',  'novalidate' => 'novalidate'],
]) ?>

<div class="col-sm-12">
    <h3 class="editContent highlight">Попробовать бесплатно</h3>
</div>

<?= $form->field($model, 'username', [
        'template' => "{error}\n{input}\n{label}\n{hint}\n",
        'errorOptions' => ['class' => 'help-block-modal'],
        'inputOptions' => ['placeholder' =>  Yii::t('app', 'Your_name_in_the_system'), 'class' => 'modal-input']
    ]
)->label(false) ?>

<?= $form->field($model, 'phone', [
        'template' => "{error}\n{input}\n{label}\n{hint}\n",
        'errorOptions' => ['class' => 'help-block-modal'],
        'inputOptions' => ['placeholder' =>  'Телефон', 'class' => 'modal-input']
    ]
)->label(false) ?>

<?= $form->field($model, 'email', [
        'template' => "{error}\n{input}\n{label}\n{hint}\n",
        'errorOptions' => ['class' => 'help-block-modal'],
        'inputOptions' => ['placeholder' => Yii::t('app', 'Your_email'), 'class' => 'modal-input']
    ]
)->label(false) ?>

<?= $form->field($model, 'password', [
    'template' => "{error}\n{input}\n{label}\n{hint}\n",
    'errorOptions' => ['class' => 'help-block-modal'],
    'inputOptions' => ['placeholder' => Yii::t('app', 'Your_password'), 'class' => 'modal-input']
])->passwordInput()->label(false) ?>

<?= $form->field($model, 'apply', [
    'template' => "{error}\n{input}\n{label}\n{hint}\n",
    'errorOptions' => ['class' => 'help-block-modal'],
    'inputOptions' => ['class' => 'modal-input']
])->checkbox(['label' => Yii::t('app', 'I_accept') . "&nbsp" . Html::a(Yii::t('app', 'user_Agreement'), '/site-rules')]) ?>

    <div class="modal-footer our-modal-footer">
        <?= Html::submitButton('Консультация', [
            'class' => 'new-modal-button new-but-l new-reg-button',
            'name' => 'login-button',
            'style' => 'float: left;',
            'id' => 'btn-for-consult-form',
            'disabled' => false,
        ]) ?>

        <?= Html::submitButton(Yii::t('app', 'Sign_up'), [
            'class' => 'new-modal-button new-but-l new-reg-button btn-hover',
            'name' => 'login-button',
            'disabled' => false,
        ]) ?>
    </div>

<?php ActiveForm::end(); ?>
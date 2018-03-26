<?php
/**
 * @var yii\web\View $this
 * @var common\models\User $model
 * @var dektrium\user\Module $module
 */

use common\extensions\Counters\Counters;
use common\models\City;
use frontend\extensions\Connect;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('user', 'Sign up');
$this->params['breadcrumbs'][] = $this->title;
if (Yii::$app->session->hasFlash('successOfficeRegister')) {
    $this->registerJs("ga('send', 'event', 'reg-form', 'send'); 
    fbq('track', 'Lead'); 
    yaCounter33738289.reachGoal('OFFICE_REGISTRATION');
    yaCounter33738289.reachGoal('OFFICE_LOGIN');", yii\web\View::POS_LOAD);
}

$link = 'http://' . Yii::$app->params['appFrontend'] . '/ru/site-rules';
?>


<div class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <?= Html::img('http://citylife.info/img/logo-bottom.png', ['alt' => 'CityLife', 'style' => 'float: left;']) ?>
            <a href="https://citylife.info/ru"><b class="citylife-logo">CItyLife</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">

            <?php if (isset($message)) : ?>
                <div class="alert-dismissible alert-info alert fade in"><?= $message ?></div>
            <?php else: ?>
                <p class="login-box-msg"><?= Html::encode($this->title) ?></p>
            <?php endif; ?>


            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'enableAjaxValidation' => true,
                'enableClientValidation' => false,
                'validateOnBlur' => false,
                'validateOnType' => false,
                'validateOnChange' => false,
            ]) ?>

            <?= $form->field($model, 'username', [
                'inputOptions' => [
                    'autofocus' => 'autofocus',
                    'class' => 'form-control',
                    'tabindex' => '1',
                    'type' => 'еуче',
                    'placeholder' => Yii::t('user', 'Username'),
                ],
                'template' => '{input}<span class="glyphicon glyphicon-user form-control-feedback"></span>
                    <div>{error}{hint}</div>',
                'options' => ['class' => 'form-group has-feedback']
            ])->label(false) ?>

            <?= $form->field($model, 'city_id')->widget(Select2::className(), [
                'data' => ArrayHelper::map(Yii::$app->params['cities'][City::ACTIVE], 'id', 'title')
            ])->label(false) ?>

            <?= $form->field($model, 'phone', [
                'inputOptions' => [
                    'class' => 'form-control',
                    'tabindex' => '4',
                    'placeholder' => 'Телефон',
                ],
                'template' => '{input}<span class="glyphicon glyphicon-phone form-control-feedback"></span>
                    <div>{error}{hint}</div>',
                'options' => ['class' => 'form-group has-feedback']
            ])->textInput()->label(false) ?>

            <?= $form->field($model, 'email', [
                'inputOptions' => [
                    'autofocus' => 'autofocus',
                    'class' => 'form-control',
                    'tabindex' => '1',
                    'type' => 'email',
                    'placeholder' => Yii::t('app', 'Email'),
                ],
                'template' => '{input}<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    <div>{error}{hint}</div>',
                'options' => ['class' => 'form-group has-feedback']
            ])->label(false) ?>

            <?= $form->field($model, 'password', [
                'inputOptions' => [
                    'class' => 'form-control',
                    'tabindex' => '2',
                    'placeholder' => Yii::t('user', 'Password'),
                ],
                'template' => '{input}<span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    <div>{error}{hint}</div>',
                'options' => ['class' => 'form-group has-feedback']
            ])->passwordInput()->label(false) ?>

            <?= $form->field($model, 'password2', [
                'inputOptions' => [
                    'class' => 'form-control',
                    'tabindex' => '2',
                    'placeholder' => Yii::t('user', 'Password'),
                ],
                'template' => '{input}<span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    <div>{error}{hint}</div>',
                'options' => ['class' => 'form-group has-feedback']
            ])->passwordInput()->label(false) ?>

            <?= $form->field($model, 'apply', [
                //'template' => "{error}\n{input}\n{label}\n{hint}\n",
                'errorOptions' => ['class' => 'help-block'],
                'inputOptions' => ['class' => 'modal-input']
            ])->checkbox(['label' => Yii::t('app', 'I_accept') . "&nbsp" . Html::a(Yii::t('app', 'user_Agreement'), $link)]) ?>

            <?= Html::submitButton(
                Yii::t('user', 'Sign up'),
                [
                    'class' => 'btn btn-primary btn-block btn-flat',
                    'tabindex' => '3',
                    'style' => 'margin-bottom: 15px;',
                ]
            ) ?>

            <?php ActiveForm::end(); ?>

            <?= Html::a(Yii::t('user', 'Already registered? Sign in!'), ['/user/login'], [
                'class' => 'text-center',
            ]) ?>

        </div>
        <div class="footer-block counter" style="margin-top: 20px;">
            <?= Counters::widget(['app' => 'office']); ?>
        </div>
    </div>
</div>
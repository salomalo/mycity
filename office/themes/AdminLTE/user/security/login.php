<?php
/**
 * @var yii\web\View $this
 * @var dektrium\user\models\LoginForm $model
 * @var dektrium\user\Module $module
 */

use kartik\widgets\Alert;
use common\extensions\Counters\Counters;
use office\extensions\Connect;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Sign_in_office');
$this->params['breadcrumbs'][] = $this->title;

if ($model->firstErrors) {
    echo Alert::widget([
        'type' => Alert::TYPE_DANGER,
        'titleOptions' => ['icon' => 'info-sign'],
        'body' => implode($model->firstErrors)
    ]);
}

$script = <<< JS
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
JS;
//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, yii\web\View::POS_READY);
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
                <p class="login-box-msg">Вход в Офис</p>
            <?php endif; ?>
            <?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'enableAjaxValidation' => true,
                'enableClientValidation' => false,
                'validateOnBlur' => false,
                'validateOnType' => false,
                'validateOnChange' => false,
            ]) ?>

            <?= $form->field($model, 'login', [
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

            <div class="row">
                <div class="col-xs-7">
                    <div class="checkbox icheck">
                        <label class="">
                            <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
                                <input type="checkbox">
                                <ins class="iCheck-helper"></ins></div> Запомнить меня
                        </label>
                    </div>
                </div>

                <div class="col-xs-5">
                    <?= Html::submitButton(
                        Yii::t('user', 'Sign in'),
                        ['class' => 'btn btn-primary btn-block btn-flat', 'tabindex' => '3']
                    ) ?>
                </div>

            </div>

            <?php ActiveForm::end(); ?>



            <div class="social-auth-links text-center">
                <?php if ($social_auth = Connect::widget(['baseAuthUrl' => ['/user/security/auth']])) : ?>
                    <br>
                    <div class="text-center">Авторизация через соцсети</div>

                    <div style="margin: 10px auto; width: 140px;">
                        <?= $social_auth ?>
                    </div>
                <?php endif; ?>

<!--                <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using-->
<!--                    Facebook</a>-->
<!--                <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using-->
<!--                    Google+</a>-->
            </div>

            <?= Html::a(Yii::t('user', 'Forgot password?'), ['/user/recovery/request'], ['tabindex' => '5'])?><br/>
            <?= Html::a(Yii::t('user', 'Sign up'), ['/user/registration/register'], ['class' => 'text-center']) ?>

        </div>
        <div class="footer-block counter" style="margin-top: 20px;">
            <?= Counters::widget(['app' => 'office']); ?>
        </div>
    </div>
</div>
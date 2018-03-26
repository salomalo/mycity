<?php
/**
 * @var $this \yii\web\View
 * @var dektrium\user\models\LoginForm $model
 * @var dektrium\user\Module $module
 */

use yii\helpers\Html;
use dektrium\user\widgets\Connect;
use yii\widgets\ActiveForm;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'enableAjaxValidation' => true,
            'enableClientValidation' => false,
            'validateOnBlur' => false,
            'validateOnType' => false,
            'validateOnChange' => false,
        ]) ?>

        <?= $form->field($model, 'login', ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1']]) ?>

        <?= $form->field($model, 'password', ['inputOptions' => ['class' => 'form-control', 'tabindex' => '2']])->passwordInput()->label(Yii::t('user', 'Password') . ($module->enablePasswordRecovery ? ' (' . Html::a(Yii::t('user', 'Forgot password?'), ['/user/recovery/request'], ['tabindex' => '5']) . ')' : '')) ?>

        <?= Html::submitButton(Yii::t('user', 'Sign in'), ['class' => 'btn btn-success btn-block', 'tabindex' => '3']) ?>

        <?php ActiveForm::end(); ?>

    </div>

    <div style="padding: 0 15px;">
        <?= Html::a(Yii::t('user', 'Sign up'), ['/user/registration/register'], ['class' => 'btn btn-primary btn-block']) ?>

        <?php if ($social_auth = Connect::widget(['baseAuthUrl' => ['/user/security/auth']])) : ?>
            <br>
            <div class="text-center">Авторизация через соцсети</div>

            <div style="margin: 10px auto; width: 140px;">
                <?= $social_auth ?>
            </div>
        <?php endif; ?>
    </div>
</div>
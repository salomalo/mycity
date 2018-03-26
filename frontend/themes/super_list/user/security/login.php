<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use dektrium\user\widgets\Connect;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\LoginForm $model
 * @var dektrium\user\Module $module
 */

$this->title = Yii::t('user', 'Sign in');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="main">
    <div class="main-inner">
        <div class="container">
            <div style="margin-top: 60px">

                <?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

                <div class="modal-dialog our-modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header our-modal-header">
                            <h4><?= Yii::t('app', 'authorization_user')?></h4>
                            <h5><?= Yii::t('app', 'authorization_user_subtitle')?></h5>
                            <div class="text"><?= Yii::t('app', 'authorization_user_text')?></div>
                        </div>
                        <div class="modal-body our-modal-body">
                            <!-- start ActiveForm -->
                            <?= Connect::widget(['baseAuthUrl' => ['/user/security/auth']]);
                            $form = ActiveForm::begin([
                                'id' => 'new-login-form',
                                'enableClientValidation' => 'true',
                                'action' => Url::to(['/user/security/login-ajax', 'redirectUrl' => $redirectUrl]),
                            ]); ?>

                            <?= $form->field($model, 'login', [
                                    'template' => "{error}\n{input}\n{label}\n{hint}\n",
                                    'errorOptions' => ['class' => 'help-block-modal'],
                                    'inputOptions' => ['placeholder' => 'Ваш login...', 'class' => 'modal-input']
                                ]
                            )->label(Yii::t('app', 'Enter_the_email_ which_is_specified_at_registration')) ?>

                            <?= $form->field($model, 'password', [
                                'template' => "{error}\n{input}\n{label}\n{hint}\n",
                                'errorOptions' => ['class' => 'help-block-modal'],
                                'inputOptions' => ['placeholder' => 'Ваш пароль...', 'class' => 'modal-input']
                            ])->passwordInput()->label(Yii::t('app', 'Enter_the_password_specified_at_registration')) ?>

                            <p>
                                <?= Yii::t('app', 'Forgot_your_password?'), ' ', Html::a(Yii::t('app', 'Forgot_your_password'), Url::to('/site/request-password-reset'))?>
                            </p>
                            <div class="modal-footer our-modal-footer">
                                <div class="form-group our-form-group">

                                    <?= Html::submitButton(Yii::t('app', 'Login_to_your_account'), ['class' => 'new-modal-button but-l new-login-button', 'name' => 'login-button']) ?>

                                    <?= Html::a(Yii::t('app', 'Sign_up'),
                                        ['javascript:void(0);'],
                                        ['onclick'=>'signup("' . Url::to('/user/registration/register-ajax') . '");return false;', 'class' => 'modal-button but-r']) ?>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div><!-- /.content -->
    </div><!-- /.main-inner -->
</div>

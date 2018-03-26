<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<!-- modal dialog for display pop up login -->
<div class="modal-dialog our-modal-dialog">
    <div class="modal-content">
        <div class="modal-header our-modal-header">
            <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <!--<h4 class="modal-title our-modal-title" id="myModalLabel"></h4>-->
            <h4><?= Yii::t('app', 'authorization_user')?></h4>
            <h5><?= Yii::t('app', 'authorization_user_subtitle')?></h5>
            <div class="text"><?= Yii::t('app', 'authorization_user_text')?></div>
        </div>
        <div class="modal-body our-modal-body">
            <!-- start ActiveForm -->
            <?php
            $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'enableClientValidation' => 'true',
                        'action' => \Yii::$app->urlManager->createUrl(['site/showmodal-login']),
            ]);
            ?>

            <?=
            $form->field($model, 'login', [
                'template' => "{error}\n{input}\n{label}\n{hint}\n",
                'errorOptions' => ['class' => 'help-block-modal'],
                //'errorOptions' => ['class' => 'help-block alert alert-danger', 'style' => 'display:none;'],
                'inputOptions' => ['placeholder' => 'Ваш email...', 'class' => 'modal-input']
                    ]
            )->label(Yii::t('app', 'Enter_the_email_ which_is_specified_at_registration'))
            ?>

            <?=
            $form->field($model, 'password', [
                'template' => "{error}\n{input}\n{label}\n{hint}\n",
                'errorOptions' => ['class' => 'help-block-modal'],
                //'errorOptions' => ['class' => 'help-block alert alert-danger', 'style' => 'display:none;'],
                'inputOptions' => ['placeholder' => 'Ваш пароль...', 'class' => 'modal-input']
            ])->passwordInput()->label(Yii::t('app', 'Enter_the_password_specified_at_registration'))
            ?>
            
            <?= Html::hiddenInput('redirectUrl', $redirectUrl, [
                'id' => 'redirectUrl'
            ])?>

            <p><?= Yii::t('app', 'Forgot_your_password?')?> 
            <?= Html::a(Yii::t('app', 'Forgot_your_password'), 
                        ['javascript:void(0);'], 
                        ['onclick'=>'reset("'.\Yii::$app->urlManager->createUrl("site/showmodal-password-reset").'");return false;', 'class' => 'password-recovery']); ?>
            </p>
            <div class="modal-footer our-modal-footer">
                <div class="form-group our-form-group">
<?= Html::submitButton(Yii::t('app', 'Login_to_your_account'), ['class' => 'modal-button but-l login-button', 'name' => 'login-button']) ?>
            <?= Html::a(Yii::t('app', 'Sign_up'), 
                ['javascript:void(0);'], 
                ['onclick'=>'signup("'.\Yii::$app->urlManager->createUrl("site/showmodal-signup").'");return false;', 'class' => 'modal-button but-r']) ?>
                </div>
<?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
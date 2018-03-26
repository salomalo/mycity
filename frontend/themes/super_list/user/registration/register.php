<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View              $this
 * @var yii\widgets\ActiveForm    $form
 * @var \common\models\User $model
 */
?>

<div class="modal-dialog our-modal-dialog">
    <div class="modal-content">
        <div class="modal-header our-modal-header">
            <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4><?= Yii::t('app', 'register_user')?></h4>
            <h5><?= Yii::t('app', 'register_user_subtitle')?></h5>
            <div class="text"><?= Yii::t('app', 'register_user_text')?></div>
        </div>
        <div class="modal-body our-modal-body">
            <?php if(\Yii::$app->getSession()->has('signup_error')): ?>
                <?= \Yii::$app->getSession()->get('signup_error'); ?>
                <?php \Yii::$app->getSession()->remove('signup_error'); ?>
            <?php endif;?>
            <?php
            $form = ActiveForm::begin([
                'id' => 'new-form-signup',
                'action' => \Yii::$app->urlManager->createUrl(['user/registration/register-ajax']),
                'options' => ['enctype' => 'multipart/form-data'],
            ]);
            ?>

            <?= $form->field($model, 'username', [
                    'template' => "{error}\n{input}\n{hint}\n",
                    'errorOptions' => ['class' => 'help-block-modal'],
                    'inputOptions' => ['placeholder' =>  Yii::t('app', 'Your_name_in_the_system'), 'class' => 'modal-input']
                ]
            )->label(Yii::t('app', 'Only_latin_digit_direct_nyzhnee_podcherkyvanye'))
            ?>

            <?= $form->field($model, 'phone', [
                    'template' => "{error}\n{input}\n{hint}\n",
                    'errorOptions' => ['class' => 'help-block-modal'],
                    'inputOptions' => ['placeholder' =>  'Ваш телефон', 'class' => 'modal-input']
                ]
            )->label('Телефон')
            ?>

            <?= $form->field($model, 'email', [
                    'template' => "{error}\n{input}\n{label}\n{hint}\n",
                    'errorOptions' => ['class' => 'help-block-modal'],
                    'inputOptions' => ['placeholder' => Yii::t('app', 'Your_email'), 'class' => 'modal-input']
                ]
            )->label(Yii::t('app', 'It_will_be_used_to_login'))
            ?>
            <?= $form->field($model, 'password', [
                'template' => "{error}\n{input}\n{label}\n{hint}\n",
                'errorOptions' => ['class' => 'help-block-modal'],
                'inputOptions' => ['placeholder' => Yii::t('app', 'Your_password'), 'class' => 'modal-input']
            ])->passwordInput()->label(Yii::t('app', 'At_least_{i}_characters_in_the_Latin_alphabet', ['i' => 6]))
            ?>

            <?= $form->field($model, 'password2', [
                'template' => "{error}\n{input}\n{label}\n{hint}\n",
                'errorOptions' => ['class' => 'help-block-modal'],
                'inputOptions' => ['placeholder' => Yii::t('app', 'Your_password'), 'class' => 'modal-input']
            ])->passwordInput()->label(Yii::t('app', 'At_least_{i}_characters_in_the_Latin_alphabet', ['i' => 6]))
            ?>

            <div class="form-group our-form-group check-apply">
                <?= $form->field($model, 'apply', [
                    'template' => "{error}\n{input}\n{label}\n{hint}\n",
                    'errorOptions' => ['class' => 'help-block-modal'],
                    'inputOptions' => ['class' => 'modal-input']
                ])->checkbox([
                    'label' => Yii::t('app', 'I_accept') . "&nbsp" .Html::a(Yii::t('app', 'user_Agreement'), '/site-rules')
                ])
                ?>
            </div>

            <div class="modal-footer our-modal-footer">
                <div class="form-group our-form-group">
                    <?= Html::submitButton(Yii::t('app', 'Sign_up'), [
                        'class' => 'new-modal-button new-but-l new-reg-button',
                        'name' => 'login-button',
                        'disabled' =>false,
                    ]) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
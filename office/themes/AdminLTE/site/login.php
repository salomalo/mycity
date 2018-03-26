<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \common\models\LoginForm $model
 */
$this->title = Yii::t('app', 'Personal_area');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">
            <h1><?= Html::encode($this->title) ?></h1>

            <p><?= Yii::t('app', 'Please_fill_the_fields')?></p>
    
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <?= $form->field($model, 'username')->label(Yii::t('app', 'log')) ?>
                <?= $form->field($model, 'password')->passwordInput()->label(Yii::t('app', 'pass')) ?>
                <?= $form->field($model, 'rememberMe')->checkbox(["label" => Yii::t('app', 'remember_me')]) ?>
                <div style="color:#999;margin:1em 0">
                    <?= Yii::t('app', 'If_forgotten_your_password')?> <?php // Html::a('восстановить', ['site/request-password-reset']) ?>
                    <?= Html::a(Yii::t('app', 'Forgot_your_password'), 
                        ['javascript:void(0);'], 
                        ['onclick'=>'reset("'.\Yii::$app->urlManager->createUrl("/site/showmodal-password-reset").'");return false;', 'class' => 'password-recovery']); ?>.
                </div>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

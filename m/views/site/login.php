<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \common\models\LoginForm $model
 */
$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Пожайлуста заполните поля для входа:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <?= $form->field($model, 'username')->label("Логин") ?>
                <?= $form->field($model, 'password')->passwordInput()->label("Пароль") ?>
                <?= $form->field($model, 'rememberMe')->checkbox(["label"=>"Запомнить меня"]) ?>
                <div style="color:#999;margin:1em 0">
                    Если вы забыли свой пароль <?= Html::a('восстановить', ['site/request-password-reset']) ?>.
                </div>
                <div class="form-group">
                    <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

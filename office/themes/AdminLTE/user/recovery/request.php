<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use office\extensions\Counters\Counters;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/*
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\RecoveryForm $model
 */

$this->title = Yii::t('user', 'Recover your password');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <?= Html::img('http://citylife.info/img/logo-bottom.png', ['alt' => 'CityLife', 'style' => 'float: left;']) ?>
            <a href="https://citylife.info/ru"><b class="citylife-logo">CItyLife</b></a>
        </div>
        <div class="login-box-body">
            <p class="login-box-msg">Востановление пароля</p>

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'enableAjaxValidation' => true,
                'enableClientValidation' => false,
                'validateOnBlur' => false,
                'validateOnType' => false,
                'validateOnChange' => false,
            ]) ?>

            <?= $form->field($model, 'email', [
                'inputOptions' => [
                    'autofocus' => 'autofocus',
                    'class' => 'form-control',
                    'tabindex' => '1',
                    'type' => 'email',
                    'placeholder' => Yii::t('app', 'Email'),
                ],
                'template' => '{input}<span class="glyphicon glyphicon-envelope form-control-feedback"></span>',
                'options' => ['class' => 'form-group has-feedback']
            ])->label(false) ?>


                    <?= Html::submitButton(
                        Yii::t('user', 'Continue'),
                        [
                            'class' => 'btn btn-primary btn-block btn-flat',
                            'tabindex' => '3',
                            'style' => 'margin-bottom: 15px'
                        ]
                    ) ?>


            <?php ActiveForm::end(); ?>

            <?= Html::a('Авторизироваться', ['/user/login'], ['class' => 'text-center']) ?>

        </div>

        <div class="footer-block counter" style="margin-top: 20px;">
            <?= Counters::widget(); ?>
        </div>
    </div>
</div>

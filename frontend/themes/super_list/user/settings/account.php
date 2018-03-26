<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this  yii\web\View
 * @var $form  yii\widgets\ActiveForm
 * @var $model dektrium\user\models\SettingsForm
 */

$this->title = Yii::t('user', 'Account settings');
$this->params['breadcrumbs'][] = $this->title;
$action = Yii::$app->controller->action->id;

$networksVisible = count(Yii::$app->authClientCollection->clients) > 0;
?>
<div class="main">
    <div class="main-inner">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-lg-9">
                    <div id="primary">
                        <div class="document-title">
                            <ol class="breadcrumb">
                                <li>
                                    <a href="/ru">Главная</a>
                                </li>
                            </ol>
                            <h1><?= Yii::t('user', 'Account') ?></h1>
                        </div><!-- /.document-title -->
                    </div>
                    <?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

                    <div class="row">
                        <div class="col-md-9">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <?= Html::encode($this->title) ?>
                                </div>
                                <div class="panel-body">
                                    <?php $form = ActiveForm::begin([
                                        'id' => 'account-form',
                                        'options' => ['class' => 'form-horizontal'],
                                        'fieldConfig' => [
                                            'template' => "{label}\n<div class=\"col-lg-7\">{input}</div>\n<div class=\"col-sm-offset-2 col-lg-7\">{error}\n{hint}</div>",
                                            'labelOptions' => ['class' => 'col-lg-3 control-label'],
                                        ],
                                        'enableAjaxValidation' => true,
                                        'enableClientValidation' => false,
                                    ]); ?>

                                    <?php // $form->field($model, 'email') ?>

                                    <?= $form->field($model, 'username')->hiddenInput()->label('') ?>

                                    <?= $form->field($model, 'current_password')->passwordInput() ?>

                                    <?= $form->field($model, 'new_password')->passwordInput() ?>

                                    <div class="form-group">
                                        <div class="col-lg-offset-2 col-lg-7">
                                            <?= Html::submitButton(Yii::t('app', 'Confirm password'), ['class' => 'btn btn-block btn-primary']) ?>
                                            <br>
                                        </div>
                                    </div>

                                    <?php ActiveForm::end(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4 col-lg-3">
                    <div class="sidebar sidebar_dashboard">
                        <div id="nav_menu-7" class="widget widget_nav_menu"><h2 class="widgettitle">Меню</h2>
                            <div class="menu-dashboard-menu-container">
                                <ul id="menu-dashboard-menu" class="menu">
                                    <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-3445">
                                        <?= Html::a('<i class="fa fa-fw fa-list"></i>' . Yii::t('app', 'Options'), ['/user/profile/index']); ?>
                                    <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-3446">
                                        <?= Html::a('<i class="fa fa-fw fa-user"></i>' . Yii::t('user', 'Account'), ['/user/settings/account']); ?>
                                    <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-3447">
                                        <?= Html::a('<i class="fa fa-fw fa-certificate"></i>' . Yii::t('user', 'Profile'), ['/user/settings/profile']); ?>
                                    </li>
                                    <?php if ($networksVisible) : ?>
                                        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-3448">
                                            <?= Html::a('<i class="fa fa-fw fa-eye"></i>' . Yii::t('user', 'Networks'), ['/user/settings/networks']); ?>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div><!-- /.sidebar -->
                </div>
            </div>
        </div>
    </div>
</div>

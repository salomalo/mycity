<?php
/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\Profile $model
 */

use common\extensions\MultiSelect\MultiSelect;
use common\models\City;
use common\models\Profile;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('user', 'Profile settings');
$this->params['breadcrumbs'][] = $this->title;

$networksVisible = count(Yii::$app->authClientCollection->clients) > 0;
$action = Yii::$app->controller->action->id;

$this->registerJsFile('../js/new/bootstrap-select.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<div class="main">
    <div class="main-inner">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-lg-9">
                    <div id="primary">
                        <div class="document-title">
                            <ol class="breadcrumb">
                                <li><a href="/ru">Главная</a></li>
                            </ol>
                            <h1><?= Yii::t('user', 'Networks') ?></h1>
                        </div>
                    </div>

                    <?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

                    <div class="row">
                        <div class="col-md-10">
                            <div class="panel panel-default" style="margin-top: 5px;">
                                <div class="panel-heading">
                                    <?= Html::encode($this->title) ?>
                                </div>
                                <div class="panel-body">
                                    <?php $form = ActiveForm::begin([
                                        'id' => 'profile-form',
                                        'options' => ['class' => 'form-horizontal'],
                                        'fieldConfig' => [
                                            'template' => "{label}\n<div class=\"col-lg-7\">{input}</div>\n<div class=\"col-sm-offset-2 col-lg-7\">{error}\n{hint}</div>",
                                            'labelOptions' => ['class' => 'col-lg-2 control-label'],
                                        ],
                                        'enableAjaxValidation' => true,
                                        'enableClientValidation' => false,
                                        'validateOnBlur' => false,
                                    ]); ?>

                                    <?= $form->field($model, 'name') ?>

                                    <?= $form->field($model, 'public_email')->label('E-mail') ?>

                                    <?= $form->field($model, 'gender')->widget(Select2::className(), [
                                        'data' => Profile::getGenderLabels(),
                                        'options' => ['id' => 'gender', 'multiple' => false],
                                        'pluginOptions' => ['allowClear' => true]
                                    ]) ?>

                                    <?= $form->field($model, 'country_id')->widget(Select2::className(), [
                                        'data' => Profile::getCountries(),
                                        'options' => ['id' => 'country_id', 'multiple' => false],
                                        'pluginOptions' => ['allowClear' => true]
                                    ]) ?>

                                    <?= $form->field($model, 'birth_city_id')->widget(MultiSelect::className(), [
                                        'options' => ['placeholder' => ''],
                                        'url' => '/business/city-list',
                                        'className' => City::className(),
                                        'multiple' => false,
                                    ]); ?>

                                    <?= $form->field($model, 'birth_date')->widget(DatePicker::className(), [
                                        'convertFormat' => true,
                                        'pluginOptions' => ['allowClear' => true, 'format' => 'yyyy-MM-dd', 'autoclose' => true]
                                    ]) ?>

                                    <?= $form->field($model, 'phone')->textInput() ?>

                                    <?= $form->field($model, 'bio')->textarea() ?>

                                    <div class="form-group row">
                                        <div class=" col-lg-offset-2  col-lg-3">
                                            <?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-block btn-primary']) ?>
                                            <br>
                                        </div>
                                        <div class="col-lg-offset-1 col-lg-3">
                                            <?= Html::a(Yii::t('app', 'Change password'), ["settings/account"], ['class' => 'btn btn-block btn-default']) ?>
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
                                    <li class="menu-item menu-item-type-post_type menu-item-object-page">
                                        <?= Html::a('<i class="fa fa-fw fa-list"></i>' . Yii::t('app', 'Options'), ['/user/profile/index']); ?>
                                    </li>
                                    <li class="menu-item menu-item-type-post_type menu-item-object-page">
                                        <?= Html::a('<i class="fa fa-fw fa-user"></i>' . Yii::t('user', 'Account'), ['/user/settings/account']); ?>
                                    </li>
                                    <li class="menu-item menu-item-type-post_type menu-item-object-page">
                                        <?= Html::a('<i class="fa fa-fw fa-certificate"></i>' . Yii::t('user', 'Profile'), ['/user/settings/profile']); ?>
                                    </li>
                                    <?php if ($networksVisible) : ?>
                                        <li class="menu-item menu-item-type-post_type menu-item-object-page">
                                            <?= Html::a('<i class="fa fa-fw fa-eye"></i>' . Yii::t('user', 'Networks'), ['/user/settings/networks']); ?>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
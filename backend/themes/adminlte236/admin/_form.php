<?php
/**
 * @var $this \yii\web\View
 * @var $model Admin|yii\base\Model
 */
use backend\models\Admin;
use common\models\City;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
?>

<div class="account-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'form-with-disabling-submit']]); ?>
    
    <?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'password_hash',['enableClientValidation' => false])->passwordInput(['maxlength' => 255,'value'=>'']) ?>

    <?php if (Yii::$app->user->identity->level == Admin::LEVEL_SUPER_ADMIN): ?>
        <?= $form->field($model, 'level')->textInput() ?>
    <?php endif?>

    <?= $form->field($model, 'cities_id')->widget(Select2::className(), [
        'data' => City::getAll(['id' => Yii::$app->params['activeCitysBackend']]),
        'model' => $model,
        'attribute' => 'cities_id',
        'options' => ['multiple' => true, 'placeholder' => 'Выберите города'],
        'pluginOptions' => ['allowClear' => true],
    ])?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

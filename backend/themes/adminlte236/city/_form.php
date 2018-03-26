<?php

use common\extensions\CustomCKEditor\CustomCKEditor;
use common\models\City;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use common\models\Region;
use common\extensions\mihaildev\ckeditor\CKEditor as CKEditor2;
use common\extensions\mihaildev\elfinder\ElFinder as ElFinder2;

/* @var $this yii\web\View */
/* @var $model common\models\City */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="city-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-with-disabling-submit']]); ?>

    <?= $form->field($model, 'idRegion')->widget(Select2::className(), [
        'data' => Region::getAll(),
        'options' => ['placeholder' => 'Select a region ...'],
        'pluginOptions' => ['allowClear' => true],
    ]) ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'title_ge')->textInput() ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => 7]) ?>

    <?= $form->field($model, 'subdomain')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'main')->widget(Select2::className(), [
        'data' => City::$status,
        'options' => ['placeholder' => 'Выберите состояние', 'multiple' => false,],
        'pluginOptions' => ['allowClear' => false]
    ]) ?>
    
    <p>___</p>
    <p>Раздел "О Городе"</p>
    <p>___</p>
    
    <?= $form->field($model, 'titleAbout')->textInput() ?>
    
    <?= $form->field($model, 'about')->widget(CustomCKEditor::className()) ?>

    <?= $form->field($model, 'google_analytic')->textInput() ?>

    <?= $form->field($model, 'vk_public_id')->textInput() ?>
    <?= $form->field($model, 'vk_public_admin_id')->textInput() ?>
    <?= $form->field($model, 'vk_public_admin_token')->textInput() ?>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-default collapsed-box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">SEO</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <?= $form->field($model, 'seo_title')->textInput() ?>
                    <?= $form->field($model, 'seo_description')->textarea() ?>
                    <?= $form->field($model, 'seo_keywords')->textInput() ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use common\models\Sitemap;

/* @var $this yii\web\View */
/* @var $model common\models\WorkCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="work-category-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-with-disabling-submit']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

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
                    <?= $form->field($model, 'seo_title') ?>
                    <?= $form->field($model, 'seo_description') ?>
                    <?= $form->field($model, 'seo_keywords') ?>
                    <?= $form->field($model, 'seo_title_resume') ?>
                    <?= $form->field($model, 'seo_description_resume') ?>
                    <?= $form->field($model, 'seo_keywords_resume') ?>
                    <?= $form->field($model, 'sitemap_en')->checkbox() ?>
                    <?= $form->field($model, 'sitemap_priority')->widget(Select2::className(),[
                        'data' => Sitemap::$priority,
                        'options' => ['placeholder' => 'Выберите приоритет'],
                        'pluginOptions' => ['allowClear' => true],
                    ]) ?>
                    <?= $form->field($model, 'sitemap_changefreq')->widget(Select2::className(),[
                        'data' => Sitemap::$changefreq,
                        'options' => ['placeholder' => 'Выберите частоту изменения'],
                        'pluginOptions' => ['allowClear' => true],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

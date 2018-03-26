<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\elfinder\InputFile;
use kartik\widgets\Select2;
use common\models\Sitemap;

/* @var $this yii\web\View */
/* @var $model common\models\PostCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-category-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'form-with-disabling-submit']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'url') ?>
    
    <?php if ($model->image) : ?>
        <img src="<?= Yii::$app->files->getUrl($model, 'image', 100)?>" > 
        <a href="<?= Yii::$app->urlManager->createUrl(['post-category/update', 'id' => $model->id, 'actions'=>'deleteImg'])?>" title="Delete" data-confirm="Are you sure you want to delete this item?" ><span class="glyphicon glyphicon-trash"></span></a>
    <?php endif; ?>
    
    <?= $form->field($model, 'image')->fileInput(); ?>

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

                    <?= $form->field($model, 'sitemap_en')->checkbox() ?>
                    <?= $form->field($model, 'sitemap_priority')->widget(Select2::className(), [
                        'data' => Sitemap::$priority,
                        'options' => ['placeholder' => 'Выберите приоритет'],
                        'pluginOptions' => ['allowClear' => true]
                    ]) ?>
                    <?= $form->field($model, 'sitemap_changefreq')->widget(Select2::className(), [
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

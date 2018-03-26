<?php

use common\extensions\CustomCKEditor\CustomCKEditor;
use common\models\KinoGenre;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use common\models\Afisha;
use common\models\AfishaCategory;
use common\extensions\mihaildev\ckeditor\CKEditor as CKEditor2;
use common\extensions\mihaildev\elfinder\ElFinder as ElFinder2;
use common\models\Sitemap;
use common\models\Tag;

/* @var $this yii\web\View */
/* @var $model common\models\Afisha */
/* @var $form yii\widgets\ActiveForm */
/* @var $dislpayCheck boolean */
?>

<div class="afisha-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'form-with-disabling-submit']]); ?>
    
    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>

    <?= $dislpayCheck ? $form->field($model, 'isChecked')->checkbox() : ''?>
    
    <?= $form->field($model, 'idCategory')->widget(Select2::className(),[
        'data' => ArrayHelper::map(AfishaCategory::find()->where(['isFilm' => 1])->all(),'id','title'),
        'options' => [
            'placeholder' => 'Select a category   ...',
            'id' => 'idCategory',
            'multiple' => false,
        ],
        'pluginOptions' => ['allowClear' => true]
    ]);?>
    
    <?= $form->field($model, 'genre')->widget(Select2::className(),[
        'data' => KinoGenre::getAll(),
        'options' => [
            'placeholder' => 'Select a genre ...',
            'id' => 'genre',
            'multiple' => true,
        ],
        'pluginOptions' => ['allowClear' => true],
    ]);?>
    
    <?= $form->field($model, 'year')->widget(Select2::className(),[
        'data' => Afisha::yearList(),
        'options' => [
            'placeholder' => 'Select a year ...',
            'id' => 'year',
            'multiple' => false,
        ],
        'pluginOptions' => ['allowClear' => true]
    ]);?>
    
    <?= $form->field($model, 'country')->textInput() ?>
    
    <?= $form->field($model, 'director')->textInput() ?>
    
    <?= $form->field($model, 'actors')->textInput() ?>
    
    <?= $form->field($model, 'budget')->textInput() ?>
    
    <?php if($model->image):?>
        <img src="<?= Yii::$app->files->getUrl($model, 'image', 70)?>" > 
        <a href="<?= Url::to(['afisha/update', 'id' => $model->id, 'actions'=>'deleteImg'])?>" title="Delete" data-confirm="Are you sure you want to delete this item?" ><span class="glyphicon glyphicon-trash"></span></a>
    <?php endif;?>

    <?= $form->field($model, 'image')->fileInput() ?>
    
    <?= $form->field($model, 'trailer')->textInput() ?>
   
    <?= $form->field($model, 'description')->widget(CustomCKEditor::className()); ?>
    
    <?= $form->field($model, 'fullText')->widget(CustomCKEditor::className()); ?>
    
    <?= $form->field($model, 'tags')->widget(Select2::className(),[
        'data' => ArrayHelper::map(Tag::find()->all(),'title','title'),
        'options' => [
            'placeholder' => 'Select a tags ...',
            'id' => 'tags',
            'multiple' => true ,
        ],
        'pluginOptions' => [
            'tags' => true,
            'maximumInputLength' => Yii::$app->params['maximumTagsLength'],
        ]
    ]);?>
    
    <?= Html::hiddenInput('Afisha[idsCompany]', 0) ?>
    <?= Html::hiddenInput('Afisha[isFilm]', 1) ?>

    <?= $form->field($model, 'rating')->textInput() ?>
    
    <?= $form->field($model, 'order') ?>

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

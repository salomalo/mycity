<?php

use office\extensions\LanguageWidget\LanguageWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use common\models\Afisha;
use common\models\AfishaCategory;
use common\extensions\mihaildev\ckeditor\CKEditor as CKEditor2;
use common\extensions\mihaildev\elfinder\ElFinder as ElFinder2;
use common\models\Sitemap;

/* @var $this yii\web\View */
/* @var $model common\models\Afisha */
/* @var $form yii\widgets\ActiveForm */
/* @var $idCompany integer */
?>

<div class="afisha-form">
    <?= LanguageWidget::widget() ?>

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>
    
    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
    <p>SEO</p>
    <?= $form->field($model, 'seo_title') ?>
    <?= $form->field($model, 'seo_description') ?>
    <?= $form->field($model, 'seo_keywords') ?>
    <?= $form->field($model, 'sitemap_en')->checkbox() ?>
    <?= $form->field($model, 'sitemap_priority')->widget(Select2::className(),[
        'data' => Sitemap::$priority,
        'options' => [
            'placeholder' => 'Select a priority   ...',
            'id' => 'itemap_priority',
            'multiple' => false ,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]);?>
    <?= $form->field($model, 'sitemap_changefreq')->widget(Select2::className(),[
        'data' => Sitemap::$changefreq,
        'options' => [
            'placeholder' => 'Select a частоту изменения ...',
            'id' => 'sitemap_changefreq',
            'multiple' => false ,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]);?>
    <p>___</p>    
    <?= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>
    
    <?= $form->field($model, 'idCategory')->widget(Select2::className(),[
        'data' => ArrayHelper::map(AfishaCategory::find()->where(['isFilm' => 1])->all(),'id','title'),
        'options' => [
            'placeholder' => 'Select a category   ...',
            'id' => 'idCategory',
            'multiple' => false,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]);?>
    
    <?= $form->field($model, 'genre')->widget(Select2::className(),[
        'data' => ArrayHelper::map(\common\models\KinoGenre::find()->all(),'id','title'),
        'options' => [
            'placeholder' => 'Select a genre ...',
            'id' => 'genre',
            'multiple' => true,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]);?>
    
    <?= $form->field($model, 'year')->widget(Select2::className(),[
        'data' => Afisha::yearList(),
        'options' => [
            'placeholder' => 'Select a year ...',
            'id' => 'year',
            'multiple' => false,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]);?>
    
   <?= $form->field($model, 'country')->textInput() ?>
    
   <?= $form->field($model, 'director')->textInput() ?>
    
   <?= $form->field($model, 'actors')->textInput() ?>
    
   <?= $form->field($model, 'budget')->textInput() ?>

    <?php if ($idCompany) : ?>
        <?= $form->field($model, 'idsCompany')->hiddenInput(['value'=> $idCompany])->label(false) ?>
    <?php endif; ?>
    
    <?php if($model->image):?>
    <img src="<?=\Yii::$app->files->getUrl($model, 'image', 70)?>" > 
    <a href="<?=\Yii::$app->urlManager->createUrl(['afisha/update', 'id' => $model->id, 'actions'=>'deleteImg'])?>" title="Delete" data-confirm="Are you sure you want to delete this item?" ><span class="glyphicon glyphicon-trash"></span></a>
    <?php endif;?>

    <?= $form->field($model, 'image')->fileInput() ?>
    
    <?= $form->field($model, 'trailer')->textInput() ?>
   
    <?=
    $form->field($model, 'description')->widget(CKEditor2::className(), [
        'editorOptions' => ElFinder2::ckeditorOptions('elfinder2', []),
    ]);
    ?>
    
    <?=
    $form->field($model, 'fullText')->widget(CKEditor2::className(), [
        'editorOptions' => ElFinder2::ckeditorOptions('elfinder2', []),
    ]);
    ?>

    <?= $form->field($model, 'rating')->textInput() ?>

    <?= Html::hiddenInput('Afisha[isFilm]', 1) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

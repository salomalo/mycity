<?php

use common\extensions\CustomCKEditor\CustomCKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\WorkCategory;
use common\models\User;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use common\extensions\MultiSelect\MultiSelect;
use common\models\Sitemap;

/* @var $this yii\web\View */
/* @var $model common\models\WorkResume */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="work-resume-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'form-with-disabling-submit']]); ?>
    
    <?= $form->field($model, 'idCategory')->widget(Select2::className(),[
        'data' => ArrayHelper::map(WorkCategory::find()->all(),'id','title'),
        'options' => ['placeholder' => 'Select a category ...'],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>

    <?= $form->field($model, 'idUser')->widget(Select2::className(),[
        'data' => ArrayHelper::map(User::find()->all(),'id','username'),
        'options' => ['placeholder' => 'Select a user ...'],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>

    <?= $form->field($model, 'idCity')->widget(Select2::className(), [
        'data' => ArrayHelper::merge(
            Yii::$app->params['adminCities']['select'],
            (($model->idCity and $model->city and !in_array($model->idCity, Yii::$app->params['adminCities']['id'])) ? [$model->idCity => $model->city->title] : [])
        ),
        'pluginOptions' => ['allowClear' => true],
        'options' => ['placeholder' => 'Выберите город'],
    ]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>
    
    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
    
    <?php if($model->photoUrl):?>
  <!--  <img src="https://s3-eu-west-1.amazonaws.com/files1q/account/53bbc6e4be5f2.jpg_100.jpg" > -->
    <img src="<?=\Yii::$app->files->getUrl($model, 'photoUrl', 90)?>" > 
    <a href="<?=\Yii::$app->urlManager->createUrl(['work-resume/update', 'id' => $model->id, 'actions'=>'deleteImg'])?>" title="Delete" data-confirm="Are you sure you want to delete this item?" ><span class="glyphicon glyphicon-trash"></span></a>
    
    <?php endif;?>
    
    <?= $form->field($model, 'photoUrl')->fileInput(); ?>

    <?php //echo $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    
    <?= $form->field($model, 'description')->widget(CustomCKEditor::className()); ?>

    <?= $form->field($model, 'experience')->textInput(['maxlength' => 255]) ?>
    
    <?= $form->field($model, 'education')->dropDownList($model->educationList, ['prompt'=>'']) ?>
    
    <?= $form->field($model, 'male')->checkbox() ?>

    <?= $form->field($model, 'isFullDay')->checkbox() ?>

    <?= $form->field($model, 'isOffice')->checkbox() ?>

    <?php //echo $form->field($model, 'dateCreate')->textInput() ?>

    <?= $form->field($model, 'year')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'salary')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'skype')->textInput(['maxlength' => 255]) ?>

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

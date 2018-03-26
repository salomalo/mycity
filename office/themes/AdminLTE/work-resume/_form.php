<?php

use common\extensions\CustomCKEditor\CustomCKEditor;
use office\extensions\LanguageWidget\LanguageWidget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use common\models\WorkCategory;
use common\models\User;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use common\extensions\MultiSelect\MultiSelect;

/* @var $this yii\web\View */
/* @var $model common\models\WorkResume */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="work-resume-form">
    <?= LanguageWidget::widget() ?>

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>

    <?php //echo $form->field($model, 'idCategory')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'idCategory')->widget(Select2::className(),[
        'data' => ArrayHelper::map(WorkCategory::find()->all(),'id','title'),
        'options' => [
            'placeholder' => 'Выберите категорию ...',
            'multiple' => false,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]); ?>

    <?php
//    $form->field($model, 'idCity')->widget(Select2::className(), [
//        'data' => ArrayHelper::map(City::find()->all(), 'id', 'title'),
//        'options' => [
//            'placeholder' => 'Выберите город ...',
//            'id' => 'idCity',
//            'multiple' => false,
//        ],
//        'pluginOptions' => [
//            'allowClear' => true,
//        ]
//    ])->label("Город");
    ?>
    
    <?= $form->field($model, 'idCity')->widget(MultiSelect::className(), [
        'url'=>'/business/city-list',
        'className' => 'common\models\City',
        'multiple' => false,
        'options'=>[
            'placeholder' =>'Выберите город ...',
        ]
    ]);
    ?>
    
    <?php if($model->photoUrl):?>
  <!--  <img src="https://s3-eu-west-1.amazonaws.com/files1q/account/53bbc6e4be5f2.jpg_100.jpg" > -->
    <img src="<?=\Yii::$app->files->getUrl($model, 'photoUrl', 100)?>" > 
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
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    
    <?= $form->field($model, 'idUser')->hiddenInput([
            'value'=>$model->isNewRecord ? Yii::$app->user->id : $model->idUser
        ])->label('')?>

    <?php ActiveForm::end(); ?>

</div>

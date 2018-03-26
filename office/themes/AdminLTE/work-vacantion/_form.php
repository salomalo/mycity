<?php

use common\extensions\CustomCKEditor\CustomCKEditor;
use common\models\Business;
use common\models\City;
use kartik\widgets\DepDrop;
use office\extensions\LanguageWidget\LanguageWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\WorkCategory;
use common\extensions\MultiSelect\MultiSelect;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;

/* @var $this yii\web\View */
/* @var $model common\models\WorkVacantion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="work-vacantion-form">
    <?= LanguageWidget::widget() ?>

    <?php $form = ActiveForm::begin(); ?>

    <?php //echo $form->field($model, 'idCategory')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]); ?>

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

    <?= $form->field($model, 'idCompany')->widget(Select2::className(),[
        'data' => $model::getCompanyList(Yii::$app->user->id),
        'disabled' => true,
        'options' => [
            'readonly' => true,
            'placeholder' => 'Выберите предприятие...',
            'id' => 'idCompany',
            'multiple' => false,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ])->hiddenInput()->label(false);?>
    
    <?php
//    $form->field($model, 'idCompany')->widget(Select2::className(),[
//        'data' => ArrayHelper::map(Business::find()->where(['idUser'=>Yii::$app->user->id])->all(),'id','title'),
//        'options' => [
//            'placeholder' => 'Select a company ...',
//            'multiple' => false,
//        ],
//        'pluginOptions' => [
//            'allowClear' => true,
//        ]
//    ]); 
    ?>

      <?php
//      $form->field($model, 'idCity')->widget(Select2::className(), [
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

    <?php $cities = City::find()->select(['id', 'title'])->where(['main' => City::ACTIVE])->all(); ?>
    <?= $form->field($model, 'idCity')->widget(DepDrop::className(), [
        'data' => ArrayHelper::map($cities, 'id', 'title'),
        'options' => [
            'placeholder' => Yii::t('app', 'Select_city'),
            'id' => 'idCity',
            'multiple' => false,
            'width' => '300px',
        ],
        'pluginOptions' => [
            'allowClear' => false,
            'url' => '/business/city-by-business',
            'depends' => ['idCompany'],
            'placeholder' => Yii::t('app', 'Select_city'),
        ]
    ])->label('Город'); ?>

    <?php //echo $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    
    <?= $form->field($model, 'description')->widget(CustomCKEditor::className()); ?>

    <?php //echo $form->field($model, 'proposition')->textarea(['rows' => 6]) ?>
    
    <?= $form->field($model, 'proposition')->widget(CKEditor::className(),[
    'editorOptions' => ElFinder::ckeditorOptions('elfinder', [
        'fontSize_defaultLabel' => '14px',
        'font_defaultLabel' => 'Arial',
    ]),
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]); ?>

    <?= $form->field($model, 'experience')->textInput(['maxlength' => 255]); ?>
    
    <?= $form->field($model, 'salary')->textInput(['maxlength' => 255]); ?>

    <?= $form->field($model, 'education')->widget(Select2::className(),[
        'data' => \common\models\WorkVacantion::$educations,
        'options' => [
            'placeholder' => 'Выберите образование...',
            'id' => 'education',
            'multiple' => false,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]);?>

    <div class="row">
        <div class="col-sm-2">
            <?= $form->field($model, 'male')->radio(['label' => 'Женский пол', 'value' => 0, 'uncheck' => null]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'male')->radio(['label' => 'Мужской пол', 'value' => 1, 'uncheck' => null]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2">
            <?= $form->field($model, 'isFullDay')->radio(['label' => 'Полный день', 'value' => 0, 'uncheck' => null]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'isFullDay')->radio(['label' => 'Частичная занятость', 'value' => 1, 'uncheck' => null]) ?>
        </div>
    </div>

    <?= $form->field($model, 'isOffice')->checkbox() ?>

    <?php //echo $form->field($model, 'dateCreate')->textInput() ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'skype')->textInput(['maxlength' => 255]) ?>


    <?= $form->field($model, 'minYears')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'maxYears')->textInput(['maxlength' => 255]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    
    <?= $form->field($model, 'idUser')->hiddenInput([
            'value'=>$model->isNewRecord ? Yii::$app->user->id : $model->idUser
        ])->label('')?>

    <?php ActiveForm::end(); ?>

</div>

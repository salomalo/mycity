<?php

use common\extensions\CustomCKEditor\CustomCKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\City;
use kartik\widgets\Select2;
use common\models\Wall;
use common\extensions\mihaildev\ckeditor\CKEditor as CKEditor2;
use common\extensions\mihaildev\elfinder\ElFinder as ElFinder2;

/* @var $this yii\web\View */
/* @var $model common\models\Wall */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wall-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-with-disabling-submit']]); ?>

    <?= $form->field($model, 'pid')->textInput() ?>

    <?= $form->field($model, 'type')->widget(Select2::className(),[
        'data' => Wall::$types,
        'options' => [
            'placeholder' => 'Select a type ...',
            'id' => 'type',
        ],
        'pluginOptions' => ['allowClear' => true],
    ]);?>
    
    <?= $form->field($model, 'idCity')->widget(Select2::className(),[
        'data' => ArrayHelper::merge(
            Yii::$app->params['adminCities']['select'],
            (($model->idCity and $model->city and !in_array($model->idCity, Yii::$app->params['adminCities']['id'])) ? [$model->idCity => $model->city->title] : [])
        ),
        'options' => ['placeholder' => 'Select a city ...'],
        'pluginOptions' => ['allowClear' => true]
    ]);?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?=$form->field($model, 'description')->widget(CustomCKEditor::className());
    ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dateCreate')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
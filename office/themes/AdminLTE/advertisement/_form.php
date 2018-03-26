<?php

use common\models\City;
use kartik\widgets\Select2;
use office\extensions\MyDatePicker\MyDatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Advertisement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="advertisement-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php if (!empty($model->image)) : ?>
        <?= Html::img(Yii::$app->files->getUrl($model, 'image'), ['style' => 'max-height: 200px']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-trash" style="font-size: 23px; color: #ee011f; margin-left: 20px;"></span>',
            ['advertisement/del-img', 'id' => $model->id],
            ['title' => 'Delete', 'data-confirm' => 'Are you sure you want to delete this item?']
        ) ?>
    <?php endif; ?>

    <?= $form->field($model, 'image')->fileInput() ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true, 'placeholder' => 'http:://site.com']) ?>

    <?= $form->field($model, 'city_id')->widget(Select2::className(),[
        'data' => City::getAll(['main' => City::ACTIVE]),
        'options' => ['placeholder' => 'Выберите город', 'id' => 'city_id'],
        'pluginOptions' => ['allowClear' => false],
    ]); ?>

    <?= MyDatePicker::widget([
        'model' => $model,
        'fields' => [
            'date_start' => 'Выберите начало',
            'date_end' => 'Выберите окончание',
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('advertisement', 'Save'), ['btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
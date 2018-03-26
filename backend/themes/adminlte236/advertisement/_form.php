<?php

use backend\extensions\MyDatePicker\MyDatePicker;
use common\models\City;
use common\models\User;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Advertisement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="advertisement-form">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-with-disabling-submit']]); ?>

    <?= $form->field($model, 'user_id')->widget(Select2::className(),[
        'data' => User::getAll(),
        'options' => ['placeholder' => 'Выберите пользователя', 'id' => 'user_id'],
        'pluginOptions' => ['allowClear' => false],
    ]); ?>

    <?= $form->field($model, 'city_id')->widget(Select2::className(),[
        'data' => ArrayHelper::merge(
            Yii::$app->params['adminCities']['select'],
            (($model->city_id and $model->city and !in_array($model->city_id, Yii::$app->params['adminCities']['id'])) ? [$model->city_id => $model->city->title] : [])
        ),
        'options' => ['placeholder' => 'Выберите город', 'id' => 'city_id'],
        'pluginOptions' => ['allowClear' => false],
    ]); ?>

    <?= $form->field($model, 'position')->widget(Select2::className(),[
        'data' => $model::$positions,
        'options' => ['placeholder' => 'Выберите позицию', 'id' => 'position'],
        'pluginOptions' => ['allowClear' => false],
    ]); ?>

    <?= $form->field($model, 'status')->widget(Select2::className(),[
        'data' => $model::$statuses,
        'options' => ['placeholder' => 'Выберите статус', 'id' => 'status'],
        'pluginOptions' => ['allowClear' => false],
    ]); ?>

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

    <?= MyDatePicker::widget([
        'model' => $model,
        'fields' => [
            'date_start' => 'Выберите начало',
            'date_end' => 'Выберите окончание',
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

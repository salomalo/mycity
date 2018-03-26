<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use common\models\Afisha;
use common\models\Business;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\ScheduleKino */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="schedule-kino-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idAfisha')->widget(Select2::className(),[
        'data' => ArrayHelper::map(Afisha::find()->where(['isFilm' => 1])->all(),'id','title'),
        'options' => [
            'placeholder' => 'Select a afisha   ...',
            'id' => 'idAfisha',
            'multiple' => false,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]); ?>
    <?php //  $form->field($model, 'idCompany')->textInput() ?>
    <?= $form->field($model, 'idCompany')->widget(Select2::className(),[
        'data' => ArrayHelper::map(Business::find()->where(['idUser'=>Yii::$app->user->identity->id, 'type' => Business::TYPE_KINOTHEATER])->all(),'id','title'),
        'options' => [
            'placeholder' => 'Выберите предприятие ...',
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ])->label("Предприятие"); ?>
    <?php 
//       $form->field($model, 'idCompany')->widget(Select2::className(),[
//        'data' => ArrayHelper::map(Business::find()->where([])->all(),'id','title'),
//        'options' => [
//            'placeholder' => 'Select a company   ...',
//            'id' => 'idCompany',
//            'multiple' => false,
//        ],
//        'pluginOptions' => [
//            'allowClear' => true,
//        ]
//    ]); 
       ?>
    
    <?= $form->field($model, 'dateStart')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Enter date start ...'],
        'pluginOptions' => [
            'autoclose'=>true,
            'todayHighlight' => true,
            'language' => 'ru',
            'format' => 'yyyy-m-dd'
        ]
    ]);
    ?>
    
    <?= $form->field($model, 'dateEnd')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Enter date end ...'],
        'pluginOptions' => [
            'autoclose'=>true,
            'todayHighlight' => true,
            'language' => 'ru',
            'format' => 'yyyy-m-dd'
        ]
    ]);
    ?>
    
    <div class="form-group field-schedulekino-times">
    <label class="control-label" for="schedulekino-times">Время показа</label>
        <?php
            echo Select2::widget([
                'model' => $model,
                'attribute' => 'times',
                'options' => ['multiple' => true, 'placeholder' => 'Select times ...'],
                    'pluginOptions' => [
                            'tags' => [],
                            'maximumInputLength' => 5
                    ],
            ]);
        ?>
    </div>

    <?= $form->field($model, 'price')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

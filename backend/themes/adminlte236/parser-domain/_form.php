<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\Region;
use common\models\City;
use kartik\widgets\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\ParserDomain */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parser-domain-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-with-disabling-submit']]); ?>
    
    <?= $form->field($model, 'idRegion')->widget(Select2::className(), [
            'data' => ArrayHelper::map(Region::find()->orderBy('title')->asArray()->all(), 'id', 'title'),
            'options' => [
                'placeholder' => 'Select a region ...',
                'id' => 'region-id',
                'multiple' => false,
            ],
            'pluginOptions' => ['allowClear' => false],
        ]);
    ?>
    
    <?= $form->field($model, 'idCity')->widget(DepDrop::className(), [
            'data'=> ($model->isNewRecord)? [] : [$model->city->id => $model->city->title],
            'options' => ['placeholder' => 'Select city ...'],
            'type' => DepDrop::TYPE_SELECT2,
            'select2Options'=>['pluginOptions'=>['allowClear'=>false]],
            'pluginOptions'=>[
                'depends'=>['region-id'],
                'url' => Url::to(['/parser-domain/city']),
                'loadingText' => 'Loading city ...',
            ],
        ]);
    ?>

    <?= $form->field($model, 'domain')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'mDomain')->textInput(['maxlength' => 50]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

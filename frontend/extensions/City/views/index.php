<?php
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Url;

/**
 * @var $modelCity \common\models\City
 * @var $cityList \common\models\City[]
 */
?>

<?php
$form = ActiveForm::begin(['id' => 'form-city', 'action' => Url::to('site/city'), 'type' => ActiveForm::TYPE_INLINE]);
$data = ArrayHelper::map($cityList, 'id', 'title');
$data[0] = 'Главная';
?>

<?= $form->field($modelCity, 'id')->widget(Select2::className(), [
    'data' => $data,
    'options' => [
        'placeholder' => Yii::t('app', 'Select_city'),
        'id' => 'city-id',
        'multiple' => false,
        'width' => '300px',
        'data-home' => Url::to('http://' . Yii::$app->params['appFrontend']),
    ],
    'pluginOptions' => ['allowClear' => false],
])->label(''); ?>

<?php ActiveForm::end(); ?>
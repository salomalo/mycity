<?php

use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\extensions\MultiSelect\MultiSelect;

/* @var $this yii\web\View */
/* @var $model common\models\WidgetCityPublic */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="widget-city-public-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-with-disabling-submit']]); ?>

    <?= $form->field($model, 'city_id')->widget(Select2::className(), [
        'data' => ArrayHelper::merge(
            Yii::$app->params['adminCities']['select'],
            (($model->city_id and $model->city and !in_array($model->city_id, Yii::$app->params['adminCities']['id'])) ? [$model->city_id => $model->city->title] : [])
        ),
        'options' => ['placeholder' => 'Выберите город'],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>

    <i>
    <label>Для ВК: id группы (только числа).</label>
    <label>Для FB: ссылка на группу.</label>
    <label>Для TW: id созданного в настройках виджета.</label>
    </i>
    <?= $form->field($model, 'group_id')->textInput() ?>

    <i>
    <label>Для ВК: 120px+ ; 220px - стандартно.</label>
    <label>Для FB: 180px - 500px; 340px - стандартно.</label>
    <label>Для TW: настраивать через http://twitter.com/</label>
    </i>
    <?= $form->field($model, 'width')->textInput([
        'placeholder' => 'ширину блока в пикселах.',
    ]) ?>

    <i>
    <label>Для ВК: 200px-1200px ; 400px - стандартно.</label>
    <label>Для FB: 70px+ ; 500px - стандартно.</label>
    <label>Для TW: настраивать через http://twitter.com/</label>
    </i>
    <?= $form->field($model, 'height')->textInput([
        'placeholder' => 'высота блока в пикселах.',
    ]) ?>

    <i>
    <label>Для ВК: vk</label>
    <label>Для FB: fb</label>
    <label>Для TW: tw</label>
    </i>
    <?= $form->field($model, 'network')->textInput([
        'placeholder' => 'vk, fb, tw'
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

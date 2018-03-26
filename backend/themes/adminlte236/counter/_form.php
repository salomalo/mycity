<?php
/**
 * @var $this yii\web\View
 * @var $model common\models\Counter
 * @var $form yii\widgets\ActiveForm
 */

use common\models\Counter;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="counter-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 10]) ?>

    <?= $form->field($model, 'for_office')->widget(Select2::className(), [
        'data' => Counter::$forOptions,
        'options' => ['placeholder' => 'Выберите параметр'],
        'pluginOptions' => ['allowClear' => false],
    ]) ?>

    <?= $form->field($model, 'for_main')->widget(Select2::className(), [
        'data' => Counter::$forOptions,
        'options' => ['placeholder' => 'Выберите параметр'],
        'pluginOptions' => ['allowClear' => false],
    ]) ?>

    <?= $form->field($model, 'for_all_cities')->widget(Select2::className(), [
        'data' => Counter::$forOptions,
        'options' => ['placeholder' => 'Выберите параметр'],
        'pluginOptions' => ['allowClear' => false],
    ]) ?>

    <?= $form->field($model, 'cities_input')->widget(Select2::className(), [
        'data' => Yii::$app->params['adminCities']['select'],
        'options' => ['placeholder' => 'Выберите города', 'multiple' => true],
        'pluginOptions' => ['allowClear' => false],
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
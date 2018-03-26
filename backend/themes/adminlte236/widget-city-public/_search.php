<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\VkWidgetCityPublic */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vk-widget-city-public-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'city_id') ?>

    <?= $form->field($model, 'group_id') ?>

    <?= $form->field($model, 'element_id') ?>

    <?= $form->field($model, 'js_src') ?>

    <?php // echo $form->field($model, 'mode') ?>

    <?php // echo $form->field($model, 'wide') ?>

    <?php // echo $form->field($model, 'width') ?>

    <?php // echo $form->field($model, 'height') ?>

    <?php // echo $form->field($model, 'color1') ?>

    <?php // echo $form->field($model, 'color2') ?>

    <?php // echo $form->field($model, 'color3') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

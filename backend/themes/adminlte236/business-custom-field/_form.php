<?php

use common\extensions\MultiSelect\MultiSelect;
use common\models\BusinessCategory;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessCustomField */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="business-custom-field-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-with-disabling-submit']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'multiple')->widget(Select2::className(), ['data' => $model::$multiple]) ?>

    <?= $form->field($model, 'filter_type')->widget(Select2::className(), ['data' => $model::$filter_types]) ?>

    <?php
    $roots = BusinessCategory::find()->roots()->all();
    $categories = [];
    /** @var BusinessCategory $root */
    foreach ($roots as $root) {
        $categories[$root->title][$root->id] = "+ {$root->title}";

        $middle_children = $root->children(1)->all();

        /** @var BusinessCategory $middle_child */
        foreach ($middle_children as $middle_child) {
            $categories[$root->title][$middle_child->id] = "-- {$middle_child->title}";

            $other_children = $middle_child->children()->all();

            /** @var BusinessCategory $other_child */
            foreach ($other_children as $other_child) {
                $categories[$root->title][$other_child->id] = "---- {$other_child->title}";
            }
        }
    }
    ?>
    <?= $form->field($model, 'business_categories')->widget(Select2::className(), [
        'data' => $categories,
        'options' => ['multiple' => true, 'placeholder' => 'Введите стандартные значения'],
    ]) ?>

    <?= $form->field($model, 'default_values')->widget(Select2::className(), [
        'model' => $model,
        'attribute' => 'default_values',
        'options' => ['multiple' => true, 'placeholder' => 'Введите стандартные значения'],
        'pluginOptions' => ['tags' => [], 'allowClear' => true],
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

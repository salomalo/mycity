<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\WorkVacantion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="work-vacantion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'idCompany') ?>

    <?= $form->field($model, 'idCategory') ?>

    <?= $form->field($model, 'idCity') ?>

    <?= $form->field($model, 'idUser') ?>

    <?php // echo $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'proposition') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'skype') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'salary') ?>

    <?php // echo $form->field($model, 'isFullDay') ?>

    <?php // echo $form->field($model, 'isOffice') ?>

    <?php // echo $form->field($model, 'experience') ?>

    <?php // echo $form->field($model, 'male') ?>

    <?php // echo $form->field($model, 'minYears') ?>

    <?php // echo $form->field($model, 'maxYears') ?>

    <?php // echo $form->field($model, 'dateCreate') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

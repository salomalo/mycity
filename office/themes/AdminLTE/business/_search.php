<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\search\Business $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="business-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'idUser') ?>

    <?= $form->field($model, 'idCategory') ?>

    <?= $form->field($model, 'idCity') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'site') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'urlVK') ?>

    <?php // echo $form->field($model, 'urlFB') ?>

    <?php // echo $form->field($model, 'urlTwitter') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'dateCreate') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use common\models\AdsColor;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AdsColor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ads-color-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'isShowOnBusiness')->widget(Select2::className(),[
        'data' => AdsColor::$statusEnabledColor,
        'options' => [
            'placeholder' => 'Выберите ...',
        ],
        'pluginOptions' => [
            'allowClear' => false,
        ]
    ])->label("Наличие товара");
    ?>

    <?php if ($model->image): ?>
        <?= Html::img(Yii::$app->files->getUrl($model, 'image', 100)) ?>
        <?= !$model->isNewRecord ? Html::a('<span class="glyphicon glyphicon-trash"></span>',
            ['delete-img', 'id' => $model->id],
            ['title' => 'Delete', 'data-confirm' => 'Are you sure you want to delete this item?']
        ) : null ?>
    <?php endif; ?>

    <?= $form->field($model, 'image')->fileInput()->label('Картинка'); ?>

    <?= $model->image ?>

    <?= $form->field($model, 'idAds')->hiddenInput(['maxlength' => true])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

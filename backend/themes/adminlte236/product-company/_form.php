<?php
/**
 * @var yii\web\View $this
 * @var common\models\ProductCompany $model
 * @var yii\widgets\ActiveForm $form
 */

use common\models\ProductCategory;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<div class="product-company-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'form-with-disabling-submit']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    Категории продуктов:
    <?= Select2::widget([
        'data' => ProductCategory::getCategoryArray(),
        'value' => array_values(ArrayHelper::map($model->pсategories, 'ProductCategory', 'ProductCategory')),
        'name' => 'categories',
        'options' => ['placeholder' => 'Выберите категории продуктов ...', 'multiple' => true],
        'pluginOptions' => ['allowClear' => true],
    ]) ?>

    <?php if ($model->image) : ?>
        <br>
        <?= Html::img(Yii::$app->files->getUrl($model, 'image', 100)) ?>
        <a href="<?= Url::to(['product-company/update', 'id' => $model->id, 'actions' => 'deleteImg']) ?>" title="Delete" data-confirm="Are you sure you want to delete this item?">
            <span class="glyphicon glyphicon-trash"></span>
        </a>
    <?php endif; ?>

    <?= $form->field($model, 'image')->fileInput(); ?>

    <div class="form-group"><?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?></div>

    <?php ActiveForm::end(); ?>
</div>
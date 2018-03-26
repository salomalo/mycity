<?php
/**
 * @var yii\web\View $this
 * @var common\models\ProductCustomfield $customfield
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\ProductCategory;
use common\models\ProductCustomfield;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\CustomfieldCategory;
?>

<div class="product-customfield-form">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-with-disabling-submit']]); ?>

    <?= $form->field($customfield, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($customfield, 'idCategory')->widget(Select2::className(),[
        'data' => ProductCategory::getCategoryArray(),
        'options' => ['placeholder' => 'Select a category ...'],
        'pluginOptions' => ['allowClear' => true],
    ]) ?>

    <?= $form->field($customfield, 'idCategoryCustomfield')->widget(Select2::className(), [
        'data' => CustomfieldCategory::getCategoryArray(),
        'options' => ['placeholder' => 'Select a category ...'],
        'pluginOptions' => ['allowClear' => true],
    ]) ?>

    <?= $form->field($customfield, 'order')->textInput() ?>

    <?= $form->field($customfield, 'type')->dropDownList(ProductCustomfield::$types, ['prompt'=>'Select type']) ?>

    <?= $form->field($customfield, 'isFilter')->checkbox() ?>

    <?= $form->field($customfield, 'alias')->textInput(['maxlength' => 255]) ?>

    <?php if (!$customfield->isNewRecord) : ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default collapsed-box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Значения(для удаления оставьте поле пустым):</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="cf-list">
                            <?php if ($customfield->customfieldValue) : ?>
                                <?php foreach ($customfield->customfieldValue as $customfieldValue) : ?>
                                    <?= $this->render('_customfield-value', ['customfieldValue' => $customfieldValue]) ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <div style="margin: 10px;">
                            <?= Html::a('<i class="fa fa-plus-circle"></i>', null, [
                                'id' => 'add-product-customfield-value',
                                'class' => 'btn btn-warning',
                                'data' => [
                                    'customfield' => $customfield->id,
                                    'url' => Url::to(['/product-customfield/create-value-ajax']),
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
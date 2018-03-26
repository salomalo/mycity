<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\ProductCategory;
use common\extensions\nestedSelect;
use common\models\ProductCustomfield;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\CustomfieldCategory;

/**
 * @var yii\web\View $this
 * @var common\models\ProductCustomfield $model
 * @var yii\widgets\ActiveForm $form
 * @var bool $emptyCat
 * @var array $customfieldValue
 */

/** @var ProductCategory[] $productCategories */
$productCategories  = ProductCategory::find()->roots()->all();

$out = [];
foreach ($productCategories as $category) {
    $out[$category->id] = $category->title;

    /** @var ProductCategory[] $children */
    $children = $category->children(1)->all();

    foreach ($children as $child) {
        $out[$child->id] = "-$child->title";

        /** @var ProductCategory[] $others */
        $others = $child->children()->all();

        foreach ($others as $other) {
            $out[$other->id] = "--$other->title";
        }
    }
}
?>

<div class="product-customfield-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-with-disabling-submit']]); ?>
    
    <?php if ($emptyCat === true) : ?>
        <?= $form->field($model, 'idCategory')->widget(Select2::className(),[
            'data' => $out,
            'options' => ['placeholder' => 'Select a category ...'],
            'pluginOptions' => ['allowClear' => true],
        ]) ?>
    <?php else : ?>
        <?= Html::activeHiddenInput($model, 'idCategory') ?>
    <?php endif; ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'idCategoryCustomfield')->widget(Select2::className(), [
        'data' => ArrayHelper::map(CustomfieldCategory::find()->all(), 'id', 'title'),
        'options' => [
            'placeholder' => 'Select a category ...',
            'id' => 'idCategoryCustomfield',
            'multiple' => false,
        ],
        'pluginOptions' => ['allowClear' => true],
    ]) ?>

    <?= $form->field($model, 'order')->textInput() ?>

    <?= $form->field($model, 'type')->dropDownList(ProductCustomfield::$types, [
        'prompt'=>'Select type',
    ]) ?>

    <?php if (($model->type == $model::TYPE_DROP_DOWN) and !$model->isNewRecord) : ?>

        <div style="border: 2px solid red; padding: 20px;">

            <?php if (is_array($customfieldValue)) : ?>
                <?php foreach ($customfieldValue as $item) : ?>
                    <?= $item ?>
                <?php endforeach; ?>
            <?php endif; ?>

            <a href="#" class="addcustomfieldValue">Добавить value</a>
        </div>

    <?php endif; ?>

    <?php if ($model->type == $model::TYPE_STRING && !$model->isNewRecord) : ?>
        <div class="form-group field-productcustomfield-value required">
            <label class="control-label" for="productcustomfield-value">Значение</label>
            <input type="text" id="productcustomfield-value" class="form-control" name="ProductCustomfieldValue[][value]" value="<?=$customfieldValue[0] ?>" maxlength="255">
            <div class="help-block"></div>
        </div>

    <?php endif;?>

    <?= $form->field($model, 'isFilter')->checkbox() ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
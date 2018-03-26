<?php

use common\extensions\CustomCKEditor\CustomCKEditor;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\ProductCategory;
use kartik\widgets\Select2;
use common\extensions\nestedSelect;

/**
 * @var yii\web\View $this
 * @var common\models\ProductCategory $model
 * @var yii\widgets\ActiveForm $form
 */
if (!$model->isNewRecord) {
    $list = \yii\helpers\ArrayHelper::map(ProductCategory::find()->where(['<>','id', $model->id])->select('id, title')->orderBy('title')->all(), 'id', 'title');
} else {
    $list = [];
}
?>

<div class="product-category-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'class' => 'form-with-disabling-submit']]); ?>
    
    <?= $form->field($model, 'title')->textInput(['maxlength' => 255])->label("Заголовок") ?>

    <?= $form->field($model, 'url') ?>
    
    <?php if (isset($parent)): ?>
        <?= $form->field($model, 'pid',['template'=>'{input}'])->hiddenInput(['value'=>$parent]); ?>
    <?php else: ?>    
        <?= $form->field($model, 'pid')->widget(nestedSelect::className(), [
            'options' => ['placeholder' => 'Выберите категорию ...'],
            'pluginOptions' => ['allowClear' => true],
        ])->label('Категория') 
        ?> 
    <?php endif; ?>

    <?php if ($model->image) : ?>
        <?= Html::img(Yii::$app->files->getUrl($model, 'image', 100)) ?>
        <a href="<?= Url::to(['product-category/update', 'id' => $model->id, 'actions' => 'deleteImg'])?>" title="Delete" data-confirm="Are you sure you want to delete this item?" ><span class="glyphicon glyphicon-trash"></span></a>
    <?php endif; ?>
    
    <?= $form->field($model, 'image')->fileInput()->label("Картинка"); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-default collapsed-box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">SEO</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <?= $form->field($model, 'seo_title') ?>
                    <?= $form->field($model, 'seo_description') ?>
                    <?= $form->field($model, 'seo_keywords') ?>
                    <?= $form->field($model, 'sitemap_en')->checkbox() ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    
    <?php if(empty($model->root)):?>
        <?= $form->field($model, 'root')->hiddenInput(['maxlength' => 255, 'value' => $parent])->label('') ?> 
    <?php endif;?>

    <?php ActiveForm::end(); ?>

    <br>
    <?php if (!$model->isNewRecord):?>
        <?php $form = ActiveForm::begin(['action'=>['merge','id'=>$model->id]]) ?>
        <?= $form->field($model, 'id')->widget(Select2::className(),[
            'data' => $list, // onlyRoot
            'options' => [
                'placeholder' => ' Выберите категорию ...',
                'id' => 'idcat',
                'multiple' => false,
                'value' => 0,
            ],
            'pluginOptions' => ['allowClear' => true]
            ])->label('Объеденить с');
        ?>
        <div class="form-group">
            <?= Html::submitButton('Объединить', ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end() ?>

        <?php $form = ActiveForm::begin(['action'=>['change-root','id'=>$model->id]]) ?>
        <?php
        $model->id = ProductCategory::find()->where(['<>','id', $model->id])->orderBy('title')->one();
        ?>
        <?= $form->field($model, 'id')->widget(Select2::className(),[
            'data' => $list, // onlyRoot
            'options' => [
                'placeholder' => ' Выберите категорию ...',
                'id' => 'idcategory',
                'multiple' => false,
                'value' => 0,
            ],
            'pluginOptions' => ['allowClear' => false]
        ])->label('Изменить родительскую категорию на');
        ?>
        <div class="form-group">
            <?= Html::submitButton('Изменить родительскую категорию', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end() ?>
    <?php endif; ?>
</div>

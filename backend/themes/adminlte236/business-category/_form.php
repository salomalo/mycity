<?php

use common\extensions\MultiSelect\MultiSelect;
use common\extensions\MultiSelect2\MultiSelect2;
use common\models\ProductCategory;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use mihaildev\elfinder\ElFinder;
use mihaildev\elfinder\InputFile;
use kartik\widgets\Select2;
use common\models\BusinessCategory;
use common\models\Sitemap;
use common\extensions\nestedSelect;

/**
 * @var yii\web\View $this
 * @var common\models\BusinessCategory $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="business-category-form" style="padding-bottom: 200px;">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'form-with-disabling-submit']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255])->label("Заголовок") ?>

    <?php if (isset($parent)): ?>
        <?= $form->field($model, 'pid', ['template' => '{input}'])->hiddenInput(['value' => $parent]); ?>
    <?php else: ?>
        <?php
        $par = $model->parents(1)->all();
        $model->pid = ($par) ? $par[0]->id : null;
        ?>
        <?= $form->field($model, 'pid')->widget(Select2::className(), [
            'data' => ArrayHelper::map($par, 'id', 'title'),
            'disabled' => true,
            'options' => [
                'placeholder' => 'Выберите категорию ...',
                'id' => 'pid',
            ],
            'pluginOptions' => ['allowClear' => true,]
        ])->label("Родительская категория")
        ?>
    <?php endif; ?>

    <?php if (!$model->isNewRecord) : ?>
        <?= $form->field($model, 'productCategoryIds')->widget(Select2::className(), [
            'model' => new \common\models\search\ProductCategory(),
            'data' => ProductCategory::getCategoryList(),
            'attribute' => 'idCategories',
            'options' => [
                'placeholder' => 'Select a category ...',
                'id' => 'idCategories',
                'multiple' => true,
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]) ?>

    <?php endif; ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>

    <?php
    //        $form->field($model, 'pid')->widget(Select2::className(),[
    //        'data' => BusinessCategory::getCategoryList((!$nested)? true : false), // onlyRoot
    //        'options' => [
    //            'placeholder' => 'Выберите родительскую категорию ...',
    //            'id' => 'pid',
    //            'multiple' => false,
    //        ],
    //        'pluginOptions' => [
    //            'allowClear' => true,
    //        ]
    //    ])->label("Родительская категория");
    ?>

    <?php if ($model->image): ?>
        <img src="<?= \Yii::$app->files->getUrl($model, 'image', 100) ?>">
        <a href="<?= \Yii::$app->urlManager->createUrl(['business-category/update', 'id' => $model->id, 'actions' => 'deleteImg']) ?>"
           title="Delete" data-confirm="Are you sure you want to delete this item?"><span
                class="glyphicon glyphicon-trash"></span></a>

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
                    <?= $form->field($model, 'seo_title')->textInput() ?>
                    <?= $form->field($model, 'seo_description')->textarea() ?>
                    <?= $form->field($model, 'seo_keywords')->textInput() ?>

                    <?= $form->field($model, 'sitemap_en')->checkbox() ?>
                    <?= $form->field($model, 'sitemap_priority')->widget(Select2::className(), [
                        'data' => Sitemap::$priority,
                        'options' => ['placeholder' => 'Выберите приоритет'],
                        'pluginOptions' => ['allowClear' => true]
                    ]) ?>
                    <?= $form->field($model, 'sitemap_changefreq')->widget(Select2::className(), [
                        'data' => Sitemap::$changefreq,
                        'options' => ['placeholder' => 'Выберите частоту изменения'],
                        'pluginOptions' => ['allowClear' => true],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php if (empty($model->root)): ?>
        <?= $form->field($model, 'root')->hiddenInput(['maxlength' => 255, 'value' => $parent])->label('') ?>
    <?php endif; ?>

    <?php ActiveForm::end(); ?>


    <?php if (false): ?>
        <?php $form = ActiveForm::begin(['action' => ['merge', 'id' => $model->id]]); ?>
        <?= $form->field($modelcategory, 'id')->widget(MultiSelect::className(), [
            'url' => '/business/category-list',
            'className' => 'common\models\BusinessCategory',
            'multiple' => false,
            'options' => [
                'placeholder' => ' Выберите категорию ...',
                'id' => 'idcat',
                'value' => 0,
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ])->label('Объеденить с');
        ?>

        <div class="form-group">
            <?= Html::submitButton('Применить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    <?php endif; ?>
</div>

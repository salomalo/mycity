<?php

use common\extensions\FilesUpload\FilesUpload;
use common\models\City;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\ProductCategory;
use common\models\Product;
use common\models\ProductCompany;
use common\models\Business;
use kartik\widgets\Select2;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use common\extensions\MultiSelect\MultiSelect;
use common\extensions\fileUploadWidget\FileUploadUI;
use common\models\File;
use common\models\City as CityModel;
use kartik\widgets\DepDrop;

/**
 * @var yii\web\View $this
 * @var common\models\Product $model
 * @var yii\widgets\ActiveForm $form
 */

$js = "$('.ads-form-frontend .multi_select_lexa').on('click', '.drop>li', function(e){
        var sel = $('.multi_select_lexa').find('#ads-idcategory');
        if(sel.val() > 0){
            $('#formAction').val(1);
            $('#productForm').submit();
        }
    })   ";

$this->registerJs($js);
?>
<div class="ads-form-frontend">
    <?php $form = ActiveForm::begin([
        'id' => 'productForm',
        'enableClientValidation' => false,
        'options' => ['enctype'=>'multipart/form-data'],
    ]); ?>
    <?= Html::hiddenInput('action', 0, ['id' => 'formAction']) ?>

    <?= $form->field($model, 'title')->label("Заголовок") ?>

    <?=$form->field($model, 'idCategory')->widget(MultiSelect::className(), [
        'url' => '/business/product-category-list',
        'className' => 'common\models\ProductCategory',
        'options' => ['placeholder' =>'Выберите категорию ...'],
        'multiple' => false
    ])->label("Категория товара");
    ?>

    <?= $form->field($model, 'idCompany')->widget(Select2::className(),[
        'data' => ProductCategory::getAll(isset($model->idCategory) ? $model->idCategory : null),
        'options' => [
            'placeholder' => 'Выберите Производителя ...',
            'id' => 'productCompany',
            'multiple' => false,
        ],
        'pluginOptions' => ['allowClear' => true],
        'pluginEvents' => ['change' => "function() { $('#formAction').val(1); $('#productForm').submit(); }"],
    ])->label("Производитель"); ?>


    <?php // Поле Модель ?>
    <?php /*
    $listProd = [];
    if($model->idCategory && $model->idCompany){
        $product = Product::find()->select(['_id','model'])->where(['idCategory' => (int)$model->idCategory, 'idCompany' => (int)$model->idCompany])->all();
        if($product){
            foreach ($product as $item){
                $listProd[(string)$item['_id']] = $item['model'];
            }
        }
    }
    */ ?>
    <?php /* echo $form->field($model, 'model')->widget(Select2::className(),[
        'data' => $listProd,
        'options' => [
            'placeholder' => 'Выберите модель ...',
            'id' => 'productModel',
            'multiple' => false,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
        'pluginEvents' => [
            'change' => "function() { $('#formAction').val(1); $('#productForm').submit(); }",
        ],
    ])->label("Модель"); */ ?>

    <?php if($model->image):?>
        <img src="<?php echo \Yii::$app->files->getUrl($model, 'image', 100)?>" >
        <a href="<?=\Yii::$app->urlManager->createUrl(['ads/update', 'id' => (string)$model->_id, 'actions'=>'deleteImg'])?>" title="Delete" data-confirm="Are you sure you want to delete this item?" ><span class="glyphicon glyphicon-trash"></span></a>
    <?php endif;?>

    <?= $form->field($model, 'image')->fileInput()->label("Картинка"); ?>

    <?= $form->field($model, 'description')->widget(CKEditor::className(),[
        'editorOptions' => ElFinder::ckeditorOptions('elfinder', [
            'fontSize_defaultLabel' => '14px',
            'font_defaultLabel' => 'Arial',
        ]),
    ])->label("Описание"); ?>

    <?php //Простая галерея ?>
    <?= FilesUpload::widget(['model' => $model]) ?>

    <?= $form->field($model, 'price')->textInput()->label("Цена") ?>

    <?= $form->field($model, 'idBusiness')->widget(Select2::className(),[
        'data' => Business::getBusinessList(['idUser'=>Yii::$app->user->id]),
        'options' => [
            'placeholder' => 'Выберите предприятие ...',
            'multiple' => false,
            'id' => 'idBusiness'
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ])->label("Предприятие"); ?>

    <?php


    echo $form->field($model, 'idCity')->widget(DepDrop::classname(), [
        'data' => City::getAll(['id' => Yii::$app->params['activeCitys']]),
        'options' => [
            'placeholder' => Yii::t('app', 'Select_city'),
            'id' => 'idCity',
            'multiple' => false,
            'width' => '300px',
        ],
        'pluginOptions' => [
            'allowClear' => false,
            'url' => '/business/city-by-business',
            'depends' => ['idBusiness'],
            'placeholder' => Yii::t('app', 'Select_city'),
        ]
    ])->label('Город');
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord
            ? Yii::t('ads', 'Form_add')
            : Yii::t('ads', 'Form_save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'id' => 'formSubmit']) ?>
    </div>

    <?= $form->field($model, 'idUser')->hiddenInput(['value'=> $model->isNewRecord ? Yii::$app->user->id : $model->idUser])->label('') ?>

    <?php ActiveForm::end(); ?>

    <?php /*
    if (!$model->isNewRecord) {
        echo FileUploadUI::widget([
            'model' => $model,
            'isMongo' => true,
            'attribute' => 'gallery',
            'title' => 'Галерея',
            'type' => File::TYPE_ADS_GALLERY,
            'essence' => $model->_id,
            'gallery' => false,
            'fieldOptions' => [
                'accept' => 'image/*'
            ],
            'clientOptions' => [
                'maxFileSize' => Yii::$app->params['maxSizeUpload']
            ]
        ]);
    }
    */ ?>
</div>

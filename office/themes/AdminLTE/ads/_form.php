<?php

use common\extensions\CustomCKEditor\CustomCKEditor;
use common\extensions\FilesUpload\FilesUpload;
use common\extensions\MultiSelect\MultiSelect;
use common\models\Business;
use common\models\BusinessCategory;
use common\models\City as CityModel;
use common\models\City;
use common\models\ProductCategory;
use common\models\ProductCompany;
use common\models\ProductCustomfield;
use common\models\ProductCustomfieldValue;
use console\models\Arenda;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use office\extensions\LanguageWidget\LanguageWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\Ads $model
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
<div class="ads-form-office">
    <?php $form = ActiveForm::begin([
        'id' => 'productForm',
        'enableClientValidation' => false,
        'options' => ['enctype'=>'multipart/form-data'],
    ]); ?>
    <?= Html::hiddenInput('action', 0, ['id' => 'formAction']) ?>

    <?= $form->field($model, 'title')->label("Заголовок") ?>

    <?= $form->field($model, 'idBusiness')->widget(Select2::className(), [
        'data' => Business::getBusinessList(['idUser'=> Yii::$app->user->id]),
        'options' => [
            'placeholder' => 'Выберите предприятие ...',
            'multiple' => false,
            'id' => 'idBusiness'
        ],
        'pluginOptions' => ['allowClear' => true]
    ])->label("Предприятие");
    ?>

    <?= $form->field($model, 'idCategory')->widget(DepDrop::className(), [
        'type' => 2,
        'options' => ['width' => '300px'],
        'pluginOptions' => [
            'url' => '/business/business-category-by-business',
            'depends' => ['ads-idbusiness'],
            'placeholder' => Yii::t('app', 'Select category'),
        ]
    ])->label('Категория товару') ?>

    <?php
    $listProdCat = [];
    if ($model->idCategory) {
        /**
         * @var \creocoder\nestedsets\NestedSetsBehavior|ProductCategory|\yii\db\ActiveQuery $rootcategory
         */
        $rootcategory = ProductCategory::findOne(['id'=>(int)$model->idCategory]);
        if (isset($rootcategory) && !$rootcategory->isRoot()) {
            $rootcategory = $rootcategory->parents()->asArray();
            $rootcategory = $rootcategory->all();
            $idCategory = $rootcategory[0]['id'];
        } else {
            $idCategory = $model->idCategory;
        }
        $listProdCat = ProductCompany::find()
            ->leftJoin('ProductCategoryCategory', '"ProductCategoryCategory"."ProductCompany" = product_company.id')
            ->where(['ProductCategoryCategory.ProductCategory' => (int)$idCategory])
            ->all();
    }
    ?>

    <?= $form->field($model, 'idCompany')->widget(Select2::className(),[
        'data' => ProductCategory::getAll(isset($model->idCategory) ? $model->idCategory : null),
        'options' => [
            'placeholder' => 'Выберите Производителя ...',
            'id' => 'productCompany',
            'multiple' => false,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
        'pluginEvents' => [
            'change' => "function() { $('#formAction').val(1); $('#productForm').submit(); }",
        ],
    ])->label("Производитель"); ?>


    <?php // Поле Модель ?>
    <?php /*
    $listProd = [];
    if($model->idCategory && $model->idCompany){
        $product = Product::find()
            ->select(['_id','model'])
            ->where(['idCategory' => (int)$model->idCategory, 'idCompany' => (int)$model->idCompany])->all();
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

    <?php if ($model->image) : ?>
        <?php
        $img = Yii::$app->files->getUrl($model, 'image', 100);
        $url = Yii::$app->urlManager->createUrl(['ads/update', 'id' => (string)$model->_id, 'actions'=>'deleteImg']);
        ?>
        <img src="<?= $img ?>">
        <a href="<?= $url ?>" title="Delete" data-confirm="Are you sure you want to delete this item?">
            <span class="glyphicon glyphicon-trash"></span>
        </a>
    <?php endif; ?>

    <?= $form->field($model, 'image')->fileInput()->label("Картинка") ?>
    <?= $form->field($model, 'description')->widget(CustomCKEditor::className())->label("Описание") ?>
    <?php if ($model->idCategory === Arenda::getArendaId()) : ?>
        <div style="border: 1px solid #000000; padding: 5px; margin-bottom: 15px;">
            <ul>
                <?php $categoryFields = ProductCustomfield::findAll(['idCategory' => $model->idCategory]); ?>
                <?php foreach ($categoryFields as $cf) : ?>
                    <li>
                        <span><?=$cf->title?></span><br>
                        <?php
                        if ($cf->type == $cf::TYPE_DROP_DOWN) {
                            $cfv = ArrayHelper::map(
                                ProductCustomfieldValue::findAll(['idCustomfield' => $cf->id]),
                                'value',
                                'value'
                            );
                            echo Html::activeDropDownList($model, $cf->alias, $cfv, ['class'=>'form-control']);
                        } else {
                            $cfVal = ProductCustomfieldValue::find()->where(['idCustomfield' => $cf->id])->one();
                            echo Html::activeInput(
                                'text',
                                $model,
                                $cf->alias,
                                [
                                    'value'=>$cfVal['value'],
                                    'class'=>'form-control',
                                ]
                            );
                        }
                        ?>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php
    //Простая галерея
    echo FilesUpload::widget(['model' => $model]);
    echo $form->field($model, 'price')->textInput()->label("Цена");
    ?>

    <?= $form->field($model, 'idCity')->widget(Select2::className(), [
        'data' => ArrayHelper::map(City::find()->select(['id', 'title'])->where(['main' => City::ACTIVE])->all(), 'id', 'title'),
        'pluginOptions' => ['allowClear' => true],
        'options' => ['placeholder' => 'Выберите город'],
    ])->label('Город') ?>

    <div class="row"><div class="col-md-12"><div class="form-group">
        <?= Html::submitButton(
            $model->isNewRecord ? Yii::t('ads', 'Create') : Yii::t('ads', 'Form_save'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'id' => 'formSubmit']
        )
        ?>
    </div></div></div>
    <?= $form->field($model, 'idUser')
        ->hiddenInput(['value'=> $model->isNewRecord ? Yii::$app->user->id : $model->idUser])->label('')
    ?>
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

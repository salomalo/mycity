<?php
/**
 * @var yii\web\View $this
 * @var common\models\Ads $model
 * @var yii\widgets\ActiveForm $form
 * @var string $idCompanyName
 * @var integer $idBusiness
 * @var AdsProperty $adsProperty
 */

use common\extensions\CustomCKEditor\CustomCKEditor;
use common\extensions\FilesUpload\FilesUpload;
use common\models\AdsProperty;
use common\models\City;
use common\models\ProductCategory;
use common\models\Provider;
use common\models\search\ProductCategory as ProductCategoryModel;
use kartik\color\ColorInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use common\models\Business;
use kartik\widgets\Select2;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use kartik\widgets\DepDrop;

//устанавливаем сохраненые атрибуты
if ($adsProperty && $model->isNewRecord) {
    if ($adsProperty->category_id) {
        $model->idCategory = $adsProperty->category_id;
    }
    if ($adsProperty->business_id) {
        $model->idBusiness = $adsProperty->business_id;
    }
    if ($adsProperty->provider_id) {
        $model->idProvider = $adsProperty->provider_id;
    }
    if ($adsProperty->company_id) {
        $model->idCompany = $adsProperty->company_id;
    }
}

$image = $model->image ? Yii::$app->files->getUrl($model, 'image', 100) : null;
?>

<script>
    var csrfVar = '<?= Yii::$app->request->getCsrfToken()?>';
</script>

<div class="ads-form-frontend">
    <?php $form = ActiveForm::begin([
        'id' => 'productForm',
        'enableAjaxValidation' => true,
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <?= Html::hiddenInput('action', 0, ['id' => 'formAction']) ?>

    <?= $form->field($model, 'title')->label('Заголовок') ?>

    <?= $form->field($model, 'isShowOnBusiness', [
        'template' => '{label}<br/>{input}{error}{hint} '
    ])
        ->checkbox(['label' => ''])
        ->label("Показывать на главной странице предприятия")
    ?>

    <?= $form->field($model, 'idBusiness')->widget(Select2::className(), [
        'data' => Business::getBusinessList(['idUser'=> Yii::$app->user->id], $model->idBusiness),
        'options' => [
            'disabled' => !empty($idBusiness),
            'placeholder' => 'Выберите предприятие ...',
            'id' => 'idBusiness'
        ],
        'pluginOptions' => ['allowClear' => true],
        'pluginEvents' => ['change' => 'function(e) { businessChange(e.target.value); }'],
    ])->label('Предприятие') ?>

    <?php if ($idBusiness) : ?>
        <?= $form->field($model, 'idCategory')->widget(DepDrop::className(), [
            'type' => DepDrop::TYPE_SELECT2,
            'options' => ['width' => '300px', 'id' => 'idCategory'],
            'data' => [$model->idCategory => $model->idCategory],
            'pluginOptions' => [
                'url' => Url::to('/business/product-category-by-business'),
                'depends' => ['idBusiness'],
                'placeholder' => Yii::t('app', 'Категория товару'),
            ],
        ])->label('Категория товару') ?>
    <?php else : ?>
        <?= $form->field($model, 'idCategory')->widget(Select2::className(), [
            'model' => new ProductCategoryModel(),
            'data' => ProductCategory::getCategoryList(),
            'options' => ['width' => '300px', 'id' => 'idCategory'],
            'pluginOptions' => [
                'allowClear' => true,
                'initialize' => true,
                'placeholder' => Yii::t('app', 'Категория товару'),
            ]
        ])->label('Категория товару') ?>
    <?php endif; ?>

    <?= $form->field($model, 'idCompany')->widget(DepDrop::className(), [
        'type' => DepDrop::TYPE_SELECT2,
        'data' => [$model->idCompany => $idCompanyName],
        'options' => ['width' => '300px'],
        'select2Options' => [
            'pluginOptions' => [
                'tags' => true,
                'createTag' => new JsExpression('function (tag) {
return {
id: tag.term,
text: tag.term,
isNew : true
};
}')
            ]
        ],
        'pluginOptions' => [
            'initDepends' => ['idCategory'],
            'depends' => ['idCategory'],
            'initialize' => true,
            'url' => Url::to(['/business/get-all-product-category']),
            'placeholder' => 'Выберите Производителя ...',
        ]
    ])->label('Производитель') ?>

    <?= $form->field($model, 'idProvider')->widget(Select2::className(), [
        'data' => ArrayHelper::map(Provider::find()->where(['user_id' => Yii::$app->user->id])->all(), 'id', 'title'),
        'options' => ['width' => '300px', 'id' => 'idProvider'],
        'pluginOptions' => [
            'allowClear' => true,
            'placeholder' => Yii::t('provider', 'Select provider'),
        ]
    ])->label(Yii::t('provider', 'Providers')) ?>

    <div>
        <?= Html::a(Yii::t('provider', 'Add provider'), Yii::$app->urlManagerOffice->createUrl('/ru/provider/create'), ['target' => '_blank', 'class' => 'btn btn-info']) ?>
    </div>

    <?= Html::img($image, ['class' => 'ads-main-image', 'style' => 'max-width: 100px; max-height: 100px;']) ?>
    <?php if ($model->image) : ?>
        <a href="<?= Yii::$app->urlManager->createUrl(['ads/update', 'id' => (string)$model->_id, 'actions'=>'deleteImg'])?>" title="Delete" data-confirm="Are you sure you want to delete this item?" ><span class="glyphicon glyphicon-trash"></span></a>
    <?php endif; ?>

    <?= $form->field($model, 'image')->fileInput()->label('Картинка'); ?>

    <?= $form->field($model, 'description')->widget(CustomCKEditor::className())->label('Описание'); ?>

    <?= FilesUpload::widget(['model' => $model]) ?>

    <?= $form->field($model, 'trailer')->textInput(['placeholder' => 'https://www.youtube.com/watch?v=LRt2jX1kaYo'])->label("Обзор товара") ?>

    <?= $form->field($model, 'color')->widget(ColorInput::className(), ['options' => ['placeholder' => 'Выберите цвет...'],])->label('Цвет товара'); ?>

    <?= $form->field($model, 'price')->textInput()->label('Цена') ?>

    <?= $form->field($model, 'contact')->textInput(['value' => ($model->contact ? $model->contact : (isset(Yii::$app->user->identity->profile->phone) ? Yii::$app->user->identity->profile->phone : ''))])->label('Телефон') ?>

    <?= $form->field($model, 'idCity')->widget(DepDrop::className(), [
        'data' => City::getAll(['main' => City::ACTIVE]),
        'options' => [
            'placeholder' => Yii::t('app', 'Select_city'),
            'id' => 'idCity',
            'width' => '300px',
        ],
        'pluginOptions' => [
            'url' => Url::to(['/business/city-by-business']),
            'depends' => ['idBusiness'],
            'placeholder' => Yii::t('app', 'Select_city'),
        ]
    ])->label('Город') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord
            ? Yii::t('ads', 'Form_add')
            : Yii::t('ads', 'Form_save'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary', 'id' => 'formSubmit']) ?>
    </div>

    <?= $form->field($model, 'idUser')->hiddenInput(['value'=> $model->isNewRecord ? Yii::$app->user->id : $model->idUser])->label('') ?>

    <?php ActiveForm::end(); ?>
</div>
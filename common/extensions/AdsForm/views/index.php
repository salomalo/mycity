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
use common\models\Ads;
use common\models\AdsColor;
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
        ->label("Рекомендовать товар на главной странице предприятия")
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
                'initialize' => true,
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
            'initDepends' => ['idBusiness'],
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

    <?php if (!$model->isNewRecord) :?>
        <div style="border: 1px solid;padding: 10px;">
            <?php
            $adsColor = AdsColor::find()->where(['idAds' => $model->_id])->andWhere(['isShowOnBusiness' => AdsColor::ENABLED])->all();
            ?>
            <?php foreach ($adsColor as $color) : ?>
                <?= Html::img(Yii::$app->files->getUrl($color, 'image', 100), ['class' => 'ads-main-image', 'style' => 'max-width: 100px; max-height: 100px;']) ?>
            <?php endforeach; ?>
            <?= Html::a('Цвета товара', Url::to(['/ads-color/index', 'adsId' => (string)$model->_id]), ['class' => 'btn btn-info']) ?>
        </div>
    <?php endif; ?>

<!--    --><?php
//    echo $form->field($model, 'color')->widget(ColorInput::className(), ['options' => ['placeholder' => 'Выберите цвет...'],])->label('Цвет товара');
//    ?>

    <?= $form->field($model, 'trailer')->textInput(['placeholder' => 'https://www.youtube.com/watch?v=LRt2jX1kaYo'])->label("Обзор товара") ?>

    <?= $form->field($model, 'price')->textInput()->label('Цена') ?>

    <?= $form->field($model, 'discount')->widget(Select2::className(), [
        'data' => Ads::$typeDiscount,
        'options' => ['placeholder' => 'Выберте скидку ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('Скидка');
    ?>

    <?php
    $business = Business::findOne($idBusiness);
    $city = City::findOne($business->idCity);
    ?>

    <?php if ($business->phone) :?>
        <div style="height: 0;">
            <?= $form->field($model, 'contact')->hiddenInput(['value' => $business->phone])->label('') ?>
        </div>
    <?php else : ?>
        <?= $form->field($model, 'contact')->textInput()->label('Телефон') ?>
    <?php endif; ?>

    <div style="height: 0;">
        <?= $form->field($model, 'idCity')->hiddenInput(['value'=> $city->id])->label('') ?>
    </div>

    <div style="height: 0;">
        <?= $form->field($model, 'idUser')->hiddenInput(['value'=> $model->isNewRecord ? Yii::$app->user->id : $model->idUser])->label('') ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord
            ? Yii::t('ads', 'Form_add')
            : Yii::t('ads', 'Form_save'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary', 'id' => 'formSubmit']) ?>
        <?= Html::submitButton(
            Yii::t('ads', 'Сохранить и продолжить'),
            [
                'class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary',
                'name' => 'save-property',
                'id' => 'btn-save-ads-property',
            ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
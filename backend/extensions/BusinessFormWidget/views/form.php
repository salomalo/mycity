<?php
/**
 * @var $readOnly boolean
 * @var $endBody string
 * @var $init_address array
 * @var $model \common\models\Business
 */

use backend\extensions\BusinessFormWidget\BusinessFormWidget;
use backend\extensions\MyStarRating\MyStarRating;
use backend\models\Admin;
use common\extensions\BusinessCustomField\EchoCustomField;
use common\extensions\CustomCKEditor\CustomCKEditor;
use common\models\Business;
use common\models\BusinessAddress;
use common\models\BusinessCategory;
use common\models\BusinessTemplate;
use common\models\City;
use common\models\ProductCategory;
use common\models\User;
use kartik\widgets\DateTimePicker;
use kartik\widgets\DepDrop;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use common\extensions\MultiView\MultiView;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use common\models\File;
use common\extensions\fileUploadWidget\FileUploadUI;
use common\extensions\BusinessPrice\BusinessPrice;
use common\models\Sitemap;
use yii\helpers\ArrayHelper;
use common\models\Tag;

$admin = Yii::$app->user->identity;
?>
<div class="business-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'form-with-disabling-submit']]); ?>

    <?= $form->field($model, 'price')->fileInput() ?>

    <?= BusinessPrice::widget([
        'model' => $model,
        'isFrontend' => false
    ]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255])->label("Заголовок") ?>

    <?= $form->field($model, 'type')->widget(Select2::className(),[
        'data' => Business::$types,
        'options' => [
            'placeholder' => 'Select a type business ...',
            'id' => 'type',
        ],
        'pluginOptions' => ['allowClear' => true]
    ]); ?>

    <?= $form->field($model, 'isChecked')->checkbox() ?>

    <?php
    if ($model->type == Business::TYPE_KINOTHEATER)
        echo $form->field($model, 'cinema_id')->textInput(['type' => 'integer']);
    ?>

    <?= $form->field($model, 'karabas_business_id')->textInput(['type' => 'integer']) ?>

    <?= $form->field($model, 'ratio') ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'short_url')->textInput(['maxlength' => 255, 'placeholder' => 'citylife.info/mybestshop']) ?>

    <?php if ($model->image): ?>
        <?= Html::img(Yii::$app->files->getUrl($model, 'image', 100)) ?>
        <?= Html::a('<span class="glyphicon glyphicon-trash"></span>',
            ['business/update', 'id' => $model->id, 'actions' => 'deleteImg'],
            ['title' => 'Delete', 'data-confirm' => 'Are you sure you want to delete this item?']
        ) ?>
    <?php endif; ?>

    <?= $form->field($model, 'image')->fileInput()->label('Картинка'); ?>

    <?php if ($model->background_image): ?>
        <?= Html::img(Yii::$app->files->getUrl($model, 'background_image', 100)) ?>
        <?= Html::a('<span class="glyphicon glyphicon-trash"></span>',
            ['business/update', 'id' => $model->id, 'actions' => 'deleteBackgroundImg'],
            ['title' => 'Delete', 'data-confirm' => 'Are you sure you want to delete this item?']
        ) ?>
    <?php endif; ?>

    <?= $form->field($model, 'background_image')->fileInput()->label('Фон шапки' . Html::a('?', '#', ['title' => 'Изображение должно быть не меньше 200 х 500'])); ?>

    <?= $form->field($model, 'idUser')->widget(Select2::className(),[
        'data' => User::getAll(),
        'options' => [
            'placeholder' => 'Select a user ...',
            'id' => 'idUser',
        ],
        'pluginOptions' => ['allowClear' => false]
    ]) ?>

    <?= $form->field($model, 'trailer')->textInput(['placeholder' => 'azdwsXLmrHE']) ?>

    <?= $form->field($model, 'idCategories')->widget(Select2::className(), [
        'model' => new \common\models\search\BusinessCategory(),
        'data' => BusinessCategory::getCategoryList(),
        'attribute' => 'idCategories',
        'options' => [
            'placeholder' => 'Select a category ...',
            'id' => 'idCategory',
            'multiple' => true,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]) ?>

    <?php if ($admin->level === Admin::LEVEL_SUPER_ADMIN) : ?>
        <?= $form->field($model, 'due_date')->widget(DateTimePicker::className()) ?>
    <?php endif; ?>

    <?= EchoCustomField::widget(['model' => $model]) ?>
    <?php
    $idProductCategories = (count($model->idProductCategories) == 1 && empty($model->idProductCategories[0])) ? 0 : $model->idProductCategories;
    $item =  ArrayHelper::map(ProductCategory::find()->where(['id' => $idProductCategories])->all(), 'id', 'title');
    ?>
    <?= $form->field($model, 'idProductCategories')->widget(DepDrop::className(), [
        'model' => new \common\models\search\ProductCategory(),
        'data' => [$item],
        'type' => 2,
        'options' => [
            'multiple' => true,
            'width' => '300px',
        ],
        'pluginOptions' => [
            'allowClear' => false,
            'url' => '/business/business-category-by-business',
            'depends' => ['idCategory'],
            'initDepends' => ['idCategory'],
            'initialize' => true,
            'placeholder' => Yii::t('app', 'Select_product'),
        ]
    ])->label('Категория продуктов');
    ?>

    <?php if ($admin->level === Admin::LEVEL_SUPER_ADMIN) : ?>
        <?= $form->field($model, 'price_type')->widget(Select2::className(), [
            'data' => Business::$priceTypes,
            'options' => ['placeholder' => 'Выберите тип'],
        ]) ?>
    <?php endif; ?>

    <?= $this->render('_time', ['model' => $model]); ?>

    <?php
            if (!$readOnly) {
                echo '<div class="title-block-address">Адреса предприятия</div>';
            }
            echo Html::beginTag('div', ['id'=>'map-canvas', 'data-city' => $init_address]);
            echo Html::endTag('div');
    ?>
    <div style="width: 30%;margin-left: 20px;">
        <?= $form->field($model, 'idCity')->widget(Select2::className(), [
            'data' => ArrayHelper::map(City::find()->select(['id', 'title'])->where(['main' => City::ACTIVE])->all(), 'id', 'title'),
            'pluginOptions' => ['allowClear' => true],
            'options' => ['placeholder' => 'Выберите город'],
            'pluginEvents' => [
                "change" => "function(obj) { change_data_center(); change_hidden_input()}",
            ],
        ]) ?>
    </div>
    <?= $endBody ?>

    <?= $form->field($model, 'shortDescription')->widget(CustomCKEditor::className()) ?>

    <?= $form->field($model, 'description')->widget(CustomCKEditor::className())->label("Описание"); ?>

    <?= $form->field($model, 'site')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'phone')->textarea(['rows' => '4'])->label('Телефон') ?>

    <?= $form->field($model, 'urlVK')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'urlFB')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'urlTwitter')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'skype')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'tags')->widget(Select2::className(),[
        'data' => ArrayHelper::map(Tag::find()->all(),'title','title'),
        'options' => [
            'placeholder' => 'Select a tags ...',
            'id' => 'tags',
            'multiple' => true ,
        ],
        'pluginOptions' => [
            'tags' => true,
            'maximumInputLength' => Yii::$app->params['maximumTagsLength'],
        ]
    ]) ?>
    <?= MyStarRating::widget(['rating' => $model->rating]) ?>
    <?= $form->field($model, 'total_rating') ?>
    <?= $form->field($model, 'quantity_rating') ?>

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

    <?= $form->field($model, 'template_id')->widget(Select2::className(),[
        'data' => ArrayHelper::map(BusinessTemplate::find()->all(),'id','title'),
        'options' => [
            'placeholder' => 'Select a template ...',
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if (!$model->isNewRecord) : ?>
        <div class="row">
            <div class="col-md-8">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Галерея</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <?= FileUploadUI::widget([
                            'model' => $model,
                            'attribute' => 'name',
                            'type' => File::TYPE_BUSINESS,
                            'essence' => $essence,
                            'gallery' => false,
                            'fieldOptions' => ['accept' => 'image/*'],
                            'clientOptions' => ['maxFileSize' => Yii::$app->params['maxSizeUpload']],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
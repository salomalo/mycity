<?php

use common\extensions\CustomCKEditor\CustomCKEditor;
use common\models\Business;
use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\PostCategory;
use common\extensions\MultiView\MultiView;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use common\models\File;
use common\models\City;
use common\extensions\mihaildev\ckeditor\CKEditor as CKEditor2;
use common\extensions\mihaildev\elfinder\ElFinder as ElFinder2;
use common\extensions\fileUploadWidget\FileUploadUI;
use common\models\Sitemap;
use common\models\Post;
use common\models\Tag;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'form-with-disabling-submit']]); ?>
    <hr>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?php if (!$model->isNewRecord): ?>
        <?= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>
    <?php endif; ?>

    <p>Отображать</p>
    <?= $form->field($model, 'idCity')->widget(Select2::className(), [
        'data' => ArrayHelper::merge(
            Yii::$app->params['adminCities']['select'],
            (($model->idCity and $model->city and !in_array($model->idCity, Yii::$app->params['adminCities']['id'])) ? [$model->idCity => $model->city->title] : [])
        ),
        'options' => [
            'placeholder' => 'Выберите город',
            'id' => 'idCity',
            'multiple' => false,
        ],
        'pluginOptions' => ['allowClear' => true]
    ]) ?>


    <?php echo $form->field($model, 'business_id')->widget(Select2::className(), [
        'data' => Business::getBusinessList(['id' => $model->business_id]),
        'options' => ['placeholder' => 'Выберите предприятие', 'multiple' => false],
        'pluginOptions' => [
            'allowClear' => false,
            'minimumInputLength' => 3,
            'language' => ['errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }")],
            'ajax' => [
                'url' => Url::to(['business/ajax-search']),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(city) { return city.text; }'),
            'templateSelection' => new JsExpression('function (city) { return city.text; }'),
        ],
    ]);
    ?>

    <?= $form->field($model, 'allCity')->checkbox() ?>
    <?= $form->field($model, 'onlyMain')->checkbox() ?>

    <p>___</p>

    <?= $form->field($model, 'idUser')->widget(Select2::className(), [
        'data' => User::getAll(),
        'options' => [
            'placeholder' => 'Select a user ...',
            'id' => 'idUser',
        ],
        'pluginOptions' => ['allowClear' => true]
    ]) ?>

    <?= $form->field($model, 'idCategory')->widget(Select2::className(), [
        'data' => ArrayHelper::map(PostCategory::find()->all(), 'id', 'title'),
        'options' => [
            'placeholder' => 'Select a category ...',
            'multiple' => false,
        ],
        'pluginOptions' => ['allowClear' => true]
    ]) ?>

    <?php if ($model->image): ?>
        <?= Html::img(Yii::$app->files->getUrl($model, 'image', 90)) ?>
        <?= Html::a(Html::tag('span', null, ['class' => 'glyphicon glyphicon-trash']), ['post/update', 'id' => $model->id, 'actions' => 'deleteImg'], ['title' => 'Delete', 'data-confirm' => 'Are you sure you want to delete this item?']) ?>
    <?php endif; ?>

    <?= $form->field($model, 'image')->fileInput(); ?>

    <div class="form-group field-post-video">
        <label class="control-label" for="post-video">Video</label>
        <?= Select2::widget([
            'model' => $model,
            'attribute' => 'video',
            'options' => ['multiple' => true, 'placeholder' => 'Enter video hash ...'],
            'pluginOptions' => ['tags' => []],
        ]) ?>
    </div>

    <?= $form->field($model, 'shortText')->widget(CustomCKEditor::className()) ?>

    <?= $form->field($model, 'fullText')->widget(CustomCKEditor::className()) ?>

    <button class="check-address-post btn btn-success btn-xs">Проверить адрес</button>

    <?= $form->field($model, 'address')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'lat')->textInput() ?>
    <?= $form->field($model, 'lon')->textInput() ?>

    <div class="business-adress-form row">
        <?= MultiView::widget([
            '_view' => '_address',
            'data' => $listAddress,
            'inForm' => true,
            'relModelName' => Post::className(),
            'selectCity' => $selectCity,
            'mapWidth' => '600px',
        ]); ?>
    </div>

    <br>

    <?= $form->field($model, 'status')->widget(Select2::className(), [
        'data' => Post::$types,
        'options' => [
            'placeholder' => 'Select a status ...',
            'multiple' => false,
        ],
        'pluginOptions' => ['allowClear' => true]
    ]); ?>

    <?= $form->field($model, 'tags')->widget(Select2::className(), [
        'data' => ArrayHelper::map(Tag::find()->all(), 'title', 'title'),
        'options' => [
            'placeholder' => 'Select a tags ...',
            'id' => 'tags',
            'multiple' => true,
        ],
        'pluginOptions' => [
            'tags' => true,
            'maximumInputLength' => Yii::$app->params['maximumTagsLength'],
        ]
    ]); ?>
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
                            'type' => File::TYPE_POST,
                            'essence' => $this->context->id,
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
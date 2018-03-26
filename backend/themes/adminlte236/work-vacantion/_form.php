<?php

use common\extensions\CustomCKEditor\CustomCKEditor;
use common\models\City;
use kartik\widgets\DepDrop;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\WorkCategory;
use common\models\User;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use common\models\Sitemap;

/* @var $this yii\web\View */
/* @var $model common\models\WorkVacantion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="work-vacantion-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-with-disabling-submit']]); ?>
    
    <?= $form->field($model, 'idCategory')->widget(Select2::className(),[
        'data' => ArrayHelper::map(WorkCategory::find()->all(),'id','title'),
        'options' => ['placeholder' => 'Select a category ...'],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>
    <?php $getCookies = Yii::$app->getRequest()->getCookies(); ?>
    <?php echo $form->field($model, 'idCompany')->widget(Select2::className(), [
        'data' => (empty($model->company) or $model->isNewRecord) ? null : [$model->idCompany => $model->company->title],
        'options' => [
            'placeholder' => 'Search for a business ...',
            'multiple' => false,
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => Url::to(['business/ajax-search?city='.$getCookies->get('SUBDOMAINID')]),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(city) { return city.text; }'),
            'templateSelection' => new JsExpression('function (city) { return city.text; }'),
        ],
    ]); ?>
    <?php $cities = ArrayHelper::map(City::find()->where(['id' => Yii::$app->params['activeCitys']])->select(['id', 'title'])->all(), 'id', 'title'); ?>

    <?= $form->field($model, 'idCity')->widget(DepDrop::className(), [
        'data' => (empty($cities[$model->idCity]) or $model->isNewRecord) ? $cities : [$model->idCity => $cities[$model->idCity]],
        'options' => [
            'placeholder' => Yii::t('app', 'Select_city'),
            'id' => 'idCity',
            'multiple' => false,
            'width' => '300px',
        ],
        'pluginOptions' => [
            'allowClear' => false,
            'url' => '/city/city-by-business',
            'depends' => ['workvacantion-idcompany'],
            'placeholder' => Yii::t('app', 'Select_city'),
        ]
    ])->label('Город'); ?>

    <?= $form->field($model, 'idUser')->widget(Select2::className(),[
        'data' => ArrayHelper::map(User::find()->all(),'id','username'),
        'options' => ['placeholder' => 'Select a user ...'],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'description')->widget(CustomCKEditor::className()); ?>
    <?= $form->field($model, 'proposition')->widget(CustomCKEditor::className()); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'experience')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'education')->dropDownList($model->educationList, ['prompt'=>'']) ?>
    <?= $form->field($model, 'salary')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'male')->checkbox() ?>
    <?= $form->field($model, 'isFullDay')->checkbox() ?>
    <?= $form->field($model, 'isOffice')->checkbox() ?>
    <?= $form->field($model, 'phone')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'skype')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'minYears')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'maxYears')->textInput(['maxlength' => 255]) ?>

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
</div>

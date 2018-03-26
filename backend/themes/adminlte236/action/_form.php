<?php

use common\extensions\CustomCKEditor\CustomCKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;
use common\models\ActionCategory;
use common\extensions\mihaildev\ckeditor\CKEditor as CKEditor2;
use common\extensions\mihaildev\elfinder\ElFinder as ElFinder2;
use common\models\Sitemap;
use common\models\Tag;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\Action */
/* @var $form yii\widgets\ActiveForm */

$js = " $('.findCompany').click(function(e){
            e.preventDefault();
            
            var id = $(this).parent().find('#action-idcompany').val();
            if(!id){
                alert('Поле не заполненно.');
                return false;
            }
            
            url = $(this).data('url');
            
            $.ajax({
                type:'POST',
                url:url,
                data: 'id=' + id,
                success: function(data)
                    {
                        var obj = jQuery.parseJSON(data);
                        $(document).find('#action-companytitle').val(obj.company);
                        $(document).find('#action-actioncity').val(obj.city);
                    }
            });
            
        }); ";

$this->registerJs($js);

$getCookies = Yii::$app->request->cookies;
?>

<div class="action-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'class' => 'form-with-disabling-submit']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>
    
    <?php  if(!$getCookies->has('SUBDOMAINID')):?>
        <div class="col-sm-12">
            <div class="col-sm-4">
                <?= $form->field($model, 'idCompany')->textInput() ?>

                <?= Html::button('Проверить компанию', [
                    'class' => 'btn btn-primary findCompany', 
                    'name' => 'findCompany',
                    'data-url' => Yii::$app->urlManager->createUrl('business/check-company')
                ]) ?>

            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'companyTitle')->textInput(['readonly' => 'readonly']) ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'actionCity')->textInput(['readonly' => 'readonly']) ?>
            </div>
        </div>
        <div class="col-sm-12"> &nbsp;</div>
    <?php else:?>

        <?php
        echo $form->field($model, 'idCompany')->widget(Select2::className(), [
            'options' => ['placeholder' => 'Search for a business ...'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 3,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => \yii\helpers\Url::to(['business/ajax-search?city='.$getCookies->get('SUBDOMAINID')]),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(city) { return city.text; }'),
                'templateSelection' => new JsExpression('function (city) { return city.text; }'),
            ],
        ]);
        ?>
    <?php endif;?>
    
    <?php  // idCompany  > 250 000 > llowed memory size of 134217728 bytes exhausted (tried to allocate 128 bytes)   
//    $form->field($model, 'idCompany')->widget(Select2::className(),[
//        'data' => ArrayHelper::map(Business::find()->all(),'id','title'),
//        'options' => [
//            'placeholder' => 'Select a company   ...',
//            'id' => 'idCompany',
//            'multiple' => false ,
//        ],
//        'pluginOptions' => [
//            'allowClear' => true,
//        ]
//    ]);
    ?>
    
    <?= $form->field($model, 'idCategory')->widget(Select2::className(),[
        'data' => ActionCategory::getAll(),
        'options' => [
            'placeholder' => 'Select a category...',
            'id' => 'idCategory',
            'multiple' => false,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]);?>
    
    <?php if($model->image):?>
    <img src="<?=\Yii::$app->files->getUrl($model, 'image', 195)?>" > 
    <a href="<?=\Yii::$app->urlManager->createUrl(['action/update', 'id' => $model->id, 'actions'=>'deleteImg'])?>" title="Delete" data-confirm="Are you sure you want to delete this item?" ><span class="glyphicon glyphicon-trash"></span></a>
    
    <?php endif;?>
    <?= $form->field($model, 'image')->fileInput() ?>

    <?= $form->field($model, 'description')->widget(CustomCKEditor::className()) ?>

    <?= $form->field($model, 'price')->textInput() ?>
    
    <?= $form->field($model, 'dateStart')->widget(DateTimePicker::className(), [
        'options' => ['placeholder' => 'Enter date and time ...'],
        'pluginOptions' => [
            'autoclose'=>true,
            'todayHighlight' => true,
            'language' => 'ru',
            'pickerPosition' => 'top-right'
        ]
    ]); ?>
    
    <?= $form->field($model, 'dateEnd')->widget(DateTimePicker::className(), [
        'options' => ['placeholder' => 'Enter date and time ...'],
        'pluginOptions' => [
            'autoclose'=>true,
            'todayHighlight' => true,
            'language' => 'ru',
            'pickerPosition' => 'top-right'
        ]
    ]) ?>
    
    <?= $form->field($model, 'tags')->widget(Select2::className(), [
        'data' => Tag::getAll(),
        'options' => [
            'placeholder' => 'Select a tags ...',
            'id' => 'tags',
            'multiple' => true,
        ],
        'pluginOptions' => [
            'tags' => true,
            'maximumInputLength' => Yii::$app->params['maximumTagsLength'],
        ]
    ]) ?>

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

<?php

use backend\extensions\RepeatSelect\RepeatSelect;
use common\extensions\CustomCKEditor\CustomCKEditor;
use common\extensions\fileUploadWidget\FileUploadUI;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;
use common\models\AfishaCategory;
use common\extensions\mihaildev\ckeditor\CKEditor as CKEditor2;
use common\extensions\mihaildev\elfinder\ElFinder as ElFinder2;
use common\models\Sitemap;
use common\models\Tag;
use common\models\File;

/* @var $this yii\web\View */
/* @var $model common\models\Afisha */
/* @var $form yii\widgets\ActiveForm */
$js = " $('.findCompany').click(function(e){
            e.preventDefault();
            
            var id = '';
            
            $(this).parent().find('select#afisha-idscompany').each(function()
            {
                id = id + $(this).val() + ',';
            });

            if(id == 'null,'){
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
                        
                        result = '<table>';
                        
                        $.each(obj, function(i, item) {
                            result = result + getTr(item);
                        });
            
                        result = result + '</table>';

                        $(document).find('.companys-afisha').html(result);

                    }
            });
            
        }); 

function getTr(item){
    result = '<tr>';
                        
        result = result + '<td class=\"' + item.class + '\">' + item.id + '</td>';
        result = result + '<td> - </td>';
        result = result + '<td class=\"' + item.class + '\">' + item.title + '</td>';
        result = result + '<td> - </td>'; 
        result = result + '<td>' + item.cityTitle + '</td>';

    result = result + '</tr>';
    
    return result;
}

";

$this->registerJs($js);

$getCookies = Yii::$app->request->cookies;
?>

<div class="afisha-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'class' => 'form-with-disabling-submit']]); ?>
    
    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
    
    <?= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>

    <?= $dislpayCheck ? $form->field($model, 'isChecked')->checkbox() : ''?>
    
    <?php if ($model->image) : ?>
        <img src="<?= Yii::$app->files->getUrl($model, 'image', 70)?>">
        <a href="<?= Yii::$app->urlManager->createUrl(['afisha/update', 'id' => $model->id, 'actions'=>'deleteImg']) ?>"
           title="Delete" data-confirm="Are you sure you want to delete this item?">
            <span class="glyphicon glyphicon-trash"></span>
        </a>
    <?php endif;?>

    <?= $form->field($model, 'image')->fileInput() ?>
    <?= $form->field($model, 'trailer')->textInput(['placeholder' => 'K_9tX4eHztY'])->label('Видео') ?>
    
    <?= $form->field($model, 'idCategory')->widget(Select2::className(), [
        'data' => ArrayHelper::map(AfishaCategory::find()->where(['isFilm' => 0])->all(), 'id', 'title'),
        'options' => [
            'placeholder' => 'Select a category...',
            'id' => 'idCategory',
            'multiple' => false,
        ],
        'pluginOptions' => ['allowClear' => true]
    ]); ?>

    <?php if (!$getCookies->has('SUBDOMAINID')) : ?>

        <div class="form-group field-afisha-times <?= $model->getFirstError('idsCompany')? 'has-error' : ''?>">
            <label class="control-label" for="afisha-times">Ids Company</label> - id Компаний (цифры)
                <?= Select2::widget([
                    'model' => $model,
                    'attribute' => 'idsCompany',
                    'options' => [
                        'multiple' => true,
                        'placeholder' => 'Enter idsCompany ...'
                    ],
                    'pluginOptions' => [
                        'tags' => [],
                        'maximumInputLength' => 10
                    ],
                ]); ?>
            <div class="help-block"><?= $model->getFirstError('idsCompany')?></div>
        </div>

        <?= Html::button('Проверить компании', [
                    'class' => 'btn btn-primary findCompany',
                    'name' => 'findCompany',
                    'data-url' => Yii::$app->urlManager->createUrl('business/check-companys')
                    ]) ?>
        <div class="col-sm-12"> &nbsp;</div>

        <div class="companys-afisha">
            <table>
                <?php foreach (array_reverse($checkCompany) as $item) : ?>
                    <tr>
                        <td class="<?= $item['class']?>"><?= $item['id']?></td>
                        <td> - </td>
                        <td class="<?= $item['class']?>"><?= $item['title']?></td>
                        <td> - </td>
                        <td><?= $item['cityTitle']?></td>
                    </tr>
                <?php endforeach;?>
            </table>
        </div>

        <div class="col-sm-12"> &nbsp;</div>
    <?php else : ?>
        <?= $form->field($model, 'idsCompany')->widget(Select2::className(), [
            'options' => [
                'placeholder' => 'Search for a business ...',
                'multiple' => true,
            ],
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
        ]); ?>
    <?php endif; ?>
    
    <?= $form->field($model, 'description')->widget(CustomCKEditor::className()); ?>

    <?= RepeatSelect::widget(['form' => $form, 'model' => $model]) ?>

    <?= $form->field($model, 'dateStart')->widget(DateTimePicker::className(), [
        'options' => ['placeholder' => 'Enter date and time ...'],
        'pluginOptions' => [
            'autoclose' => true,
            'todayHighlight' => true,
            'language' => 'ru',
        ]
    ]); ?>

    <?= $form->field($model, 'dateEnd')->widget(DateTimePicker::className(), [
        'options' => ['placeholder' => 'Enter date and time ...'],
        'pluginOptions' => [
            'autoclose'=>true,
            'todayHighlight' => true,
            'language' => 'ru',
        ]
    ]); ?>
    <div class="form-group field-afisha-times">
    <label class="control-label" for="afisha-times">Время</label>
        <?= Select2::widget([
            'value' => (is_array($model->times) ? $model->times : [$model->times]),
            'name' => 'times',
            'options' => [
                'multiple' => true,
                'placeholder' => 'Select times ...'
            ],
            'pluginOptions' => [
                'tags' => [],
                'maximumInputLength' => 5
            ],
        ]); ?>

        <?= $form->field($model, 'always')->checkbox() ?>
    </div>
    
    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'rating')->textInput() ?>

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
                            'type' => File::TYPE_AFISHA,
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

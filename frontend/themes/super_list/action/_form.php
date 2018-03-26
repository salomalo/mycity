<?php

use kartik\widgets\DatePicker;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;
use common\models\Business;
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

$getCookies = Yii::$app->getRequest()->getCookies();
?>

<div class="action-form-frontend">

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
      
    <?php  // idCompany  > 250 000 > llowed memory size of 134217728 bytes exhausted (tried to allocate 128 bytes)   
    
        $business = ($model->isNewRecord)? $titleCompany : $model->companyName->title;
        $url = \yii\helpers\Url::to(['/action/business-list']);
        
        echo $form->field($model, 'idCompany')->widget(Select2::classname(), [
            'initValueText' => $business, // set the initial display text
            'options' => ['placeholder' => Yii::t('action', 'Search_a_company') . ' ...'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => $url,
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(city) { return city.text; }'),
                'templateSelection' => new JsExpression('function (city) { return city.text; }'),
            ],
        ])->label(Yii::t('business', 'Busines'));
    ?>
    
    <?= $form->field($model, 'idCategory')->widget(Select2::className(),[
        'data' => ActionCategory::getAll(),
        'options' => [
            'placeholder' => Yii::t('action', 'Select_a_category') . ' ...',
            'id' => 'idCategory',
            'multiple' => false ,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ])->label(Yii::t('action', 'Category'));?>
    
    <?php if($model->image):?>
        <img src="<?=\Yii::$app->files->getUrl($model, 'image', 195)?>" > 
    <?php endif;?>
        
    <?= $form->field($model, 'image')->fileInput() ?>

    <?=
    $form->field($model, 'description')->widget(CKEditor2::className(), [
        'editorOptions' => ElFinder2::ckeditorOptions('elfinder2', ['preset' => 'basic']),
    ])->label(Yii::t('action', 'Desc'));
    ?>

    <?= $form->field($model, 'price')->textInput()->label(Yii::t('action', 'Price')) ?>
    
    <?= $form->field($model, 'dateStart')->widget(DatePicker::classname(), [
    'options' => ['placeholder' => Yii::t('action', 'Enter date and time') . ' ...'],
    'pluginOptions' => [
        'autoclose'=>true,
        'todayHighlight' => true,
        'language' => 'ru',
        'pickerPosition' => 'top-right'
    ]
    ])->label(Yii::t('action', 'Date_start'));
    ?>
    
    <?= $form->field($model, 'dateEnd')->widget(DatePicker::classname(), [
    'options' => ['placeholder' => Yii::t('action', 'Enter date and time') . ' ...'],
    'pluginOptions' => [
        'autoclose'=>true,
        'todayHighlight' => true,
        'language' => 'ru',
        'pickerPosition' => 'top-right'
    ]
    ])->label(Yii::t('action', 'Date_end'));
    ?>
    
    <?= $form->field($model, 'tags')->widget(Select2::className(),[
        'data' => Tag::getAll(),
        'options' => [
//            'placeholder' => 'Select a tags ...',
            'id' => 'tags',
            'multiple' => true ,
        ],
        'pluginOptions' => [
            'tags' => true,
            'maximumInputLength' => Yii::$app->params['maximumTagsLength'],
        ]
    ]);?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('action', 'Form_add') : Yii::t('action', 'Form_save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

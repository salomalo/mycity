<?php

use backend\extensions\RepeatSelect\RepeatSelect;
use common\extensions\CustomCKEditor\CustomCKEditor;
use kartik\widgets\DatePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;
use common\models\AfishaCategory;
use common\extensions\mihaildev\ckeditor\CKEditor as CKEditor2;
use common\extensions\mihaildev\elfinder\ElFinder as ElFinder2;
use common\models\Tag;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\Afisha */
/* @var $form yii\widgets\ActiveForm */
$js = " $('.findCompany').click(function(e){
            e.preventDefault();
            var id = '';
            $(this).parent().find('select#afisha-idscompany').each(function() {
                id = id + $(this).val() + ',';
            });
            if (id == 'null,') {
                alert('Поле не заполненно.');
                return false;
            }
            url = $(this).data('url');
            $.ajax({
                type:'POST',
                url:url,
                data: 'id=' + id,
                success: function(data) {
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
function getTr(item) {
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

$getCookies = Yii::$app->getRequest()->getCookies();
?>

<div class="afisha-form-frontend">

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>
    
    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
    
    <?php if ($model->image) : ?>
        <img src="<?=\Yii::$app->files->getUrl($model, 'image', 70)?>" > 
    <?php endif; ?>

    <?= $form->field($model, 'image')->fileInput() ?>

    <?= $form->field($model, 'trailer')->textInput(['placeholder' => 'K_9tX4eHztY'])->label('Видео') ?>
    
    <?= $form->field($model, 'idCategory')->widget(Select2::className(),[
        'data' => AfishaCategory::getAll(['isFilm' => 0]),
        'options' => [
            'placeholder' => Yii::t('action', 'Select_a_category') . ' ...',
            'id' => 'idCategory',
            'multiple' => false,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ])->label(Yii::t('afisha', 'Category')) ?>
    
     
    
    <?php
    $business = $model->isNewRecord ? $titleCompany : (empty($model->companys[0]) ? '' : $model->companys[0]->title);

    $url = Url::to(['/afisha/business-list']);
    ?>

    <?php if (!$getCookies->has('SUBDOMAINID')) : ?>
        <?php
        $model->idsCompany = $modelCompanyId;
        ?>
        <div class="form-group field-afisha-times <?= $model->getFirstError('idsCompany')? 'has-error' : ''?>">
            <label class="control-label" for="afisha-times">Ids Company</label> - id Компаний (цифры)
            <?= Select2::widget([
                'model' => $model,
                //'value' => $modelCompanyId,
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


    <?= $form->field($model, 'description')->widget(CustomCKEditor::className())->label("Описание") ?>

    <?= RepeatSelect::widget(['form' => $form, 'model' => $model]) ?>

    <?= $form->field($model, 'dateStart')->widget(DatePicker::className(), [
        'options' => ['placeholder' => Yii::t('afisha', 'Select_date_start')],
        'pluginOptions' => [
            'autoclose'=>true,
            'todayHighlight' => true,
            'language' => 'ru',
            'format' => 'yyyy-mm-dd'
        ]
    ])->label(Yii::t('afisha', 'Date_start'));
    ?>

    <?= $form->field($model, 'dateEnd')->widget(DatePicker::className(), [
        'options' => ['placeholder' => Yii::t('afisha', 'Select_date_end')],
        'pluginOptions' => [
            'autoclose'=>true,
            'todayHighlight' => true,
            'language' => 'ru',
            'format' => 'yyyy-mm-dd'
        ]
    ])->label(Yii::t('afisha', 'Date_end')) ?>
    
    <div class="form-group field-afisha-times">
    <label class="control-label" for="afisha-times"><?= Yii::t('afisha', 'Time_spending')?></label>
        <?= Select2::widget([
            'model' => $model,
            'attribute' => 'times',
            'options' => ['multiple' => true, 'placeholder' => Yii::t('afisha', 'Enter_times') . ' ...'],
            'pluginOptions' => [
                'tags' => [],
                'maximumInputLength' => 5
            ],
        ]) ?>

        <?= $form->field($model, 'always')->checkbox() ?>
    </div>
    
    <?= $form->field($model, 'price')->textInput()->label(Yii::t('afisha', 'Price')) ?>
    
    <?= $form->field($model, 'tags')->widget(Select2::className(),[
        'data' => Tag::getAll(),
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
      
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('afisha', 'Form_add') : Yii::t('afisha', 'Form_save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

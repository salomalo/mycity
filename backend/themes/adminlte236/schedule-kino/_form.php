<?php

use backend\extensions\CheckboxActivator\CheckboxActivator;
use kartik\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use common\models\Afisha;
use common\models\Business;
use kartik\widgets\DatePicker;
/* @var $this yii\web\View */
/* @var $model common\models\ScheduleKino */
/* @var $form yii\widgets\ActiveForm */
/* @var $errors_msg array */

$js = " $('.findCompany').click(function(e){
            e.preventDefault();
            
            var id = $(this).parent().find('#schedulekino-idcompany').val();
            if (!id) {
                alert('Поле не заполненно.');
                return false;
            }
            
            url = $(this).data('url');
            
            $.ajax({
                type:'POST',
                url:url,
                data: 'id=' + id,
                success: function (data) {
                    var obj = jQuery.parseJSON(data);
                    $(document).find('#schedulekino-companytitle').val(obj.company);
                    $(document).find('#schedulekino-schedulecity').val(obj.city);
                }
            });
        }); ";

$this->registerJs($js);

$getCookies = Yii::$app->request->cookies;
?>

<div class="schedule-kino-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-with-disabling-submit']]); ?>

    <?= $form->field($model, 'idAfisha')->widget(Select2::className(),[
        'data' => ArrayHelper::map(Afisha::find()->where(['isFilm' => 1])->all(),'id','title'),
        'options' => [
            'placeholder' => 'Select a afisha   ...',
            'id' => 'idAfisha',
            'multiple' => false,
        ],
        'pluginOptions' => ['allowClear' => true],
    ]); ?>
    
    <?php if(!$getCookies->has('SUBDOMAINID')):?>
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
                <?= $form->field($model, 'scheduleCity')->textInput(['readonly' => 'readonly']) ?>
            </div>
        </div>
        <div class="col-sm-12"> &nbsp;</div>
    
    <?php else:?>
        
        <?= $form->field($model, 'idCompany')->widget(Select2::className(),[
            'data' => ArrayHelper::map(Business::find()->where(['idCity' => $getCookies->get('SUBDOMAINID'), 'type' => Business::TYPE_KINOTHEATER])->all(),'id','title'),
            'options' => [
                'placeholder' => 'Select a company   ...',
                'id' => 'idCompany',
                'multiple' => false,
            ],
            'pluginOptions' => ['allowClear' => true],
        ]) ?>
        
    <?php endif;?>
    
    <?= $form->field($model, 'dateStart')->widget(DatePicker::className(), [
        'options' => ['placeholder' => 'Enter date start ...'],
        'pluginOptions' => [
            'autoclose'=>true,
            'todayHighlight' => true,
            'language' => 'ru',
            'format' => 'yyyy-m-dd'
        ]
    ]) ?>
    
    <?= $form->field($model, 'dateEnd')->widget(DatePicker::className(), [
        'options' => ['placeholder' => 'Enter date end ...'],
        'pluginOptions' => [
            'autoclose'=>true,
            'todayHighlight' => true,
            'language' => 'ru',
            'format' => 'yyyy-m-dd'
        ]
    ]) ?>

    <div>
        <?= $form->field($model, 'times')->widget(Select2::className(), [
            'model' => $model,
            'attribute' => 'times',
            'options' => ['multiple' => true, 'placeholder' => 'Select times ...'],
            'pluginOptions' => ['tags' => [], 'maximumInputLength' => 5, 'allowClear' => true],
        ])->label('Время показа') ?>
    </div>

    <?= CheckboxActivator::widget(['element' => 'times2D', 'isSet' => !empty($model->times2D)])?>
    <?= CheckboxActivator::widget(['element' => 'times3D', 'isSet' => !empty($model->times3D)])?>

    <div id="times2D" <?= $model->times2D ? '' : 'class="hidden"'?>>
        <?= $form->field($model, 'times2D')->widget(Select2::className(), [
            'model' => $model,
            'attribute' => 'times2D',
            'options' => ['multiple' => true, 'placeholder' => 'Select times ...'],
            'pluginOptions' => ['tags' => [], 'maximumInputLength' => 5, 'allowClear' => true],
        ]) ?>
    </div>

    <div id="times3D" <?= $model->times3D ? '' : 'class="hidden"'?>>
        <?= $form->field($model, 'times3D')->widget(Select2::className(), [
            'model' => $model,
            'attribute' => 'times3D',
            'options' => ['multiple' => true, 'placeholder' => 'Select times ...'],
            'pluginOptions' => ['tags' => [], 'maximumInputLength' => 5, 'allowClear' => true],
        ]) ?>
    </div>

    <?= $form->field($model, 'price')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Ticket;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use mihaildev\ckeditor\CKEditor;
use office\extensions\TicketBusiness\TicketBusiness;
//use mihaildev\elfinder\ElFinder
/* @var $this yii\web\View */
/* @var $model common\models\Ticket */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ticket-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'type')->widget(Select2::className(),[
        'data' => Ticket::$types,
        'options' => [
            'placeholder' => 'Выберите тип запроса ...',
            'id' => 'type-id',
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ]
        ])->label("Тип запроса"); 
    ?>
   

<?= $form->field($model, 'pid')->widget(DepDrop::classname(), [
        'data'=> [],
        'options' => ['placeholder' => 'Выберите предприятие ...', 'width' => '300px'],
        'type' => DepDrop::TYPE_SELECT2,
        'select2Options'=>['pluginOptions'=>['allowClear'=>false]],
        'pluginOptions'=>[
            'depends'=>['type-id'],
            'url' => Url::to(['/business/user-business']),
            'loadingText' => 'Загрузка предприятий ...',
        ],
        'pluginEvents' => [
            "depdrop.init"=>"function() { console.log('depdrop.init'); }",
//            "depdrop.ready"=>"function() { log('depdrop.ready'); }",
//            "depdrop.change"=>"function(event, id, value, count) { log(id); log(value); log(count); }",
//            "depdrop.beforeChange"=>"function(event, id, value) { log('depdrop.beforeChange'); }",
//            "depdrop.afterChange"=>"function(event, id, value) { log('depdrop.afterChange'); }",
//            "depdrop.error"=>"function(event, id, value) { log('depdrop.error'); }",
        ],
    ])->label('Список компаний / (если тип запроса относится к компании)');
 ?>
    
    
    <?= $form->field($model, 'title')->textInput(['maxlength' => 255, 'readonly'=>($model->isNewRecord)? false : true])->label("Заголовок тикета") ?>
    
    <?= $form->field($model, 'body')->textarea()->label("Содержание"); ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Отправить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    
     <?= $form->field($model, 'idUser')->hiddenInput(['value'=>Yii::$app->user->id])->label('') ?>

    <?php ActiveForm::end(); ?>

</div> 
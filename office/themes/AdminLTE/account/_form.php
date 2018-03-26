<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;

/**
 * @var yii\web\View $this
 * @var common\models\Account $model
 * @var boolean $isDateInFuture
 * @var yii\widgets\ActiveForm $form
 */
?>

<!--Если дата родения из будущего-->
<?php if($isDateInFuture):?>
    <div class="alert alert-danger" role="alert">Неверная дата рождения</div>
<?php endif;?>

<!--Форма-->
<div class="account-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>
    
    <?= $form->field($model, 'username')->textInput(['maxlength' => 255])->label("Логин") ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 255])->label("E-mail") ?>
    
    <?= $form->field($model, 'birthday')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Введите день рождения ...'],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ])->label("День рождение"); ?>
            
    <?= $form->field($model, 'description')->widget(CKEditor::className(),[
    'editorOptions' => ElFinder::ckeditorOptions('elfinder',[
        'fontSize_defaultLabel' => '14px',
        'font_defaultLabel' => 'Arial',
    ]),
    ])->label("Описание"); ?>
    
    <?php if($model->photoUrl):?>
  <!--  <img src="https://s3-eu-west-1.amazonaws.com/files1q/account/53bbc6e4be5f2.jpg_100.jpg" > -->
    <img src="<?=\Yii::$app->files->getUrl($model, 'photoUrl', 100)?>" > 
    <a href="<?=\Yii::$app->urlManager->createUrl(['account/update', 'id' => $model->id, 'actions'=>'deleteImg'])?>" title="Delete" data-confirm="Are you sure you want to delete this item?" ><span class="glyphicon glyphicon-trash"></span></a>
    
    <?php endif;?>
    
    <?= $form->field($model, 'photoUrl')->fileInput()->label("Аватарка"); ?>

    <?= $form->field($model, 'vkID')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'vkToken')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

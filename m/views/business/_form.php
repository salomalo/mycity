<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\widgets\ActiveForm;
use common\extensions\MultiView\MultiView;
//use common\extensions\MultiSelect\MultiSelect;
use m\extensions\MultiSelect\MultiSelect;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var common\models\Business $model
 * @var yii\widgets\ActiveForm $form
 */
if($model->isNewRecord){
    $this->registerJsFile('@web/js/script.js', ['depends' => 'yii\web\YiiAsset']); 
} 

$this->registerJs(
        '$("document").ready(function(){
            $("#new_note").on("pjax:end", function() {
            initialize();
        });
    });'
    );
?>

<div class="business-form">
    
<?php Pjax::begin(['id' => 'new_note']) ?>
    
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'data-pjax' => true]]); ?>
    
    <?php if(!$model->isNewRecord):?>
        <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
    <?php endif;?>
    
    <?= $form->field($model, 'title')->textInput(['maxlength' => 255])->label("Заголовок") ?>

    <?php if ($model->image): ?>
        <div class="business-logo">
            <img src="<?= \Yii::$app->files->getUrl($model, 'image', 100) ?>" >
        </div>
    <?php endif; ?>

    <?= $form->field($model, 'image')->fileInput()->label("Логотип"); ?>

    <?= $form->field($model, 'idUser')->hiddenInput(['value'=> 1])->label(false) ?>
        
    <?=$form->field($model, 'idCategories')->widget(MultiSelect::className(), [
        'url'=>'/business/category-list',
        'className' => 'common\models\BusinessCategory',
        'search' => false,
        'options'=>[
            'placeholder' =>'Выберите категорию ...',
        ]
    ]);?>   
        
    <?= $form->field($model, 'idProductCategories')->widget(MultiSelect::className(), [
        'url'=>'/business/product-category-list',
        'className' => 'common\models\ProductCategory',
        'search' => false,
        'options'=>[
            'placeholder' =>'Выберите категорию ...',
        ]
    ]);?>

    <?= $this->render('_time', ['model' => $model]); ?>

    <!--<div class="business-adress-form row">-->
        
        
            <div class="form-group field-business-address-address">
                <label class="control-label">Адрес:</label>
                <input type="text" class="form-control business_address" name="business_address[1][address]" value="<?= ($model->isNewRecord)? '' : $adres->address?>">
            </div>
            <div class="form-group field-business-address-phone">
                <label class="control-label">Телефон:</label>
                <input type="text" class="form-control business_phone" name="business_address[1][phone]" value="<?= ($model->isNewRecord)? '' : $adres->phone?>">
            </div>
            <div class="form-group field-business-address-lat">
                <!--<label class="control-label">Широта:</label>-->
                <input type="hidden" class="form-control business_lat" name="business_address[1][lat]" value="<?= ($model->isNewRecord)? '' : $adres->lat?>">
            </div>
            <div class="form-group field-business-address-lon">
                <!--<label class="control-label">Долгота:</label>-->
                <input type="hidden" class="form-control business_lon" name="business_address[1][lon]" value="<?= ($model->isNewRecord)? '' : $adres->lon?>">
            </div>    
                
    
<?php
//MultiView::widget([
//    '_view' => '_address',
//    'data' => $address,
//    'relModelName' => 'common\models\BusinessAddress',
//    'readOnly' => false,
//]);
?>

    <!--</div>-->



    <div class="form-group text-center">
        
        <?php if(!$model->isNewRecord):?>
            <?= Html::hiddenInput('isUpdate', true)?>
            <?= Html::hiddenInput('id', $model->id)?>
        <?php endif;?>
        
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', [
            'class' => $model->isNewRecord ? 'btn btn-success col-xs-4 col-xs-offset-4 col-sm-4 col-sm-offset-4' : 'btn btn-primary'
            ]) ?>
        
        <?php if(!$model->isNewRecord):?>
            <?= Html::resetButton('Очистить', ['class' => 'btn btn-danger reset_button', 'data-id' => $model->id, 'data-url' => '/business/reset-image'])?>
        <?php endif;?>
        
    </div>

<?php ActiveForm::end(); ?>
<?php Pjax::end() ?>
</div>
<br>
<br>
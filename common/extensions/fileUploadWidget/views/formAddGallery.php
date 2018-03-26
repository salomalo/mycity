<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Gallery;

$gal = new Gallery;
?>

<div class="post-form">
    <?php $form = ActiveForm::begin([
        'action' => Yii::$app->UrlManager->createUrl($essence.'/addGallery'),
        ]); ?>
    
    <?= Html::hiddenInput('Gallery[idmodel]', isset($isMongo) && $isMongo ? $model->_id : $model->id)?>
    
    <?= Html::hiddenInput('Gallery[type]', $type)?>
    
    <?= Html::hiddenInput('Gallery[model]', $essence)?>
    
    <?= $form->field($gal, 'title')->textInput() ?>

    <div class="form-group">
            <?= Html::submitButton('Добавить галерею', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
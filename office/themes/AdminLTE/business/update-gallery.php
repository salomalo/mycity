<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//$this->title = $model->title;
//
//$this->params['breadcrumbs'][] = ['label' => Yii::t('business', 'Business'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;

$this->title = $business->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('business', 'Business'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$link = 'http://' . $business->getSubDomain() . Yii::$app->params['appFrontend'] . $business->getFrontendUrl();
$btnSeeOnFrontend = Html::a('Посмотреть на сайте', $link, ['class' => 'btn bg-purple', 'target' => '_blank', 'style' => 'margin-left: 20px']);

$script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>

<div class="post-form">
    <?= $this->render('top_block', ['id' => $business->id, 'active' => 'add-gallery'])?>

    <div class="update-gallery">
        <div class="box-header with-border">
            <i class="fa fa-bar-chart"></i>

            <h3 class="box-title">Тариф <?= $business::$priceTypes[$business->price_type] ?></h3>
        </div>
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'title')->textInput() ?>

    <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>

</div>
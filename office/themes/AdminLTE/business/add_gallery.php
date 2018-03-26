<?php
/**
 * @var yii\web\View $this
 * @var common\models\Business $model
 */
use common\extensions\fileUploadWidget\FileUploadUI;
use common\models\File;
use yii\helpers\Html;

$this->title = $model->title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('business', 'Business'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$link = 'http://' . $model->getSubDomain() . Yii::$app->params['appFrontend'] . $model->getFrontendUrl();
$btnSeeOnFrontend = Html::a('Посмотреть на сайте', $link, ['class' => 'btn bg-purple', 'target' => '_blank', 'style' => 'margin-left: 20px']);

$script = <<< JS
    $('.right-side .content-header h1').append('$btnSeeOnFrontend');
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>
<?= $this->render('top_block', ['id' => $model->id, 'active' => 'add-gallery'])?>

<div class="add-gallery">
    <div class="box-header with-border">
        <i class="fa fa-bar-chart"></i>

        <h3 class="box-title">Тариф <?= $model::$priceTypes[$model->price_type] ?></h3>
    </div>
<?php if (!$model->isNewRecord) : ?>
    <?= FileUploadUI::widget([
        'model' => $model,
        'attribute' => 'name',
        'type' => File::TYPE_BUSINESS,
        'essence' => $this->context->id,
        'gallery' => false,
        'fieldOptions' => ['accept' => 'image/*'],
        'clientOptions' => ['maxFileSize' => Yii::$app->params['maxSizeUpload']]
    ]) ?>
<?php endif; ?>
</div>

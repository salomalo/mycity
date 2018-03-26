<?php
/**
 * @var $model \common\models\Ads
 */
use common\models\Gallery;
use common\models\File;
use common\models\Product;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$gallery = File::find()->where(['type' => $type, 'pidMongo' => (string)$model->_id])->all();

if (!$gallery){
    $galleries = Gallery::find()->where(['pid' => (string)$model->_id])->all();
    $gallery =File::find()
        ->leftJoin('gallery','gallery.id = file.pid')
        ->where(['gallery.pid' => (string)$model->_id])
        ->andWhere(['file.type' => $type])
        ->all();
}

$images = [];
$previews = [];

if ($model->image){
    $previews[] = Yii::$app->files->getUrl($model, 'image', 100);
    $images[] = Yii::$app->files->getUrl($model, 'image', null);
}
if ($gallery){
    foreach ($gallery as $file) {
        $previews[] = Yii::$app->files->getUrl($gal, 'attachments', 100, $file->name, $this->context->id . '/' . $file->pid);
        $images[] = Yii::$app->files->getUrl($gal, 'attachments', null, $file->name, $this->context->id . '/' . $file->pid);
    }
}
if ($model->images){
    foreach ($model->images as $img){
        $previews[] = Yii::$app->files->getUrl($model, 'images', 100, $img);
        $images[] = Yii::$app->files->getUrl($model, 'images', null, $img);
    }
}

if($gallery || (isset($model->images) && $model->images) || (isset($model->image) && $model->image)) : ?>
    <div class="listing-detail-section" id="listing-detail-section-gallery">
        <h2 class="page-header">Галерея</h2>

        <div class="listing-detail-gallery-wrapper">
            <div class="listing-detail-gallery">

                <?php foreach ($images as $id => $image) : ?>
                    <?php $span = Html::tag('span', null, ['class' => 'item-image', 'data' => ['background-image' => $image]]); ?>
                    <?= Html::a($span, $image, ['rel' => 'listing-gallery', 'data' => ['item-id' => $id]])?>
                <?php endforeach; ?>

            </div>

            <div class="listing-detail-gallery-preview" data-count="<?= count($previews) ?>">
                <div class="listing-detail-gallery-preview-inner">

                    <?php foreach ($previews as $id => $preview) : ?>
                        <?php $img = Html::img($preview); ?>
                        <?= Html::tag('div', $img, ['data' => ['item-id' => $id]])?>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>
<?php endif; ?>


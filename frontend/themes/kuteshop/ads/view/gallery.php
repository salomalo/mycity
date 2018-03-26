<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Ads
 */


$images = [];
if ($model->image){
    $images[] = Yii::$app->files->getUrl($model, 'image');
}
if ($model->images) {
    foreach ($model->images as $img) {
        $images[] = Yii::$app->files->getUrl($model, 'images', 500, $img);
    }
}
?>

<div class="product_preview images-small">
    <div class="owl-carousel thumbnails_carousel"
         id="thumbnails"
         data-nav="true"
         data-dots="false"
         data-margin="10"
         data-responsive='{"0":{"items":3},"480":{"items":4},"600":{"items":5},"768":{"items":3}}'
    >
        <?php foreach ($images as $img): ?>
            <a href="#" data-image="<?= $img ?>" data-zoom-image="<?= $img ?>">
                <img src="<?= $img ?>" data-large-image="<?= $img ?>" alt="">
            </a>
        <?php endforeach; ?>
    </div><!--/ .owl-carousel-->
</div><!--/ .product_preview-->

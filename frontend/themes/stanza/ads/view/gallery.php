<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Ads
 */


use common\models\File;
use frontend\extensions\ThemeButtons\AddFavourite;

$images = [];
$images[] = Yii::$app->files->getUrl($model, 'image');
if ($model->images) {
    foreach ($model->images as $img) {
        $images[] = Yii::$app->files->getUrl($model, 'images', 500, $img);
    }
}
?>

<div class="col-sm-6 col-xs-12">
    <ul id="product-slider" class="product-item-slider product-image">
        <?php foreach ($images as $img): ?>
            <li class="item hoverStyle" data-thumb="<?= $img ?>">
                <img src="<?= $img ?>" style="object-fit: contain;">
                <div class="hoverBox">
                    <div class="hoverIcons">
                        <a href="<?= $img ?>" class="eye hovicon"><i
                                class="fa fa-expand expand-pic"></i></a>
                        <?= AddFavourite::widget([
                            'id' => $model->_id,
                            'type' => File::TYPE_ADS,
                            'template' => 'favorite_stanza',
                        ]) ?>
                    </div><!-- ( HOVER ICONS END ) -->
                </div><!-- ( HOVER BOX END ) -->
            </li>
        <?php endforeach; ?>
    </ul>
</div>

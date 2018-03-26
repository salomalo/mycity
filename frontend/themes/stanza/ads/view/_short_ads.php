<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Ads
 */
use common\models\File;
use frontend\extensions\ThemeButtons\AddFavourite;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="productBox">
        <div class="productImage hoverStyle" style="">
            <img src="<?= Yii::$app->files->getUrl($model, 'image') ?>" width="263" height="332" alt="" style="width: 100%;height: 368px;object-fit: contain;">
            <?php if ($model->discount) : ?>
                <span class="sale">Скидка!</span>
            <?php endif;?>
            <div class="hoverBox">
                <div class="hoverIcons">
                    <a href="<?= Url::to(['/ads/view', 'alias' => "{$model->_id}-{$model->url}"]) ?>" class="eye hovicon"><i class="fa fa-eye"></i></a>
                    <?= AddFavourite::widget([
                        'id' => $model->_id,
                        'type' => File::TYPE_ADS,
                        'template' => 'favorite_stanza',
                    ]) ?>
                </div><!-- ( HOVER ICONS END ) -->
            </div><!-- ( HOVER BOX END ) -->
        </div><!-- ( PRODUCT IMAGE END ) -->
        <div class="productDesc">
            <span class="product-title" style="overflow: hidden;">
                <?= Html::a($model->category->title, ['/ads/index', 'pid' => $model->category->url], ['style' => 'white-space: nowrap;']) ?>
            </span>
            <p><?= $model->title ?></p>
            <div class="stars">
                <span class="starsimgRating"></span>
            </div><!-- ( STARS END ) -->
            <strong class="productPrice"><?= $model->price ?> грн.</strong>
        </div><!-- ( PRODUCT DESCRIPTION END ) -->
    </div><!-- ( PRODUCT BOX END ) -->
</div>

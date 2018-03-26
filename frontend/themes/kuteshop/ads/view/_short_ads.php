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

<li class="product-item product-item-opt-2">
    <div class="product-item-info">
        <div class="product-item-photo" style="min-height:300px;">
            <a href="<?= Url::to(['/ads/view', 'alias' => "{$model->_id}-{$model->url}"]) ?>" class="product-item-img">
                <img src="<?= Yii::$app->files->getUrl($model, 'image') ?>" alt="product name">
            </a>
            <div class="product-item-actions">
                <?= AddFavourite::widget([
                    'id' => $model->_id,
                    'type' => File::TYPE_ADS,
                    'template' => 'favorite_kuteshop',
                ]) ?>
<!--                <a href="" class="btn btn-compare"><span>compare</span></a>-->
<!--                <a href="" class="btn btn-quickview"><span>quickview</span></a>-->
            </div>
<!--            <button class="btn btn-cart" type="button"><span>Add to Cart</span></button>-->

        </div>
        <div class="product-item-detail">
            <strong class="product-item-name"><a href="<?= Url::to(['/ads/view', 'alias' => "{$model->_id}-{$model->url}"]) ?>"><?= $model->title ?></a></strong>
            <div class="clearfix">
                <div class="product-item-price">
                    <?php if ($model->discount) : ?>
                        <span class="price"><?= $model->price * (1 - $model->discount / 100) ?> грн.</span>
                        <span class="old-price"><?= $model->price ?> грн.</span>
                    <?php else: ?>
                        <span class="price"><?= $model->price ?> грн.</span>
                    <?php endif; ?>
                </div>
                <div class="product-reviews-summary">
                    <div class="rating-summary">
                        <div class="rating-result" title="70%">
                            <span style="width:70%">
                                <span><span>70</span>% of <span>100</span></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</li>

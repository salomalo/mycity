<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Ads
 * @var $alias string
 */
use common\models\File;
use frontend\extensions\BasketButton\BasketButton;
use frontend\extensions\ThemeButtons\AddFavourite;
use yii\helpers\Url;
$aliasBusiness = "{$model->business->id}-{$model->business->url}";
$url = Url::to(['/business/' . $aliasBusiness . '/ads/' . "{$model->_id}-{$model->url}"]);
?>

<div class="product-item product-item-opt-2">
    <div class="product-item-info">
        <div class="product-item-photo">
            <a class="product-item-img" href="<?= $url ?>"><img alt="<?= $model->title ?>" src="<?= Yii::$app->files->getUrl($model, 'image') ?>"></a>
            <div class="product-item-actions">
                <?= AddFavourite::widget([
                    'id' => $model->_id,
                    'type' => File::TYPE_ADS,
                    'template' => 'favorite_kuteshop',
                ]) ?>
            </div>
            <?= BasketButton::widget(['model_id' => $model->_id, 'template' => 'index_kuteshop', 'alias' => $alias]) ?>
            <?php if ($model->discount) : ?>
                <span class="product-item-label label-price"><?= $model->discount ?>% <span>off</span></span>
            <?php endif; ?>
        </div>
        <div class="product-item-detail">
            <strong class="product-item-name"><a href="<?= $url ?>"><?= $model->title ?></a></strong>
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
                        <div title="80%" class="rating-result">
                            <span style="width:80%">
                                <span><span>80</span>% of <span>100</span></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

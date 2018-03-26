<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Ads
 * @var $business \common\models\Business
 */
use common\models\File;
use frontend\extensions\BasketButton\BasketButton;
use frontend\extensions\ThemeButtons\AddFavourite;
use yii\helpers\Html;
use yii\helpers\Url;
$aliasBusiness = "{$business->id}-{$business->url}";
$url = Url::to(['/business/' . $aliasBusiness . '/ads/' . "{$model->_id}-{$model->url}"]);
?>
<li class="col-sm-4 product-item ">
    <div class="product-item-opt-1">
        <div class="product-item-info">
            <div class="product-item-photo" style="min-height:300px;">
                <a href="<?= $url ?>" class="product-item-img"><img src="<?= Yii::$app->files->getUrl($model, 'image') ?>"
                                                         alt="<?= $model->title?>"></a>
                <div class="product-item-actions">
                    <?= AddFavourite::widget([
                        'id' => $model->_id,
                        'type' => File::TYPE_ADS,
                        'template' => 'favorite_kuteshop',
                    ]) ?>
<!--                    <a href="" class="btn btn-compare"><span>compare</span></a>-->
<!--                    <a href="" class="btn btn-quickview"><span>quickview</span></a>-->
                </div>
                <?= BasketButton::widget(['model_id' => $model->_id, 'template' => 'index_kuteshop']) ?>
                <?php if ($model->discount) : ?>
                <span class="product-item-label label-price"><?= $model->discount ?>% <span>off</span></span>
                <?php endif; ?>
            </div>
            <div class="product-item-detail">
                <strong class="product-item-name"><a href="<?= $url ?>"><?= $model->title?></a></strong>
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
                            <div class="rating-result" title="80%">
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
</li>

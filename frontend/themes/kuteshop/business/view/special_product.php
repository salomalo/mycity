<?php
/** @var $business Business */

use common\models\Ads;
use common\models\Business;
use yii\helpers\Url;

/** @var Ads $model */
$model = Ads::find()->where(['idBusiness' => $business->id])->orderBy(['views' => SORT_DESC])->one();
?>
<?php if ($model): ?>
<!-- Block  bestseller products-->
<div class="block-sidebar block-sidebar-products">
    <div class="block-title">
        <strong>Спец предложения</strong>
    </div>
    <div class="block-content">
        <div class="product-item product-item-opt-1">
            <div class="product-item-info">
                <div class="product-item-photo">
                    <a class="product-item-img" href="<?= Url::to(['/ads/view', 'alias' => "{$model->_id}-{$model->url}"]) ?>">
                        <img alt="<?= $model->title?>" src="<?= Yii::$app->files->getUrl($model, 'image') ?>">
                    </a>
                </div>
                <div class="product-item-detail">
                    <strong class="product-item-name">
                        <a href="<?= Url::to(['/ads/view', 'alias' => "{$model->_id}-{$model->url}"]) ?>">
                            <?= $model->title?>
                        </a>
                    </strong>
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
                                <div title="70%" class="rating-result">
                                    <span style="width:70%">
                                        <span><span>70</span>% of <span>100</span></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- Block  bestseller products-->
<?php endif; ?>

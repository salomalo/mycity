<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Ads
 * @var $business \common\models\Business
 */

use common\models\Ads;
use common\models\Business;
use yii\helpers\Url;

/** @var Ads[] $ads */
$ads = Ads::find()->where(['idBusiness' => $business->id])->andWhere(['isShowOnBusiness' => '1'])->andWhere(['not', '_id', ['$eq' => $model->_id]])->limit(6)->all();
?>

<?php if ($ads) : ?>
    <!-- Block  bestseller products-->
    <div class="block-sidebar block-sidebar-products">
        <div class="block-title">
            <strong>Лучшие товары</strong>
        </div>
        <div class="block-content">
            <div class="owl-carousel"
                 data-nav="false"
                 data-dots="true"
                 data-margin="0"
                 data-autoplayTimeout="700"
                 data-autoplay="true"
                 data-loop="true"
                 data-responsive='{
                                "0":{"items":1},
                                "420":{"items":1},
                                "480":{"items":2},
                                "600":{"items":2},
                                "992":{"items":1}
                                }'>
                <?php foreach ($ads as $key => $ad) : ?>
                    <?= $key % 3 === 0 ? '<div class="item">' : '' ?>
                        <div class="product-item product-item-opt-2">
                        <div class="product-item-info">
                            <div class="product-item-photo">
                                <a class="product-item-img" href="<?= Url::to(['/ads/view', 'alias' => "{$ad->_id}-{$ad->url}"]) ?>">
                                    <img alt="product name" src="<?= Yii::$app->files->getUrl($ad, 'image') ?>""></a>
                            </div>
                            <div class="product-item-detail">
                                <strong class="product-item-name"><a href="<?= Url::to(['/ads/view', 'alias' => "{$ad->_id}-{$ad->url}"]) ?>"><?= $ad->title ?></a></strong>
                                <div class="clearfix">
                                    <div class="product-item-price">
                                        <?php if ($ad->discount) : ?>
                                            <span class="price"><?= $ad->price * (1 - $ad->discount / 100) ?> грн.</span>
                                            <span class="old-price"><?= $ad->price ?> грн.</span>
                                        <?php else: ?>
                                            <span class="price"><?= $ad->price ?> грн.</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="product-reviews-summary">
                                        <div class="rating-summary">
                                            <div title="70%" class="rating-result">
                                                <span style="width:70%"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?= $key % 3 === 2 ? '</div>' : '' ?>
                <?php endforeach; ?>
                <?= count($ads) % 3 !== 0 ? '</div>' : '' ?>
            </div>
        </div>
    </div><!-- Block  bestseller products-->
<?php endif; ?>

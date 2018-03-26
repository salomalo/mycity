<?php
/**
 * @var $this \yii\web\View
 * @var $model Business
 * @var $alias string
 */

use common\models\Ads;
use common\models\Business;
use common\models\File;
use frontend\extensions\BasketButton\BasketButton;
use frontend\extensions\ThemeButtons\AddFavourite;
use yii\helpers\Url;

/** @var Ads $adsBig */
$adsBig = Ads::find()->where(['idBusiness' => $model->id])->orderBy(['views' => SORT_DESC])->limit(1)->one();
/** @var Ads[] $ads */
$ads = Ads::find()->where(['idBusiness' => $model->id])->orderBy(['views' => SORT_DESC])->offset(1)->limit(4)->all();

$aliasBusiness = "{$model->id}-{$model->url}";
?>
<?php if ($ads || $adsBig) : ?>
<div class="stripe">
    <div class="container">
        <h3 class="dashStyle">Лучшие продажи</h3>
        <div class="productsRow row best-seller">
            <div class="col-md-6 col-sm-12">
                    <?php foreach ($ads as $key => $ad) : ?>
                        <?= $key % 2 === 0 ? '<div class="row">' : ''?>
                        <?php
                        $url = Url::to(['/business/' . $aliasBusiness . '/ads/' . "{$ad->_id}-{$ad->url}"]);
                        ?>
                        <div class="col-sm-6 col-xs-12 col-xs-12-ls">
                            <div class="productBox <?= $key > 1 ? 'marginBottomNone' : '' ?>">
                                <div class="productImage hoverStyle">
                                    <div style="height:191px">
                                        <img src="<?= Yii::$app->files->getUrl($ad, 'image') ?>" width="263" height="191" alt="" style="    object-fit: contain;width: 100%;height: 100%;">
                                    </div>
                                    <div class="hoverBox">
                                        <div class="hoverIcons">
                                            <a href="<?= $url ?>" class="eye hovicon"><i
                                                    class="fa fa-eye"></i></a>
                                            <?= AddFavourite::widget([
                                                'id' => $ad->_id,
                                                'type' => File::TYPE_ADS,
                                                'template' => 'favorite_stanza',
                                            ]) ?>
                                        </div><!-- ( HOVER ICONS END ) -->
                                        <a href="javascript:void(0);" class="cartBTN2" style="padding:0;">
                                            <?= BasketButton::widget(['model_id' => $ad->_id, 'template' => 'stanza', 'alias' => $alias]) ?>
                                        </a>
                                    </div><!-- ( HOVER BOX END ) -->
                                </div><!-- ( PRODUCT IMAGE END ) -->
                                <div class="productDesc">
                                    <span class="product-title" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;"><a href="<?= $url ?>"><?= $ad->title ?></a></span>
                                    <p style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                                        <?= strip_tags($ad->description) ?>
                                    </p>
                                    <div class="stars">
                                        <span class="starsimgRating"></span>
                                    </div><!-- ( STARS END ) -->
                                    <?php if ($ad->discount): ?>
                                        <strong class="productPrice">
                                            <del><?= $ad->price?> грн.</del>
                                        </strong>
                                        <strong class="productPrice"><?= $ad->price * (1 - $ad->discount / 100) ?> грн.</strong>
                                    <?php else: ?>
                                        <strong class="productPrice"><?= $ad->price?> грн.</strong>
                                    <?php endif; ?>
                                </div><!-- ( PRODUCT DESCRIPTION END ) -->
                            </div><!-- ( PRODUCT BOX END ) -->
                        </div>
                        <?= $key % 2 === 1 ? '</div>' : ''?>
                    <?php endforeach; ?>
                    <?= count($ads) % 2 === 1 ? '</div>' : '' ?>
            </div>
            <?php if ($adsBig) : ?>
                <?php
                $url = Url::to(['/business/' . $aliasBusiness . '/ads/' . "{$adsBig->_id}-{$adsBig->url}"]);
                mb_internal_encoding("UTF-8");
                ?>
                <div class="col-md-6 col-sm-12">
                    <div class="productBox big-sell" style="">
                        <div class="productImage hoverStyle">
                            <div class="col-sm-6 col-xs-6 imgHeight">
                                <div style="width: 202px;height: 526px;">
                                    <img src="<?= Yii::$app->files->getUrl($adsBig, 'image') ?>" width="202" height="526" alt="" style="object-fit: contain;">
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-6">
                                <a href="#" class="onSalesBTN"><span>On</span>Sale!</a>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="text-left">
                                    <h3><?= $adsBig->title ?></h3>
                                    <p><?= mb_substr(strip_tags($adsBig->description, '<br>'), 0, 50) ?></p>
                                </div>
                            </div>
                            <div class="hoverBox">
                                <div class="hoverIcons">
                                    <a href="<?= $url ?>" class="eye hovicon"><i
                                            class="fa fa-eye"></i></a>
                                    <?= AddFavourite::widget([
                                        'id' => $adsBig->_id,
                                        'type' => File::TYPE_ADS,
                                        'template' => 'favorite_stanza',
                                    ]) ?>
                                </div><!-- ( HOVER ICONS END ) -->
                                <a href="javascript:void(0);" class="cartBTN2" style="padding:0;">
                                    <?= BasketButton::widget(['model_id' => $adsBig->_id, 'template' => 'stanza', 'alias' => $alias]) ?>
                                </a>
                            </div><!-- ( HOVER BOX END ) -->
                        </div><!-- ( PRODUCT IMAGE END ) -->
                        <div class="productDesc">
                            <div class="row">
                                <div class="col-xs-7">
                                    <span class="product-title"><a
                                            href="<?= $url ?>"><?= $adsBig->title ?></a></span>
                                    <p><?= mb_substr(strip_tags($adsBig->description, '<br>'), 0, 30) ?></p>
                                    <div class="stars">
                                        <span class="starsimgRating"></span>
                                    </div><!-- ( STARS END ) -->
                                </div>
                                <div class="col-xs-5">
                                    <?php if ($adsBig->discount): ?>
                                        <strong class="productPrice">
                                            <del><?= $adsBig->price?> грн.</del>
                                        </strong>
                                        <strong class="big-sel-price"><?= $adsBig->price * (1 - $adsBig->discount / 100) ?> грн.</strong>
                                    <?php else: ?>
                                        <strong class="big-sel-price"><?= $adsBig->price?> грн.</strong>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div><!-- ( PRODUCT DESCRIPTION END ) -->
                    </div><!-- ( PRODUCT BOX END ) -->
                </div>
            <?php endif; ?>
        </div><!-- ( PRODUCTS ROW END ) -->
    </div>
</div><!-- ( STRIPE END ) -->
<?php endif; ?>

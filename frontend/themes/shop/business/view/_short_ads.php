<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Ads
 */
use frontend\extensions\BasketButton\BasketButton;
use yii\helpers\Html;
use yii\helpers\Url;

$phone = $model ? isset($model->business->phone) ? $model->business->phone : null : null;

$alias = "{$model->business->id}-{$model->business->url}";
$city = Yii::$app->request->city;
$businessUrl = Url::to(['/business/view', 'alias' => $alias]);
$front = Yii::$app->params['appFrontend'];
if ($model->business->city && (($city && ($model->business->idCity !== $city->id)) || !$city)) {
    $businessUrl = "http://{$model->business->city->subdomain}.{$front}{$businessUrl}";
}

$url = Url::to(['/business/' . $alias . '/ads/' . "{$model->_id}-{$model->url}"]);
?>

<div itemscope="itemscope" itemtype="http://schema.org/Product" class="
                b-product-gallery b-hovered  qa-product-block
                js-product-line js-tracking
                js-rtb-partner"  style="margin-left: 20px;">
    <div class="b-product-gallery__content">

        <div class="b-product-gallery__holder">

            <div class="b-favorites-icon h-hidden  js-product-ad-conv-action">
                <div class="b-favorites-icon__bg"></div>
                <div class="b-favorites-icon__holder">
                            <span class="b-iconed-text js-comparison-handler " data-product-id="163016490"
                                  rendered="true">
                                <span class="b-iconed-text__icon-holder h-vertical-middle js-comparison-handler-star">
                                    <span
                                        class="h-vertical-middle h-cursor-pointer h-select-none qa-comparison-star icon-comparison"
                                        id="52E11A36-4F71-4F94-B536-A74F1885B4DA"></span>
                                </span>
                            </span>
                </div>
            </div>
            <a href="<?= $url ?>" itemprop="url"
               class="b-image-holder js-favourites-popup" id="Z069BAC39-143E-40F3-A218-E3B879EC837D">
                <img alt="<?= $model->title ?>" class="b-image-holder__img"
                     src="<?= Yii::$app->files->getUrl($model, 'image') ?>">
                <?php if ($model->discount) : ?>
                <span class="b-sticky-label b-product-gallery__sticker qa-discount-icon b-sticky-label_type_timeout">
                    <span class="b-sticky-label__holder">
                    <span class="b-sticky-label__value h-text-normal h-mr-20">
                        -<?= $model->discount ?>%
                    </span>
                    </span>
                </span>
                <?php endif; ?>
            </a>
            <div data-extend="Popup" data-subscribe="mouseout : current-target | hide-child-popups"
                 class="b-product-gallery__details-panel" id="Z935F7B7C-D22E-4C00-B91C-451F36DBA70C">

                <?= BasketButton::widget(['model_id' => $model->_id, 'template' => 'index_shop']) ?>

                <?php if ($phone) : ?>
                    <div class="h-inline-block h-mt-5">
                        <div class="b-iconed-text align-left js-product-ad-conv-action" data-cid="1647127">
                            <div class="b-iconed-text__icon-holder ">
                                <span class="b-iconed-text__icon icon-phone_small h-mr-10"></span>
                            </div>
                            <div id="wrapper-ID-cf647462-d28a-4917-affe-a4be7f90134b"
                                 class="b-iconed-text__text-holder">

                                <span class="b-pseudo-link">
                                    <span id="show-all-phones-ID-<?= $model->_id ?>" class="">
                                        <span data-reactid=".3">
                                            <span class="b-pseudo-link" data-reactid=".3.0">
                                                <span style="position:relative;text-align:left;display:inline-block;" data-reactid=".3.0.0">
                                                    <span data-reactid=".3.0.0.0"><span data-reactid=".3.0.0.0.0">+380  показать номер</span>
                                                    </span>
                                                </span>
                                            </span>
                                        </span>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <div class="b-product-gallery__content-spacer">
            <div class="h-mt-15">
                <div class="b-text-hider h-nowrap">
                    <span class="b-text-hider__right-shadow"></span>
                    <span itemprop="offers" itemscope="itemscope" itemtype="http://schema.org/Offer">
                                    <span itemprop="price">
                                        <?php if ($model->price) : ?>
                                            <?php if ($model->discount) : ?>
                                                <span class="h-font-size-19">
                                                    <?= '  ' . $model->price * (1 - $model->discount / 100) ?>
                                                </span> грн.
                                            <?php else: ?>
                                                <span class="h-font-size-19"><?= $model->price ?></span> грн.
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </span>
                                </span>
                </div>


                <div class="b-product-line__state b-product-line__state_type_available h-mt-10 h-mb-5">
                    <?= Yii::t('ads', 'New_by') ?>
                </div>

            </div>
            <h3 class="b-text-hider b-text-hider_type_two-lines medium-text h-mb-10" title="<?= $model->title ?>">
                <span class="b-text-hider__bottom-corner"></span>
                <a href="<?= $url?>" itemprop="url"
                   class="b-product-gallery__product-name-link qa-product-name-link"
                   id="link_to_product_163016490" data-extend="Tracking">
                    <span itemprop="name"><?= $model->title ?></span>
                </a>
            </h3>
        </div>
        <?php if ($model->business) : ?>
            <div class="b-product-gallery__content-hidden">
                <div class="bgl-product__section bgl-product__hidden">

                    <div class="bgl-product__company-info">
                        <div class="bgl-product__company-info-title" data-qaid="company-name"
                             title="<?= $model->business->title ?>">
                            <?= Html::a($model->business->title, $businessUrl) ?>
                        </div>
                        <div class="bgl-product__company-info-city" data-qaid="company-city">
                            г. <?= $model->business->city->title ?>
                        </div>
                    </div>

                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

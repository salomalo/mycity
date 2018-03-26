<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Action
 * @var $businessModel \common\models\Business
 */
use frontend\extensions\BasketButton\BasketButton;
use yii\helpers\Html;
use yii\helpers\Url;

$phone = isset($businessModel->phone) ? $businessModel->phone : null;

$alias = "{$businessModel->id}-{$businessModel->url}";
$city = Yii::$app->request->city;
$businessUrl = Url::to(['/business/view', 'alias' => $alias]);
$front = Yii::$app->params['appFrontend'];
if ($businessModel->city && (($city && ($businessModel->idCity !== $city->id)) || !$city)) {
    $businessUrl = "http://{$businessModel->city->subdomain}.{$front}{$businessUrl}";
}

$start = new DateTime($model->dateStart);
$end = new DateTime($model->dateEnd);
$alias = $model->id . '-' . $model->url;

?>

<div itemscope="itemscope" itemtype="http://schema.org/Product" class="
                b-product-gallery b-hovered  qa-product-block
                js-product-line js-tracking
                js-rtb-partner">
    <div class="b-product-gallery__content">

        <div class="b-product-gallery__holder">

            <div class="b-favorites-icon h-hidden  js-product-ad-conv-action">
                <div class="b-favorites-icon__bg"></div>
                <div class="b-favorites-icon__holder">
                    <span class="b-iconed-text js-comparison-handler " data-product-id="163016490" rendered="true">
                        <span class="b-iconed-text__icon-holder h-vertical-middle js-comparison-handler-star">
                            <span class="h-vertical-middle h-cursor-pointer h-select-none qa-comparison-star icon-comparison"
                                        id="52E11A36-4F71-4F94-B536-A74F1885B4DA"></span>
                        </span>
                    </span>
                </div>
            </div>
            <a href="<?= Url::to(['/action/view', 'alias' => "{$alias}"]) ?>" itemprop="url"
               class="b-image-holder js-favourites-popup" id="Z069BAC39-143E-40F3-A218-E3B879EC837D">
                <img alt="<?= $model->title ?>" class="b-image-holder__img"
                     src="<?= Yii::$app->files->getUrl($model, 'image') ?>">
            </a>
        </div>

        <div class="b-product-gallery__content-spacer">
            <div class="h-mt-15">
                <div class="b-text-hider h-nowrap">
                    <span class="b-text-hider__right-shadow"></span>
                    <span itemprop="offers" itemscope="itemscope" itemtype="http://schema.org/Offer">
                                    <span itemprop="price">
                                        <?php if ($model->price) : ?>
                                            <span class="h-font-size-19"><?= $model->price ?></span> грн.
                                        <?php endif; ?>
                                    </span>
                                </span>
                </div>


                <div class="listing-small-location"><a
                        href="<?= Url::to(['action/index', 'pid' => $model->category->url]) ?>">
                        <?= $model->category->title ?>
                    </a></div>

            </div>
            <h3 class="b-text-hider b-text-hider_type_two-lines medium-text h-mb-10" title="<?= $model->title ?>">
                <span class="b-text-hider__bottom-corner"></span>
                <a href="<?= Url::to(['/action/view', 'alias' => "{$alias}"]) ?>" itemprop="url"
                   class="b-product-gallery__product-name-link qa-product-name-link"
                   id="link_to_product_163016490" data-extend="Tracking">
                    <span itemprop="name"><?= $model->title ?></span>
                </a>
            </h3>
            <div class="listing-small-price-new"><span>c <?= $start->format('d.m.Y') ?> по <?= $end->format('d.m.Y') ?></span></div>
        </div>
        <?php if ($businessModel) : ?>
            <div class="b-product-gallery__content-hidden">
                <div class="bgl-product__section bgl-product__hidden">

                    <div class="bgl-product__company-info">
                        <div class="bgl-product__company-info-title" data-qaid="company-name"
                             title="<?= $businessModel->title ?>">
                            <?= Html::a($businessModel->title, $businessUrl) ?>
                        </div>
                        <div class="bgl-product__company-info-city" data-qaid="company-city">
                            г. <?= $businessModel->city->title ?>
                        </div>
                    </div>

                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
/**
 * @var $this \yii\web\View
 * @var $adsCategories array
 * @var $minPrice integer
 * @var $maxPrice integer
 */
use common\models\Ads;
use yii\helpers\Url;

?>

<aside class="slideNav closed">
    <a class="close-btn"><i class="fa fa-close"></i></a>
    <h4 class="dashStyle2">Категории</h4>
    <div class="sideNav">
        <ul class="li_accordion">
            <?php foreach ($adsCategories as $category) : ?>
                <li><a href="<?= Url::to(['/business/goods', 'alias' => "{$model->id}-{$model->url}", 'urlCategory' => $category->url])?>">
                        <span><?= $category->title ?></span>
                        (<?= Ads::find()->where(['idBusiness' => $model->id, 'idCategory' => $category->id])->count() ?>)
                    </a>
            <?php endforeach; ?>
        </ul>
    </div><!-- ( SIDE NAV END ) -->

    <?php if ($maxPrice && $minPrice) : ?>
        <h4 class="text-center">Сортировка по цене</h4>
        <div data-role="main" class="ui-content rangeSlider">
            <form action="/" method="get" class="widget_price_filter">
                <div class="price_slider_wrapper">
                    <div class="price_slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
                        <div class="ui-slider-range ui-widget-header ui-corner-all" style="left:0%;width:100%;"></div>
                        <span tabindex="0" class="ui-slider-handle ui-state-default ui-corner-all" style="left:0%;"></span>
                        <span tabindex="0" class="ui-slider-handle ui-state-default ui-corner-all" style="left:100%;"></span>
                    </div>
                    <div class="price_slider_amount">
                        <input type="text" placeholder="Min price" data-min="9" value="" name="min_price" id="min_price" style="display:none;">
                        <input type="text" placeholder="Max price" data-max="2999" value="" name="max_price" id="max_price" style="display:none;">
                        <div class="price_label">
                            Цена: <span class="from"><?= $minPrice ?> грн.</span> &mdash; <span class="to"><?= $maxPrice ?> грн.</span>
                        </div>
                        <div class="text-center"><button class="btn orangeBTN" type="submit">Применить</button></div>
                        <div class="clear"></div>
                    </div>
                </div>
            </form>
        </div><!-- ( RANGE SLIDER END ) -->
    <?php endif; ?>
</aside><!-- ( SLIDE NAV END ) -->

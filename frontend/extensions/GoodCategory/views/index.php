<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 * @var $adsCategories \common\models\ProductCategory[]
 * @var $pid integer current category id
 */
use frontend\extensions\SubCategoryWidget\SubCategoryWidget;
use yii\helpers\Html;
?>

<div id="listing_details-3" class="widget widget_listing_details">
    <div class="widget-inner">
        <h2 class="widgettitle"><?= Yii::t('business', 'Категории товаров') ?></h2>
        <div class="listing-detail-section" id="listing-detail-section-property-amenities">
            <div class="default-product-category-menu">
                <ul>
                    <?php foreach ($adsCategories as $category) : ?>
                        <?php
                        $currentLi = ($pid && ($category->id == $pid)) ? '' : '';
                        ?>
                        <li class="" style="width: 100%;" data-subCategory-id="item_subcategory_<?= $category->id  ?>">
                            <?= Html::a($category->title, ['/business/goods', 'alias' => "{$model->id}-{$model->url}", 'urlCategory' => $category->url], ['style' => $currentLi]) ?>
                        </li>
                        <?= SubCategoryWidget::widget(['business' => $model, 'category' => $category]);?>
                        <div class="b-categories-top__item-subcategories" style="display: none; top: -302px;">
                            <h3 class="b-categories-top__item-subcategories-title">
                                Одежда женская
                            </h3>
                            <div class="b-categories-top__subcat">
                                <h2 class="b-categories-top__subcat-title">
                                    Популярные категории
                                </h2>
                                <ul class="b-categories-top__subcat-popular-content">

                                    <li class="b-categories-top__subcat-item">
                                        <a href="/Zhenskii-platya" class="b-categories-top__subcat-link">
                                            <span class="b-image-holder"><img alt="" class="b-image-holder__img" content="https://images.ua.prom.st/582632706_w100_h100__platya_zima.jpg" data-subscribe="on_show@item_subcategory_354: IDom/trigger &quot;appear&quot;" longdesc="https://images.ua.prom.st/582632706_w100_h100__platya_zima.jpg" src="https://images.ua.prom.st/582632706_w100_h100__platya_zima.jpg" id="Z7117360C-ABB4-419F-8FAE-F1B4AA03B8B1"></span>
                                            Платья женские
                                        </a>
                                    </li>

                                    <li class="b-categories-top__subcat-item">
                                        <a href="/Zhenskoe-bele-i-kupalniki" class="b-categories-top__subcat-link">
                                            <span class="b-image-holder"><img alt="" class="b-image-holder__img" content="https://images.ua.prom.st/169147037_w100_h100_47.jpg" data-subscribe="on_show@item_subcategory_354: IDom/trigger &quot;appear&quot;" longdesc="https://images.ua.prom.st/169147037_w100_h100_47.jpg" src="https://images.ua.prom.st/169147037_w100_h100_47.jpg" id="Z842202D3-BE8B-48E9-87C3-44EF62650F4A"></span>
                                            Белье и купальники женские
                                        </a>
                                    </li>

                                    <li class="b-categories-top__subcat-item">
                                        <a href="/Zhenskaya-verhnyaya-odezhda" class="b-categories-top__subcat-link">
                                            <span class="b-image-holder"><img alt="" class="b-image-holder__img" content="https://images.ua.prom.st/169146999_w100_h100_wpid_6743621d6__ad2c320f75.jpg" data-subscribe="on_show@item_subcategory_354: IDom/trigger &quot;appear&quot;" longdesc="https://images.ua.prom.st/169146999_w100_h100_wpid_6743621d6__ad2c320f75.jpg" src="https://images.ua.prom.st/169146999_w100_h100_wpid_6743621d6__ad2c320f75.jpg" id="Z42B6D7C3-C3DE-462F-AFAA-FABA8ABC1765"></span>
                                            Верхняя одежда женская
                                        </a>
                                    </li>

                                    <li class="b-categories-top__subcat-item">
                                        <a href="/Domashnyaya-odezhda-zhenskaya" class="b-categories-top__subcat-link">
                                            <span class="b-image-holder"><img alt="" class="b-image-holder__img" content="https://images.ua.prom.st/169147304_w100_h100_velurovii_kostum_doma.jpg" data-subscribe="on_show@item_subcategory_354: IDom/trigger &quot;appear&quot;" longdesc="https://images.ua.prom.st/169147304_w100_h100_velurovii_kostum_doma.jpg" src="https://images.ua.prom.st/169147304_w100_h100_velurovii_kostum_doma.jpg" id="ZE5E5CF33-AC9B-4947-9D12-A7CDE65FFF82"></span>
                                            Одежда для сна и дома женская
                                        </a>
                                    </li>

                                    <li class="b-categories-top__subcat-item">
                                        <a href="/Zhenskie-kofty-i-kardigany" class="b-categories-top__subcat-link">
                                            <span class="b-image-holder"><img alt="" class="b-image-holder__img" content="https://images.ua.prom.st/169147034_w100_h100_zhenskie_kardigany_2012_4.jpg" data-subscribe="on_show@item_subcategory_354: IDom/trigger &quot;appear&quot;" longdesc="https://images.ua.prom.st/169147034_w100_h100_zhenskie_kardigany_2012_4.jpg" src="https://images.ua.prom.st/169147034_w100_h100_zhenskie_kardigany_2012_4.jpg" id="Z3D3B9630-578F-450A-8815-62E341618F66"></span>
                                            Свитеры и кардиганы женские
                                        </a>
                                    </li>

                                    <li class="b-categories-top__subcat-item">
                                        <a href="/Zhenskie-bluzki" class="b-categories-top__subcat-link">
                                            <span class="b-image-holder"><img alt="" class="b-image-holder__img" content="https://images.ua.prom.st/169147013_w100_h100_1342623761_bul.jpg" data-subscribe="on_show@item_subcategory_354: IDom/trigger &quot;appear&quot;" longdesc="https://images.ua.prom.st/169147013_w100_h100_1342623761_bul.jpg" src="https://images.ua.prom.st/169147013_w100_h100_1342623761_bul.jpg" id="ZE9047305-169F-420C-A293-301739D7E863"></span>
                                            Блузки и туники женские
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <ul class="b-categories-top__item-subcategories-list">

                                <li class="b-categories-top__item-subcategories-item-wrapper">
                                    <a href="/Zhenskii-platya" class="b-categories-top__item-subcategories-item">
                                        Платья женские
                                    </a>
                                </li>

                                <li class="b-categories-top__item-subcategories-item-wrapper">
                                    <a href="/Zhenskoe-bele-i-kupalniki" class="b-categories-top__item-subcategories-item">
                                        Белье и купальники женские
                                    </a>
                                </li>

                                <li class="b-categories-top__item-subcategories-item-wrapper">
                                    <a href="/Zhenskaya-verhnyaya-odezhda" class="b-categories-top__item-subcategories-item">
                                        Верхняя одежда женская
                                    </a>
                                </li>

                                <li class="b-categories-top__item-subcategories-item-wrapper">
                                    <a href="/Domashnyaya-odezhda-zhenskaya" class="b-categories-top__item-subcategories-item">
                                        Одежда для сна и дома женская
                                    </a>
                                </li>

                                <li class="b-categories-top__item-subcategories-item-wrapper">
                                    <a href="/Zhenskie-kofty-i-kardigany" class="b-categories-top__item-subcategories-item">
                                        Свитеры и кардиганы женские
                                    </a>
                                </li>
                            </ul>
                            <div class="h-layout-clear"></div>
                            <a href="/Zhenskaya-odezhda" class="b-categories-top__subcat-popular-footer-link">
                                Все подкатегории
                                <span class="b-categories-top__subcat-footer-arrow">›</span>
                            </a>
                        </div>
                    <?php endforeach; ?>
                    <li class="" style="width: 100%;">
                        <?= Html::a('Корневые категории', ['/business/goods', 'alias' => "{$model->id}-{$model->url}", 'urlCategory' => 'goods'], ['style' => $currentLi]) ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * @var \common\models\Ads $model
 * @var $isEmptyCart
 * @var $business \common\models\Business
 * @var $count
 */
use common\models\File;
use common\models\Lang;
use frontend\controllers\ShoppingCartController;
use frontend\extensions\ThemeButtons\AddFavourite;
use frontend\themes\kuteshop\AppAssets;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Breadcrumbs;

$isInBasket = ShoppingCartController::isInBasket($model->_id);
$bundle = AppAssets::register($this);

$script = <<< JS
    (function($) {
        "use strict";
        $(document).ready(function() {
            $('#slider-range').slider({
                range: true,
                min: 0,
                max: 500,
                values: [0, 300],
                slide: function (event, ui) {
                    $('#amount-left').text(ui.values[0] );
                    $('#amount-right').text(ui.values[1] );
                }
            });

            $('#amount-left').text($('#slider-range').slider('values', 0));
            $('#amount-right').text($('#slider-range').slider('values', 1));
        });

    })(jQuery);
JS;
$this->registerJs($script, View::POS_END);
?>
<!-- MAIN -->
<main class="site-main">

        <div class="columns container">

            <!-- Block  Breadcrumb-->
            <?php if (isset($this->context->breadcrumbs) and ($breadcrumbs = $this->context->breadcrumbs)) : ?>
                <?= Breadcrumbs::widget([
                    'tag' => 'ol',
                    'options' => ['class' => 'breadcrumb'],
                    'homeLink' => false,
                    'links' => $breadcrumbs,
                ]); ?>
            <?php else : ?>
                <?= Html::ul([['label' => Yii::t('app', 'Home'), 'url' => $main_string]], ['item' => function ($item, $index) {
                    if (!empty($item['url'])) {
                        return Html::tag('li', Html::a($item['label'], [$item['url']], []), ['class' => false]);
                    } else {
                        return Html::tag('li', $item['label'], ['class' => false]);
                    }
                }, 'class' => 'breadcrumb']); ?>
            <?php endif; ?>


            <div class="row">



                <!-- Main Content -->
                <div class="col-md-9  col-main">

                    <div class="row">

                        <div class="col-sm-6 col-md-6 col-lg-6">

                            <div class="product-media media-horizontal">

                                <div class="image_preview_container images-large">

                                    <img id="img_zoom" data-zoom-image="<?= Yii::$app->files->getUrl($model, 'image') ?>" src="<?= Yii::$app->files->getUrl($model, 'image') ?>" alt="">

                                    <button class="btn-zoom open_qv"><span>zoom</span></button>

                                </div>

                                <?php if ($model->images || $model->image) : ?>
                                    <?= $this->render('view/gallery', ['model' => $model]) ?>
                                <?php endif; ?>

                            </div><!-- image product -->
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-6">

                            <div class="product-info-main">

                                <h1 class="page-title">
                                    <?= $model->title?>
                                </h1>
                                <div class="product-reviews-summary">
                                    <div class="rating-summary">
                                        <div class="rating-result" title="70%">
                                                <span style="width:70%">
                                                    <span><span>70</span>% of <span>100</span></span>
                                                </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="product-info-price">
                                    <div class="price-box">
                                        <?php if ($model->discount) : ?>
                                            <span class="price"><?= $model->price * (1 - $model->discount / 100) ?> грн.</span>
                                            <span class="old-price"><?= $model->price ?> грн.</span>
                                            <span class="label-sale">-<?= $model->discount ?>%</span>
                                        <?php else: ?>
                                            <span class="price"><?= $model->price ?> грн.</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="product-code">
                                    Код товара: <?= $model->_id ?>
                                </div>
                                <div class="product-info-stock">
                                    <div class="stock available">
                                        <span class="label">Наличие: </span>В наличии
                                    </div>
                                </div>
                                <div class="product-condition">
                                    Состояние: Новый
                                </div>
                                <div class="product-overview">
                                    <div class="overview-content">
                                        <?= $model->description ?>
                                    </div>
                                </div>

                                <div class="product-add-form">
                                    <form method="post" action="<?= Url::to(['/shopping-cart/add-shopping-cart'])?>" class="clearfix">

<!--                                        <div class="product-options-wrapper">-->
<!---->
<!--                                            <div class="swatch-opt">-->
<!--                                                <div class="swatch-attribute color" >-->
<!--                                                    <span class="swatch-attribute-label">Color:</span>-->
<!--                                                    <div class="swatch-attribute-options clearfix">-->
<!--                                                        <div class="swatch-option color selected" style="background-color: #0c3b90 ;"></div>-->
<!--                                                        <div class="swatch-option color" style="background-color: #036c5d ;"></div>-->
<!--                                                        <div class="swatch-option color" style="background-color: #5f2363 ;"></div>-->
<!--                                                        <div class="swatch-option color " style="background-color: #ffc000 ;"></div>-->
<!--                                                        <div class="swatch-option color" style="background-color: #36a93c ;"></div>-->
<!--                                                        <div class="swatch-option color" style="background-color: #ff0000 ;"></div>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->


                                        <div class="product-options-bottom clearfix">
                                            <div class="actions">
                                                <input type="hidden" id="inputId" name="id"
                                                           value="<?= $model->_id ?>">
                                                <input type="hidden" id="redirect" name="redirect" value="index">
                                                <button class="action btn-cart" type="submit" title="Add to Cart">
                                                    <span><?= $isInBasket ? 'Убрать с корзины' : 'В коризну' ?></span>
                                                </button>
                                                <div class="product-addto-links">

                                                    <?= AddFavourite::widget([
                                                        'id' => $model->_id,
                                                        'type' => File::TYPE_ADS,
                                                        'template' => 'favorite_kuteshop_ads',
                                                    ]) ?>
<!--                                                    <a href="#" class="action btn-compare" title="Compare">-->
<!--                                                        <span>Compare</span>-->
<!--                                                    </a>-->
                                                </div>
                                            </div>

                                        </div>

                                    </form>
                                </div>
<!--                                <div class="share">-->
<!--                                    <img src="http://placehold.it/328x20" alt="share">-->
<!--                                </div>-->
                            </div><!-- detail- product -->

                        </div><!-- Main detail -->

                    </div>

                    <!-- product tab info -->

                    <div class="product-info-detailed ">

                        <!-- Nav tabs -->
                        <ul class="nav nav-pills" role="tablist">
                            <li role="presentation" class="active"><a href="#description"  role="tab" data-toggle="tab">Описания </a></li>
                            <li role="presentation"><a href="#tags"  role="tab" data-toggle="tab">Детали </a></li>
                            <li role="presentation"><a href="#reviews"  role="tab" data-toggle="tab">Отзывы</a></li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="description">
                                <div class="block-title">Product Details</div>
                                <div class="block-content">
                                    <?= $model->description ?>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="tags">
                                <div class="block-title">information</div>
                                <div class="block-content">
                                    <?= $this->render('view/add_info', ['model' => $model]) ?>
                                </div>
                            </div>

                            <div role="tabpanel" class="tab-pane" id="reviews">
                                <div class="product-comments-block-tab">
                                    <?= $this->render('view/comments', ['model' => $model]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- product tab info -->

                    <?= $this->render('view/related_products', ['model' => $model, 'business' => $business]) ?>

                    <?= $this->render('view/up_sell_products', ['model' => $model, 'business' => $business]) ?>

                </div><!-- Main Content -->

                <!-- Sidebar -->
                <div class=" col-md-3   col-sidebar">

                    <?= $this->render('view/best_sellers', ['model' => $model, 'business' => $business]) ?>

                    <!-- block slide top -->
                    <div class="block-sidebar block-banner-sidebar">
                        <div class="owl-carousel"
                             data-nav="false"
                             data-dots="true"
                             data-margin="0"
                             data-items='1'
                             data-autoplayTimeout="700"
                             data-autoplay="true"
                             data-loop="true">
                            <div class="item item1" >
                                <img src="http://placehold.it/270x345" alt="images">
                            </div>
                            <div class="item item2" >
                                <img src="http://placehold.it/270x345" alt="images">
                            </div>
                            <div class="item item3" >
                                <img src="http://placehold.it/270x345" alt="images">
                            </div>
                        </div>
                    </div><!-- block slide top -->


                </div><!-- Sidebar -->

            </div>
        </div>


    </main><!-- end MAIN -->

<!-- Custom scripts -->

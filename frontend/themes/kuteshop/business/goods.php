<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 * @var $ads \common\models\Ads
 * @var $isGoods boolean
 * @var $isAction boolean
 * @var $isAfisha boolean
 * @var $idCategory integer
 */

use common\models\Lang;
use common\models\ProductCategory;
use frontend\extensions\BlockListAds\BlockListAds;
use frontend\themes\kuteshop\AppAssets;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Breadcrumbs;
use yii\widgets\LinkPager;

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

if ($idCategory){
    $category = ProductCategory::findOne($idCategory);
} else {
    $category = null;
}

$bundle = AppAssets::register($this);
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
            <div class="col-md-9 col-md-push-3  col-main">

                <!-- images categori -->
                <div class="category-view">
                    <div class="owl-carousel "
                         data-nav="true"
                         data-dots="false"
                         data-margin="0"
                         data-items='1'
                         data-autoplayTimeout="700"
                         data-autoplay="true"
                         data-loop="true">
                        <div class="item ">
                            <a href=""><img src="<?= $bundle->baseUrl . '/images/22.jpg' ?>" alt="category-images"></a>
                        </div>
                        <div class="item ">
                            <a href=""><img src="<?= $bundle->baseUrl . '/images/23.jpg' ?>" alt="category-images"></a>
                        </div>
                        <div class="item ">
                            <a href=""><img src="<?= $bundle->baseUrl . '/images/21.jpg' ?>" alt="category-images" width="848px" height="567px"></a>
                        </div>
                    </div>
                </div><!-- images categori -->

                <!-- Toolbar -->
                <div class=" toolbar-products toolbar-top">

                    <div class="btn-filter-products">
                        <span>Filter</span>
                    </div>

                    <h1 class="cate-title"><?= $category ? $category->title : 'Список товаров' ?></h1>

                    <div class="modes">
                        <strong class="label">View as:</strong>
                        <strong class="modes-mode active mode-grid" title="Grid">
                            <span>grid</span>
                        </strong>
                        <!--                        <a href="Category2.html" title="List" class="modes-mode mode-list">-->
                        <!--                            <span>list</span>-->
                        <!--                        </a>-->
                    </div><!-- View as -->

                    <div class="toolbar-option">

                        <div class="toolbar-sorter ">
                            <label class="label">Short by:</label>
                            <select class="sorter-options form-control">
                                <option selected="selected" value="position">Position</option>
                                <option value="name">Name</option>
                                <option value="price">Price</option>
                            </select>
                            <a href="" class="sorter-action"></a>
                        </div><!-- Short by -->

                        <div class="toolbar-limiter">
                            <label class="label">
                                <span>Show:</span>
                            </label>

                            <select class="limiter-options form-control">
                                <option selected="selected" value="9">Show 18</option>
                                <option value="15">Show 15</option>
                                <option value="30">Show 30</option>
                            </select>

                        </div><!-- Show per page -->

                    </div>

                    <ul class="pagination">
                        <li class="action">
                            <a href="#">
                                <span><i aria-hidden="true" class="fa fa-angle-left"></i></span>
                            </a>
                        </li>

                        <li class="active">
                            <a href="#">1</a>
                        </li>
                        <li>
                            <a href="#">2</a>
                        </li>
                        <li>
                            <a href="#">3</a>
                        </li>
                        <li class="action">
                            <a href="#">
                                <span><i aria-hidden="true" class="fa fa-angle-right"></i></span>
                            </a>
                        </li>
                    </ul>

                </div><!-- Toolbar -->

                <!-- List Products -->
                <div class="products  products-grid">
                    <ol class="product-items row">
                        <?php foreach ($ads as $ad) : ?>
                            <?= $this->render('view/_short_ads', ['model' => $ad, 'business' => $model]) ?>
                        <?php endforeach; ?>
                    </ol><!-- list product -->
                </div> <!-- List Products -->

                <!-- Toolbar -->
                <div class=" toolbar-products toolbar-bottom">
                    <?= isset($pages) ? LinkPager::widget([
                        'pagination' => $pages,
                    ]) : ''; ?>

                </div><!-- Toolbar -->

            </div><!-- Main Content -->

            <!-- Sidebar -->
            <div class="col-md-3 col-md-pull-9  col-sidebar">


                <?= BlockListAds::widget([
                    'template' => 'kuteshop',
                    'idCategory' => $idCategory,
                    'business' => $model,
                ]) ?>

                <?= $this->render('view/slider_top', ['business' => $model]) ?>

                <?= $this->render('view/special_product', ['business' => $model]) ?>

                <!-- block slide top -->
<!--                <div class="block-sidebar block-sidebar-testimonials">-->
<!--                    <div class="block-title">-->
<!--                        <strong>Testimonials</strong>-->
<!--                    </div>-->
<!--                    <div class="block-content">-->
<!--                        <div class="owl-carousel"-->
<!--                             data-nav="false"-->
<!--                             data-dots="true"-->
<!--                             data-margin="0"-->
<!--                             data-items='1'-->
<!--                             data-autoplayTimeout="700"-->
<!--                             data-autoplay="true"-->
<!--                             data-loop="true">-->
<!--                            <div class="item ">-->
<!--                                <strong class="name">Roverto & Maria</strong>-->
<!--                                <div class="avata">-->
<!--                                    <img src="http://placehold.it/102x102" alt="avata">-->
<!--                                </div>-->
<!--                                <div class="des">-->
<!--                                    "Your product needs to improve more. To suit the needs and update your image up"-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="item ">-->
<!--                                <strong class="name">Roverto & Maria</strong>-->
<!--                                <div class="avata">-->
<!--                                    <img src="http://placehold.it/102x102" alt="avata">-->
<!--                                </div>-->
<!--                                <div class="des">-->
<!--                                    "Your product needs to improve more. To suit the needs and update your image up"-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="item ">-->
<!--                                <strong class="name">Roverto & Maria</strong>-->
<!--                                <div class="avata">-->
<!--                                    <img src="http://placehold.it/102x102" alt="avata">-->
<!--                                </div>-->
<!--                                <div class="des">-->
<!--                                    "Your product needs to improve more. To suit the needs and update your image up"-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->


            </div><!-- Sidebar -->

        </div>
    </div>
</main><!-- end MAIN -->

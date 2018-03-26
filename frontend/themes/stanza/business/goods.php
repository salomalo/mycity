<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 * @var $ads \common\models\Ads
 * @var $isGoods boolean
 * @var $isAction boolean
 * @var $isAfisha boolean
 */
use frontend\extensions\FilterCategory\FilterCategory;
use frontend\extensions\StanzaBottomProduct\StanzaBottomProduct;
use frontend\extensions\StanzaLatestBlog\StanzaLatestBlog;
use frontend\extensions\StanzaLinkPager\StanzaLinkPager;
use frontend\extensions\SuperListLinkPager\SuperListLinkPager;
use frontend\themes\stanza\AppAssets;
use yii\helpers\Url;
use yii\widgets\LinkPager;

if ($idCategory) {
    $category = \common\models\ProductCategory::findOne($idCategory);
} else {
    $category = null;
}

$bundle = AppAssets::register($this);
?>

<head>
    <script type="text/javascript">
        var csrfVar = '<?=Yii::$app->request->getCsrfToken()?>';
    </script>
</head>

<div id="banner" class="stripe banner">
    <img src="<?= $bundle->baseUrl . '/images/banner-business.jpg' ?>" width="1920" height="460" alt=""/>
    <div class="bannerText">
        <div class="container">
            <div class="bantitle2 fontsize_122 cl_000000 uppercase bold" style="margin-top: -50px;margin-left: 210px;font-size: 80px;">Большие <span class="cl_ffffff">Скидки</span></div>
            <a href="#" onclick="return false" class="banner_borderbtn bc_000000" style="color: #fff;margin-top: 130px;">Перейти</a>
        </div>
    </div>
</div><!-- ( BANNER END ) -->

<div id="content" class="productPage">
        <div class="container">
            <div class="topSection">
                <div class="breadcrumbRow clearfix">
                    <div class="row">
                        <div class="col-xs-12 col-sm-5 text-xs-center">
                            <?php if ($category) : ?>
                                <h2><?= $category->title ?></h2>
                            <?php else: ?>
                                <h2>Магазин</h2>
                            <?php endif; ?>
                        </div>
                        <div class="col-xs-12 col-sm-7">
                            <div class="breadcrumb">
                                <ul class="clearfix text-right text-xs-center">
                                    <li><a href="<?= Url::to(['/business/view', 'alias' => "{$model->id}-{$model->url}"]) ?>"><?= $model->title ?></a></li>
                                    <li>Магазин</li>
                                </ul>
                            </div><!-- ( BREAD CRUMB END ) -->
                        </div>
                    </div>

                </div><!-- ( BREAD CRUMB ROW END ) -->
                <div class="sorting clearfix">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-6 text-xs-center">
                            <span class="filterBTN"><a href="#_" class="toggleNav"><i class="fa fa-bars"></i><span>Фильтр категорий</span></a></span>

                            <?= FilterCategory::widget(['businessModel' => $model]) ?>
                        </div>
                        <div class="col-xs-12 col-sm-8 col-md-6">
<!--                            <div id="selectDropdown" class="selectDropdown style1 floatRight" tabindex="1">-->
<!--                                <span>Последние</span>-->
<!--                                <ul class="dropdown">-->
<!--                                    <li><a href="#">Price High to Low</a></li>-->
<!--                                    <li><a href="#">Price Low to High</a></li>-->
<!--                                </ul>-->
<!--                            </div>-->
                        </div>
                    </div>
                </div><!-- ( SORTING END ) -->
            </div><!-- ( TOP SECTION END ) -->

            <div class="stripe">
                <div class="productsRow row">
                    <?php foreach ($ads as $ad) : ?>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?= $this->render('view/_short_ads', ['model' => $ad, 'business' => $model, 'alias' => 'goods']) ?>
                        </div>
                    <?php endforeach; ?>
                </div><!-- ( PRODUCTS ROW END ) -->

                <div class="text-center">
                    <nav class="navigation pagination" role="navigation">
                        <?= isset($pages) ? StanzaLinkPager::widget([
                            'pagination' => $pages,
                            'maxButtonCount' => 7,
                            'activePageCssClass' => 'page-numbers current',
                            'options' => ['class' => 'nav-links'],
                            'linkOptions' => ['class' => 'next page-numbers'],
                        ]) : ''; ?>
                    </nav>
                </div>
            </div><!-- ( STRIPE END ) -->
        </div>

        <?= $this->render('view/new_products', ['model' => $model, 'alias' => 'new']) ?>


        <?= StanzaBottomProduct::widget(['businessModel' => $model]) ?>
    </div><!-- ( CONTENT END ) -->

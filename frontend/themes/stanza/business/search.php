<?php
/**
 * @var $this \yii\web\View
 * @var $models \common\models\Ads[]
 * @var $business \common\models\Business
 * @var $pages \yii\data\Pagination
 * @var $search string
 */


use common\models\Lang;
use frontend\extensions\StanzaBottomProduct\StanzaBottomProduct;
use frontend\extensions\StanzaLinkPager\StanzaLinkPager;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

?>
<div class="innerHeading bg_f1f1f1 innerHeading-border stripeM">
    <div class="container text-center">
        <h1 class="marginBottomNone">Результаты поиска: “<?= $search ?>”</h1>
        <?php if (isset($this->context->breadcrumbs) and ($breadcrumbs = $this->context->breadcrumbs)) : ?>
            <?= Breadcrumbs::widget([
                'tag' => 'ol',
                'options' => ['class' => 'breadcrumb'],
                'homeLink' => false,
                'links' => $breadcrumbs,
            ]); ?>
        <?php endif; ?>
    </div>
</div><!-- ( INNER HEADING END ) -->


<div id="content" class="productPage">
    <div class="container">
        <div class="topSection">
            <div class="breadcrumbRow clearfix">
                <div class="row">
                    <div class="col-xs-12 col-sm-5 text-xs-center">
                        <?php if ($models): ?>
                            <h2>Результаты поиска: “<?= $search ?>”</h2>
                        <?php else: ?>
                            <h2>По запросу: “<?= $search ?>” ничего не найдено</h2>
                        <?php endif; ?>

                    </div>
                    <div class="col-xs-12 col-sm-7">
                        <div class="breadcrumb">
                            <ul class="clearfix text-right text-xs-center">
                                <li>Showing all 5 results</li>
                            </ul>
                        </div><!-- ( BREAD CRUMB END ) -->
                    </div>
                </div>

            </div><!-- ( BREAD CRUMB ROW END ) -->
            <div class="sorting clearfix">
                <div class="row">
                    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-4 col-md-offset-6">
                        <div id="selectDropdown" class="selectDropdown style1 floatRight" tabindex="1">
                            <span>Последние</span>
                            <ul class="dropdown">
                                <li><a href="#">Price High to Low</a></li>
                                <li><a href="#">Price Low to High</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div><!-- ( SORTING END ) -->
        </div><!-- ( TOP SECTION END ) -->

        <div class="stripe">
            <div class="productsRow row">
                <?php foreach ($models as $ad) : ?>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <?= $this->render('view/_short_ads', ['model' => $ad, 'business' => $business, 'alias' => 'search']) ?>
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

    <?= StanzaBottomProduct::widget(['businessModel' => $business]) ?>
</div><!-- ( CONTENT END ) -->
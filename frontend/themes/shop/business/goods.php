<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Business
 * @var $ads \common\models\Ads
 * @var $isGoods boolean
 * @var $isAction boolean
 * @var $isAfisha boolean
 */

use common\models\Lang;
use common\models\ProductCategory;
use frontend\extensions\AdBlock;
use frontend\extensions\BlockListProduct\BlockListProduct;
use frontend\extensions\SuperListLinkPager\SuperListLinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$cur_lang = Lang::getCurrent()->url;
$canonical = str_replace('/site', '', Url::current([], 'http'));

$cf = [];
if ($model->customFieldValues) {
    foreach ($model->customFieldValues as $item) {
        $cf[$item->customField->title][] = $item->anyValue;
    }
    foreach ($cf as &$item) {
        $item = implode(', ', $item);
    }
}

$homeTitle = ($city = Yii::$app->request->city) ? Yii::t('app', 'site_of_the_city_of_{cityTitle}', ['cityTitle' => $city->title_ge]) : Yii::t('app', 'Home');
$main = ($canonical === Url::to($cur_lang, true)) ? null : Url::to($cur_lang, true);
?>

<head>
    <script type="text/javascript">
        var csrfVar = '<?=Yii::$app->request->getCsrfToken()?>';
    </script>
</head>

<div class="main">
    <div class="main-inner">
        <div class="container" style="">
            <div class="row">
                <!-- Slider -->
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                    <!-- Указатели -->
                    <ol class="carousel-indicators">
                        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="3"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="4"></li>
                    </ol>
                    <!-- Контент слайда (slider wrap)-->
                    <div class="carousel-inner" style="max-height: 350px !important;">
                        <div class="item active">
                            <div class="fig">
                                <img src="/img/shop/shop_1.jpg" alt="...">
                                <div class="carousel-caption">
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="fig">
                                <img src="/img/shop/shop_2.jpg" alt="...">
                                <div class="carousel-caption">
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="fig">
                                <img src="/img/shop/shop_3.jpg" alt="...">
                                <div class="carousel-caption">
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="fig">
                                <img src="/img/shop/shop_4.jpg" alt="...">
                                <div class="carousel-caption">
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="fig">
                                <img src="/img/shop/shop_5.jpg" alt="...">
                                <div class="carousel-caption">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Элементы управления -->
                    <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 col-lg-9">
                    <div id="primary">
                        <div class="document-title">
                            <?php if (isset($this->context->breadcrumbs) and ($breadcrumbs = $this->context->breadcrumbs)) : ?>
                                <?= Breadcrumbs::widget([
                                    'tag' => 'ol',
                                    'options' => ['class' => 'breadcrumb'],
                                    'homeLink' => false,
                                    'links' => $breadcrumbs,
                                ]); ?>
                            <?php endif; ?>
                            <h1 style="margin-top: 15px">
                                <?= $ads ? 'Каталог товаров' : 'Нету товаров в данной категории' ?>
                            </h1>
                        </div>

                        <div class="listings-row" style="margin-bottom: 25px;">

                            <?php if (!empty($ads)) : ?>
                                <div class="b-product-line b-product-line_size_wide js-gallery-container">
                                    <?php foreach ($ads as $ad) : ?>
                                        <?= $this->render('view/_short_ads', ['model' => $ad]) ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif;?>

                        </div>

                        <div style="margin-bottom: 50px">
                            <nav class="navigation pagination" role="navigation">
                                <?= isset($pages) ? SuperListLinkPager::widget([
                                    'pagination' => $pages,
                                    'maxButtonCount' => 6,
                                    'activePageCssClass' => 'page-numbers current',
                                    'options' => ['class' => 'nav-links'],
                                    'linkOptions' => [
                                        'class' => 'next page-numbers',
                                    ],
                                ]) : ''; ?>
                            </nav>
                        </div>

                    </div>
                </div>
                <?= $this->render('view/right_col', ['model' => $model, 'pid' => $idCategory]) ?>
            </div>
        </div>
    </div>
</div>
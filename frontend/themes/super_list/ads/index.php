<?php
/**
 * @var $models \common\models\Ads[]
 * @var $pages yii\data\Pagination
 * @var $pid integer
 * @var $category ProductCategory
 */

use common\models\Business;
use common\models\Lang;
use common\models\ProductCategory;
use frontend\extensions\AdBlock;
use frontend\extensions\BlockListProduct\BlockListProduct;
use frontend\extensions\SuperListLinkPager\SuperListLinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$pid = $this->context->alias_category;
$cur_lang = Lang::getCurrent()->url;

$canonical = str_replace('/site', '', Url::current([], 'http'));

$homeTitle = ($city = Yii::$app->request->city) ? Yii::t('app', 'site_of_the_city_of_{cityTitle}', ['cityTitle' => $city->title_ge]) : Yii::t('app', 'Home');
$main = ($canonical === Url::to($cur_lang, true)) ? null : Url::to($cur_lang, true);
$city = Yii::$app->request->city;
?>
<head>
    <script type="text/javascript">
        var csrfVar = '<?=Yii::$app->request->getCsrfToken()?>';
    </script>
</head>

<div class="main">
    <div class="main-inner">
        <div class="container">
            <div class="row">
                <?php if (isset($this->context->breadcrumbs) and ($breadcrumbs = $this->context->breadcrumbs)) : ?>
                    <?= Breadcrumbs::widget([
                        'tag' => 'ol',
                        'options' => ['class' => 'breadcrumb'],
                        'homeLink' => ['label' => $homeTitle, 'url' => $main],
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

                <div class="col-sm-8 col-lg-9">
                    <div id="primary">
                        <div class="top-block-a"><?= AdBlock::getTop() ?></div>

                        <div class="document-title" style="margin-top: 20px;">
                            <h1><?= $city ? Yii::t('ads', 'seo_title', ['cityTitle' => $city->title]) : Yii::t('ads', 'seo_title_no_city') ?></h1>
                        </div>

                        <?= $this->render('_search_form', ['pid' => $pid]) ?>

                        <div class="b-product-line b-product-line_size_wide js-gallery-container">
                            <?php foreach ($models as $model) : ?>
                                <?= $this->render('_ads_short', ['model' => $model]) ?>
                            <?php endforeach; ?>
                        </div>

                        <div style="margin-bottom: 50px">
                            <nav class="navigation pagination" role="navigation">
                                <?= isset($pages) ? SuperListLinkPager::widget([
                                    'pagination' => $pages,
                                    'maxButtonCount' => 7,
                                    'activePageCssClass' => 'page-numbers current',
                                    'options' => ['class' => 'nav-links'],
                                    'linkOptions' => ['class' => 'next page-numbers'],
                                ]) : ''; ?>
                            </nav>
                        </div>

                        <?php if ($category) : ?>
                            <div id="text-description-page" class="text-description-page">
                                <div class="body-layout">
                                    <div class="wrap">
                                        <div id="short_text" class="text-description-content box-hide">
                                            <div style="text-align: justify;">
                                                <?= $category->description ?>
                                            </div>
                                            <div  id="hide-description-product-category" style="text-align: justify;" class="hidden">
                                                <?= $category->hide_description ?>
                                            </div>
                                        </div>
                                        
                                        <?php if ($category->hide_description) : ?>
                                            <div class="text-description-more">
                                                <a href="#" id="short_text_show_link"
                                                   class="novisited arrow-link text-description-more-link">
                                                    <span class="xhr arrow-link-inner">Читать полностью</span>&nbsp;→
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

                <div class="col-sm-4 col-lg-3">
                    <div id="secondary" class="secondary sidebar">
                        <div class="quad-block-a"><?= AdBlock::getQuad() ?></div>

                        <?= BlockListProduct::widget([
                            'title' => Yii::t('ads', 'Categories_ad'),
                            'className' => ProductCategory::className(),
                            'attribute' => 'pid',
                            'template' => 'super_list',
                            'path' => 'ads/index',
                            'id_category' => $this->context->id_category,
                        ]) ?>

                        <div class="left-block-a"><?= AdBlock::getLeft() ?></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div>
    <div class="bgl-overlay hidden" style="z-index: 90000; display: block;" data-reactid=".i">
        <div class="bgl-overlay__dialog" data-qaid="popup-overlay" data-reactid=".i.0">
            <div class="bgl-overlay__close-button" data-qaid="close-btn" data-reactid=".i.0.0"></div>
            <div class="bgl-overlay-phones" data-reactid=".i.0.1">
                <div class="bgl-overlay-title qa-overlay-title" data-reactid=".i.0.1.0">Свяжитесь с продавцом
                </div>
                <div id="bgl-overlay-seller-phone">

                </div>
            </div>
        </div>
    </div>
</div>

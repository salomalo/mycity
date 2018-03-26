<?php
/**
 * @var $this View
 * @var $pages Pagination
 * @var $category ProductCategory
 * @var $models \common\models\Product[]
 */

use common\models\Lang;
use common\models\ProductCategory;
use frontend\extensions\AdBlock;
use frontend\extensions\BlockListProduct\BlockListProduct;
use frontend\extensions\SuperListLinkPager\SuperListLinkPager;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Breadcrumbs;

$cur_lang = Lang::getCurrent()->url;

$canonical = str_replace('/site', '', Url::current([], 'http'));

$homeTitle = ($city = Yii::$app->request->city) ? Yii::t('app', 'site_of_the_city_of_{cityTitle}', ['cityTitle' => $city->title_ge]) : Yii::t('app', 'Home');
$main = ($canonical === Url::to($cur_lang, true)) ? null : Url::to($cur_lang, true);

$subDomain = ArrayHelper::getValue(Yii::$app->params, 'SUBDOMAINTITLE', '');
?>

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

                        <div class="document-title">
                            <div class="top-block-a"><?= AdBlock::getTop() ?></div>

                            <h2><?= ($category ? $category->title : null) ?> <?= !empty($subDomain) ? 'в '.$subDomain : '' ?></h2>
                        </div>

                        <?= $this->render('_search_form', ['pid' => ($category ? $category->id : null)]) ?>

                        <div class="listings-row">

                            <?php if (!empty($models)) : ?>
                                <?php foreach ($models as $model) : ?>
                                    <?= $this->render('_product_short',['model' => $model]) ?>
                                <?php endforeach; ?>
                            <?php endif;?>
                        </div>

                        <div style="margin-bottom: 50px">
                            <nav class="navigation pagination" role="navigation">
                                <?= SuperListLinkPager::widget([
                                    'pagination' => $pages,
                                    'maxButtonCount' => 7,
                                    'activePageCssClass' => 'page-numbers current',
                                    'options' => ['class' => 'nav-links'],
                                    'linkOptions' => ['class' => 'next page-numbers'],
                                ]) ?>
                            </nav>
                        </div>

                    </div>
                </div>

                <div class="col-sm-4 col-lg-3">
                    <div id="secondary" class="secondary sidebar">
                        <div class="quad-block-a"><?= AdBlock::getQuad() ?></div>

                        <?= BlockListProduct::widget([
                            'title' => Yii::t('product', 'Products_Categories'),
                            'className' => ProductCategory::className(),
                            'attribute' => 'pid',
                            'template' => 'super_list',
                            'path' => 'product/index',
                            'id_category' => ($category ? $category->id : null),
                        ]) ?>

                        <div class="left-block-a"><?= AdBlock::getLeft() ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
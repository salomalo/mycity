<?php
/**
 * @var $models \common\models\Ads
 * @var $pages yii\data\Pagination
 * @var $pid integer
 */

use common\models\Lang;
use common\models\ProductCategory;
use frontend\extensions\AdBlock;
use frontend\extensions\BlockListProduct\BlockListProduct;
use frontend\extensions\SuperListLinkPager\SuperListLinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

//$pid = $this->context->alias_category;
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

                <div class="col-sm-8 col-lg-9">
                    <div id="primary">
                        <div class="top-block-a"><?= AdBlock::getTop() ?></div>

                        <div class="document-title" style="margin-top: 20px;">
                            <h1>
                                Товары в категории <?= $categoryTitle ?>
                                <?= Html::a('Список всех товаров', ['/business/goods', 'alias' => "{$business->id}-{$business->url}"], ['class' => 'small-link']) ?>
                            </h1>
                        </div>

                        <div class="listings-row">
                            <?php if (!empty($models)) : ?>
                                <?php foreach ($models as $model) : ?>
                                    <?= $this->render('_ads_short', ['model' => $model, 'business' => $business]) ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <?= Yii::t('ads', 'No_ads_in_this_category') ?>
                            <?php endif; ?>

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

                    </div>
                </div>

                <?= $this->render('view/right_col', ['model' => $business, 'pid' => $pid]) ?>

            </div>
        </div>
    </div>
</div>


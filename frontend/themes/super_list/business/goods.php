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
use frontend\extensions\AdBlock;
use frontend\extensions\BlockListAds\BlockListAds;
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

<?= $this->render('view/simple_banner', ['model' => $model, 'backgroundDisplay' => $backgroundDisplay]) ?>
<?= $this->render('view/detail_menu', ['model' => $model, 'enabled' => [
    'detail' => true,
    'attributes' => !!$cf,
    'goods' => $isGoods,
    'action' => $isAction,
    'afisha' => $isAfisha,
]]) ?>

<div class="main">
    <div class="main-inner">
        <div class="container">
            <div class="row">

                <div class="col-sm-8 col-lg-9">
                    <div id="primary">
                        <div class="document-title" style="margin-top: 15px">

                            <div class="top-block-a"><?= AdBlock::getTop() ?></div>

                            <h1>
                                <?= Yii::t('ads', 'seo_title_no_city') ?>
                            </h1>
                        </div>

                        <div class="listings-row" style="margin-bottom: 25px;">

                            <?php if (!empty($ads)) : ?>
                                <?php foreach ($ads as $ad) : ?>
                                    <?= $this->render('_ads_short', ['model' => $ad]) ?>
                                <?php endforeach; ?>
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

                <div class="col-sm-4 col-lg-3">
                    <div id="secondary" class="secondary sidebar">
                        <?= BlockListAds::widget([
                            'template' => 'super_list_goods',
                            'business' => $model,
                            'idCategory' => $idCategory,
                        ]) ?>
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
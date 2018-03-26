<?php
use common\extensions\BusinessCustomField\CustomFieldFilter;
use common\extensions\MultiView\MultiView;
use common\models\BusinessAddress;
use common\models\BusinessCategory;
use common\models\Lang;
use frontend\extensions\AdBlock;
use frontend\extensions\BlockListNested\BlockListNested;
use frontend\extensions\SuperListLinkPager\SuperListLinkPager;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/**
 * @var $pages \yii\data\Pagination
 * @var $models \common\models\Business[]
 *
 * @var $top false
 * @var $isSelect false
 *
 * @var $listAddress array
 *
 * @var $search string
 * @var $pid string
 * @var $start_time string
 * @var $end_time string
 * @var $weekDay string
 * @var $activeTab string
 * @var $views array
 *
 * @var $titleCategory string|null
 */

$pid = $this->context->alias_category;

$start_time = Yii::$app->request->post('start_time', '00:00');
$end_time = Yii::$app->request->post('end_time', '00:00');
$weekDay = Yii::$app->request->post('weekDay', '');
$activeTab = Yii::$app->session->get('viewBusiness');

$top = false;
$isSelect = false;

$cur_lang = Lang::getCurrent()->url;
$alt_lang = Lang::getAlternate()->url;

$canonical = str_replace('/site', '', Url::current([], 'http'));
$alt_canonical = str_replace("/$cur_lang/", "/$alt_lang/", $canonical);

$listAddress = $this->context->listAddress;

$homeTitle = ($city = Yii::$app->request->city) ? Yii::t('app', 'site_of_the_city_of_{cityTitle}', ['cityTitle' => $city->title_ge]) : Yii::t('app', 'Home');
$main = ($canonical === Url::to($cur_lang, true)) ? null : Url::to($cur_lang, true);
?>
<head>
    <script type="text/javascript">
        var csrfVar = '<?=Yii::$app->request->getCsrfToken()?>';
        var idCategory = '<?= isset($idCategory) ? $idCategory : ''?>';
    </script>
</head>
<div class="main">
    <div id="google-map-4" class="widget widget_google-map">
        <div class="widget-inner">

            <div class="map-wrapper">
                <div class="map-inner ">
                    <div class="mapescape-map-wrapper">
                        
                        <div class="map-google">
                            <div class="map-google-inner" style="height: 340px">
                                <?= MultiView::widget([
                                    '_view' => '_address_map',
                                    //'data' => $listAddress,
                                    'relModelName' => BusinessAddress::className(),
                                ]); ?>
                             </div>
    
                            <div class="map-switch"><?=Yii::t('business', 'Toggle Map') ?></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


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

                            <h1>
                                <?php if (!$titleCategory) : ?>
                                    <?= !$top ?
                                        Yii::t('business', 'Directory_of_municipal_services_ge', ['city' => Yii::$app->params['SUBDOMAINTITLE']])
                                        : Yii::t('business', 'Top_100_firm_city_ge', ['city' => Yii::$app->params['SUBDOMAIN_TITLE_GE']])
                                    ?>
                                <?php else : ?>
                                    <?= Yii::t('business', 'Business_ge_{titleCategory}_{cityTitle}', [
                                        'titleCategory' => $titleCategory,
                                        'cityTitle' => (!empty(Yii::$app->params['SUBDOMAIN_TITLE_GE'])) ? Yii::$app->params['SUBDOMAIN_TITLE_GE'] : ''
                                    ]) ?>
                                <?php endif; ?>
                            </h1>
                        </div>

                        <?= $this->render('_search_form', ['pid' => $pid]) ?>

                        <div class="listings-row">
                            <?php if (!empty($models)) : ?>
                                <?php foreach ($models as $model) : ?>
                                    <?= $this->render('_business_short', [
                                        'model' => $model,
                                        'view' => isset($views[$model->id]) ? $views[$model->id] : 0,
                                    ]) ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <?= Yii::t('business', 'Not Found') ?>
                            <?php endif; ?>
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
                        <div class="quad-block-a"><?= AdBlock::getQuad() ?></div>

                        <div class="widget widget_listing_author">
                            <?= CustomFieldFilter::widget([
                                'category' => ArrayHelper::getValue($this->context, 'id_category', null),
                                'attributes' => ArrayHelper::getValue($this->context, 'cf_values', null)
                            ]) ?>
                        </div>

                        <?= BlockListNested::widget([
                            'title' => Yii::t('business', 'Categories_enterprises'),
                            'className' => BusinessCategory::className(),
                            'attribute' => 'pid',
                            'template' => 'super_list',
                            'id_category' => ArrayHelper::getValue($this->context, 'id_category', null),
                        ]) ?>

                        <div class="left-block-a"><?= AdBlock::getLeft() ?></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
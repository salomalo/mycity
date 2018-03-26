<?php
/**
 * @var $this \yii\web\View
 * @var $id_city integer
 * @var $page integer
 * @var $pages
 * @var $listAddress array
 * @var $searchName string
 * @var $search string
 * @var $modelResult \yii\db\ActiveRecord|\yii\mongodb\ActiveRecord
 */

use common\extensions\MultiView\MultiView;
use common\models\BusinessAddress;
use common\models\Lang;
use frontend\extensions\AdBlock;
use frontend\extensions\SphinxSearchForm\SphinxSearchForm;
use frontend\extensions\SuperListLinkPager\SuperListLinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
$this->title = 'Поиск ' .  $searchName;
$city = Yii::$app->request->city;
$cur_lang = Lang::getCurrent()->url;
$canonical = str_replace('/site', '', Url::current([], 'http'));

$homeTitle = ($city = Yii::$app->request->city) ? Yii::t('app', 'site_of_the_city_of_{cityTitle}', ['cityTitle' => $city->title_ge]) : Yii::t('app', 'Home');
$main = ($canonical === Url::to($cur_lang, true)) ? null : Url::to($cur_lang, true);
$main_string = ($canonical === Url::to($cur_lang, true)) ? null : '/';
?>

<div class="main">
    <?php if (!empty($listAddress)) : ?>
        <div id="google-map-4" class="widget widget_google-map">
            <div class="widget-inner">
    
                <div class="map-wrapper">
                    <div class="map-inner ">
                        <div class="mapescape-map-wrapper">
    
                            <div class="map-google">
                                <div class="map-google-inner" style="height: 340px">
                                    <?= MultiView::widget([
                                        '_view' => '_address_map',
                                        'data' => $listAddress,
                                        'relModelName' => BusinessAddress::className(),
                                    ]); ?>
                                </div>
    
                                <div class="map-switch"><?=Yii::t('business', 'Toggle Map') ?></div>
                            </div>
                        </div>
    
                        <div class="map-content">
                            <div class="container">
    
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="map-actions">
                                            <div class="map-actions-group">
                                                <a id="map-control-type-roadmap"><span><?=Yii::t('business', 'Map_Roadmap') ?></span></a>
                                                <a id="map-control-type-terrain"><span><?=Yii::t('business', 'Map_Terrain') ?></span></a>
                                                <a id="map-control-type-satellite"><span><?=Yii::t('business', 'Map_Satellite') ?></span></a>
                                            </div>
    
                                            <div class="map-actions-group">
                                                <a id="map-control-zoom-in"><span><?=Yii::t('business', 'Map_Zoom_In') ?></span></a>
                                                <a id="map-control-zoom-out"><span><?=Yii::t('business', 'Map_Zoom_Out') ?></span></a>
                                            </div>
    
                                            <div class="map-actions-group">
                                                <a id="map-control-current-position"><span><?=Yii::t('business', 'Map_Current_Position') ?></span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
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
                            
                            <h1>Поиск <?= $searchName ?></h1>
                        </div>

                        <div class="widget widget_filer" id="widget-search-top">
                            <div class="widget-inner widget-pb">
                                <?= SphinxSearchForm::widget([
                                    'search' => $search,
                                    'id_city' => $id_city,
                                    'type' => $type,
                                    'page' => $page,
                                ]) ?>
                            </div>
                        </div>

                        <div class="listings-row">
                            <?php foreach ($modelResult as $model) : ?>
                                <?= $this->render($model->searchShortView, [
                                    'model' => $model,
                                    'pid' => null,
                                    'showCat' => false,
                                    'time' => null,
                                    'showCatFilm' => 1,
                                    'idCity' => $id_city,
                                    'date' => date("Y-m-d"),
                                ]) ?>
                            <?php endforeach; ?>
                        </div>

                        <?php if ($pages): ?>
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
                        <?php endif; ?>

                    </div>
                </div>

                <div class="col-sm-4 col-lg-3">
                    <div class="secondary sidebar">
                        <div class="quad-block-a"><?= AdBlock::getQuad() ?></div>

                        <div class="left-block-a"><?= AdBlock::getLeft() ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
/**
 * @var $this \yii\web\View
 * @var $models Action[]
 */

use common\models\Action;
use common\models\Lang;
use frontend\extensions\AdBlock;
use frontend\extensions\BlockListAction\BlockListAction;
use frontend\extensions\Calendar\Calendar;
use frontend\extensions\SuperListLinkPager\SuperListLinkPager;
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Breadcrumbs;

/**
 * @var \yii\data\Pagination $pages
 * @var \common\models\Action[] $models
 * @var bool $archive
 * @var string $time
 * @var string $titleCategory
 * @var string $pid
 * @var string $url
 * @var string $search
 */
$city = Yii::$app->request->city;
$cur_lang = Lang::getCurrent()->url;

$canonical = str_replace('/site', '', Url::current([], 'http'));

$homeTitle = ($city = Yii::$app->request->city) ? Yii::t('app', 'site_of_the_city_of_{cityTitle}', ['cityTitle' => $city->title_ge]) : Yii::t('app', 'Home');
$main = ($canonical === Url::to($cur_lang, true)) ? null : Url::to($cur_lang, true);
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
                <?php endif; ?>

                <div class="col-sm-8 col-lg-9">
                    <div id="primary">
                        <div class="document-title">
                            <div class="top-block-a"><?= AdBlock::getTop() ?></div>

                            <h1>
                                <?php if ($city) : ?>
                                    <?= Yii::t('action', 'Action {city_ge}', ['city_ge' => $city->title_ge]) ?>
                                <?php else : ?>
                                    <?= Yii::t('action', 'Action Ukraine') ?>
                                <?php endif; ?>
                            </h1>
                        </div>

                        <?= $this->render('_search_form_action.php', ['pid' => null]) ?>

                        <div class="listings-row">

                            <?php if (!empty($models)) : ?>
                                <?php foreach ($models as $model) : ?>
                                    <?= $this->render('_action_short', ['model' => $model, 'pid' => $pid]) ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <?= Yii::t('action', 'Not Found') ?>
                            <?php endif; ?>

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
                    <div class="secondary sidebar">
                        <div class="quad-block-a"><?= AdBlock::getQuad() ?></div>

                        <?= BlockListAction::widget([
                            'sort' => !empty($this->context->sotrTime) ? 'time' : null,
                            'archive' => !empty($this->context->archive) ? 1 : null,
                            'sortTime' => !empty($this->context->time) ? $this->context->time : null,
                            'filter' => true,
                            'template' => 'index_super_list',
                        ]); ?>

                        <div class="block">
                            <?= Calendar::widget([
                                'title' => Yii::t('action', 'Calendar_of_shares'),
                                'model' => Action::className(),
                                'idCity' => ArrayHelper::getValue(Yii::$app->params, 'SUBDOMAINID', null),
                                'path' => 'action/index',
                                'idCategory' => defined('CATEGORYID') ? CATEGORYID : null,
                                'businesList' => !empty($this->context->listCityBusiness) ? $this->context->listCityBusiness : [],
                                'selectDate' => !empty($this->context->date) ? $this->context->date : date('Y-m-d'),
                                'sortTime' => !empty($this->context->sotrTime) ? 'time' : null,
                                'isSelectedDay' => !empty($this->context->time) ? true : false,
                            ]) ?>
                        </div>

                        <div class="left-block-a"><?= AdBlock::getLeft() ?></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
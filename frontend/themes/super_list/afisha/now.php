<?php
/**
 * @var $models common\models\Afisha[]
 * @var $this \yii\web\View
 * @var \yii\data\Pagination $pages
 * @var string $titleCategory
 * @var bool $forToday
 * @var \common\models\Business[] $business
 */

use common\models\Afisha;
use common\models\AfishaCategory;
use common\models\Lang;
use frontend\extensions\AdBlock;
use frontend\extensions\BlockListAfisha\BlockListAfisha;
use frontend\extensions\Calendar\Calendar;
use frontend\extensions\GenreSelect\GenreSelect;
use frontend\extensions\SuperListLinkPager\SuperListLinkPager;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
use yii\widgets\Breadcrumbs;

$alias = ($this->context->aliasCategory)? $this->context->aliasCategory : null;
$action = Yii::$app->controller->action->id;
$archive = Yii::$app->request->get('archive', '');

$pid = ($this->context->aliasCategory)? $this->context->aliasCategory : null;
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
                                <?= $titleCategory ?>
                                <?php if (Yii::$app->controller->action->id  === 'index') : ?>
                                    <?= 'на ', ($forToday ? (Yii::t('afisha', 'Poster_today')) : ''); ?>
                                    <?= ' ', $this->context->date ?>
                                <?php endif; ?>
                            </h1>
                        </div>

                        <?= $this->render('_search_form.php', ['options' => [
                            'action' => 'afisha',
                            'genre' => $this->context->genre,
                            'archive' => $archive,
                            'pid' => $pid,
                            'index' => ['pid' => null, 'url' => '/vacation/index', 'class' => 'active'],
                        ]]) ?>

                        <div class="listings-row">
                            <?php foreach ($models as $model) : ?>
                                <?php
                                $company = null;
                                if ($model->idsCompany and isset($model->idsCompany[0]) and isset($business[$model->idsCompany[0]])) {
                                    $company = $business[$model->idsCompany[0]];
                                }
                                ?>
                                <?= $this->render('_afisha_now_short', [
                                    'company' => $company,
                                    'model' => $model,
                                    'date' => $this->context->date,
                                    'time' => null,
                                ]) ?>
                            <?php endforeach; ?>
                        </div>

                        <div style="margin-bottom: 50px">
                            <nav class="navigation pagination" role="navigation">
                                <?= ($pages) ? SuperListLinkPager::widget([
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
                <div class="col-sm-4 col-lg-3">
                    <div class="secondary sidebar my-filter-afisha">
                        <div class="quad-block-a"><?= AdBlock::getQuad() ?></div>

                        <?php if(!$this->context->isFilm): ?>
                            <?= BlockListAfisha::widget([
                                'title' => Yii::t('afisha', 'Сategory_Posters'),
                                'className' => AfishaCategory::className(),
                                'path' => 'afisha/' . ((Yii::$app->controller->action->id === 'view') ?
                                        'index' : Yii::$app->controller->action->id),
                                'attribute' => 'pid',
                                'template' => 'super_list',
                                'id_category' =>$this->context->idCategory,
                                'archive' => $this->context->archive  ? 'archive' : false,
                                'forWeek' => $this->context->forWeek,
                            ]) ?>
                        <?php else:?>
                            <?= GenreSelect::widget([
                                'pid'=>$this->context->idCategory,
                                'active' => $this->context->genre,
                                'filter' => true,
                                'template' => 'super_list',
                                'date' => $this->context->date,
                                'archive' => $this->context->archive ? 'archive' : false,
                                'forWeek' => $this->context->forWeek,
                            ]) ?>
                        <?php endif;?>

                        <?= Calendar::widget([
                            'title' => Yii::t('afisha', 'Calendar_billboard'),
                            'model' => Afisha::className(),
                            'isFilm' => $this->context->isFilm,
                            'idCompanyIsArray' => true,
                            'idCity' => ArrayHelper::getValue(Yii::$app->params, 'SUBDOMAINID', null),
                            'path' => '/afisha/index',
                            'idCategory' => (defined('CATEGORYID')) ? CATEGORYID : null,
                            'businesList' => $this->context->listCityBusiness,
                            'selectDate' => $this->context->date,
                            'isSelectedDay' => $this->context->time ? true : false,
                        ]) ?>

                        <div class="left-block-a"><?= AdBlock::getLeft() ?></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php
/**
 * @var $this \yii\web\View
 * @var $models \common\models\WorkVacantion[]
 */

use common\models\Lang;
use frontend\extensions\AdBlock;
use frontend\extensions\BlockListPost\BlockListPost;
use frontend\extensions\SuperListLinkPager\SuperListLinkPager;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;


$pid = $this->context->alias_category;
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
                                <?php if (!$titleCategory) : ?>
                                    <?= Yii::t('vacantion', 'All_jobs_in_city_ge', ['cityTitle' => (!empty(Yii::$app->params['SUBDOMAIN_TITLE_GE'])) ? Yii::$app->params['SUBDOMAIN_TITLE_GE'] : '']) ?>
                                <?php else : ?>
                                    <?= Yii::t('vacantion', 'Jobs_ge_{titleCategory}_{cityTitle}', [
                                        'titleCategory' => $titleCategory,
                                        'cityTitle' => (!empty(Yii::$app->params['SUBDOMAIN_TITLE_GE']))? Yii::$app->params['SUBDOMAIN_TITLE_GE'] : ''
                                    ])?>
                                <?php endif; ?>
                            </h1>
                        </div>

                        <?= $this->render('_search_form.php', ['options' => [
                            'action' => 'vacantion',
                            'pid' => $pid,
                            'id_category' => ArrayHelper::getValue($this->context, 'id_category', null),
                            'index' => ['pid' => null, 'url' => '/vacation/index', 'class' => 'active'],
                        ]]) ?>


                        <div class="listings-row">

                            <?php if (!empty($models)) : ?>
                                <?php foreach ($models as $model) : ?>
                                    <?= $this->render('_vacantion_short', ['model' => $model]) ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <?= Yii::t('business', 'Not Found') ?>
                            <?php endif; ?>

                        </div>

                        <div style="margin-bottom: 50px">
                            <nav class="navigation pagination" role="navigation">
                                <?= isset($pages) ? SuperListLinkPager::widget([
                                    'pagination' => $pages,
                                    'maxButtonCount' => 7,
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
                    <div class="secondary sidebar">
                        <div class="quad-block-a"><?= AdBlock::getQuad() ?></div>

                        <?= BlockListPost::widget([
                            'title' => Yii::t('vacantion', 'Categories_of_work'),
                            'className' => 'common\models\WorkCategory',
                            'template' => 'super_list',
                            'attribute' => 'pid',
                            'path' => 'vacantion/index',
                        ]) ?>

                        <div class="left-block-a"><?= AdBlock::getLeft() ?></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



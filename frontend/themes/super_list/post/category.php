<?php
/**
 * @var $models \common\models\Post[]
 * @var $pages \yii\data\Pagination
 */

use common\models\Lang;
use frontend\extensions\AdBlock;
use frontend\extensions\FotoInPost\FotoInPost;
use frontend\extensions\SuperListLinkPager\SuperListLinkPager;
use frontend\extensions\TopNews\TopNews;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$subDomain = ArrayHelper::getValue(Yii::$app->params, 'SUBDOMAIN_TITLE_GE', null);

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
                                <?= empty($subDomain) ? Yii::t('post', 'City_posts_{cityTitle}', ['cityTitle' => $subDomain]) : Yii::t('post', 'Posts') ?>:
                            </h1>
                        </div>

                        <?= $this->render('_search_form.php', ['options' => [
                            'action' => 'post',
                            'pid' => $pid,
                            'id_category' => $id_category,
                            'index' => ['pid' => null, 'url' => '/ads/index', 'class' => 'active'],
                        ]]) ?>

                        <div class="listings-row">
                            <?php foreach ($models as $i => $model) : ?>
                                <?= $this->render('_post_short', ['model' => $model, 'index' => $i]) ?>
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
                    </div>
                </div>

                <div class="col-sm-4 col-lg-3">
                    <div class="secondary sidebar my-filter-afisha">
                        <div class="quad-block-a"><?= AdBlock::getQuad() ?></div>

                        <?= TopNews::widget([
                            'title' => Yii::t('post', 'Top_news'),
                            'idCity' => ArrayHelper::getValue(Yii::$app->params, 'SUBDOMAINID', null),
                        ]) ?>

                        <?= FotoInPost::widget([
                            'titlePhoto' => Yii::t('post', 'Photo_in_news'),
                            'titleVideo' => Yii::t('post', 'Video_in_news'),
                            'idCity' => ArrayHelper::getValue(Yii::$app->params, 'SUBDOMAINID', null),
                            'subdomainCity' => ArrayHelper::getValue(Yii::$app->params, 'SUBDOMAINTITLE', null),
                        ]) ?>

                        <div class="left-block-a"><?= AdBlock::getLeft() ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
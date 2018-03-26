<?php
/**
 * @var \yii\web\View $this
 */

use common\models\City;
use common\models\Lang;
use frontend\assets\AdminLTEAsset;
use frontend\extensions\AdBlock;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

AdminLTEAsset::register($this);

$this->title = Html::encode(Yii::$app->user->identity->existName) . ' - CityLife';
$this->params['breadcrumbs'][] = Html::encode(Yii::$app->user->identity->existName);

$add_label = '<span class="fa fa-plus"></span> ' . Yii::t('app', 'Add');
$list_label = '<span class="fa fa-th-list"></span> ' . Yii::t('app', 'List');

$options = ['class' => 'btn btn-primary', 'target' => '_blank'];

$urlManager = Yii::$app->urlManagerOffice;
$frontend = Yii::$app->params['appFrontend'];
$lang = Lang::getCurrent()->url;

/** @var $cities City[] */
$cities = Yii::$app->params['cities'][City::ACTIVE];

$homeTitle = ($city = Yii::$app->request->city) ? Yii::t('app', 'site_of_the_city_of_{cityTitle}', ['cityTitle' => $city->title_ge]) : Yii::t('app', 'Home');

$cur_lang = Lang::getCurrent()->url;
$canonical = str_replace('/site', '', Url::current([], 'http'));
$main = ($canonical === Url::to($cur_lang, true)) ? null : Url::to($cur_lang, true);
$main_string = ($canonical === Url::to($cur_lang, true)) ? null : '/';

$networksVisible = count(Yii::$app->authClientCollection->clients) > 0;
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
                        <div class="top-block-a"><?= AdBlock::getTop() ?></div>

                        <div class="document-title">
                            <h1>Личный кабинет</h1>
                        </div>


                        <div class="row" style="margin-left: 10px">
                            <?php /* Афиша */ ?>
                            <div class="col-md-6 col-sm-5 col-xs-5">
                                <div class="info-box">
                                    <span class="info-box-icon bg-light-green"><i
                                            class="ion ion-film-marker"></i></span>
                                    <div class="info-box-content">
                                        <span class="my-block-title"><?= Yii::t('afisha', 'Poster') ?></span>
                                        <div class="grid-buttons">
                                            <?= Html::a($add_label, $urlManager->createUrl('afisha/create'), $options) ?>
                                            <?php if (Yii::$app->request->city) : ?>
                                                <?= Html::a($list_label, ['/afisha/index'], $options) ?>
                                            <?php else: ?>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                                            data-toggle="dropdown" aria-expanded="false">
                                                        <?= $list_label ?> <span class="caret"></span>
                                                    </button>

                                                    <ul class="dropdown-menu" role="menu">
                                                        <?php foreach ($cities as $city) : ?>
                                                            <li><?= Html::a($city->title_ge, ("http://{$city->subdomain}.{$frontend}/{$lang}/afisha"), ['target' => '_blank']) ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php /* Акции */ ?>
                            <div class="col-md-6 col-sm-5 col-xs-5">
                                <div class="info-box">
                                    <span class="info-box-icon bg-light-green"><i class="ion ion-bag"></i></span>
                                    <div class="info-box-content">
                                        <span class="my-block-title"><?= Yii::t('action', 'Promotions') ?></span>
                                        <div class="grid-buttons">
                                            <?= Html::a($add_label, $urlManager->createUrl('action/create'), $options) ?>

                                            <?php if (Yii::$app->request->city) : ?>
                                                <?= Html::a($list_label, ['/action/index'], $options) ?>
                                            <?php else: ?>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                                            data-toggle="dropdown" aria-expanded="false">
                                                        <?= $list_label ?> <span class="caret"></span>
                                                    </button>

                                                    <ul class="dropdown-menu" role="menu">
                                                        <?php foreach ($cities as $city) : ?>
                                                            <li><?= Html::a($city->title_ge, ("http://{$city->subdomain}.{$frontend}/{$lang}/action"), ['target' => '_blank']) ?></li>
                                                        <?php endforeach; ?>

                                                        <li class="divider"></li>
                                                        <li><?= Html::a(Yii::t('app', 'of Main'), ['/action/index'], ['target' => '_blank']) ?></li>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php /* Новости */ ?>
                            <div class="col-md-6 col-sm-5 col-xs-5">
                                <div class="info-box">
                                    <span class="info-box-icon bg-light-green"><i class="ion ion-earth"></i></span>
                                    <div class="info-box-content">
                                        <span class="my-block-title"><?= Yii::t('post', 'Posts') ?></span>
                                        <div class="grid-buttons">
                                            <?= Html::a($add_label, $urlManager->createUrl('post/create'), $options) ?>
                                            <?= Html::a($list_label, $urlManager->createUrl('post/index'), $options) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php /* Предприятия */ ?>
                            <div class="col-md-6 col-sm-5 col-xs-5">
                                <div class="info-box">
                                    <span class="info-box-icon bg-light-green"><i class="ion ion-podium"></i></span>
                                    <div class="info-box-content">
                                        <span class="my-block-title"><?= Yii::t('business', 'Business') ?></span>
                                        <div class="grid-buttons">
                                            <?= Html::a($add_label, $urlManager->createUrl('business/create'), $options) ?>
                                            <?= Html::a($list_label, $urlManager->createUrl('business/index'), $options) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php /* Вакансии */ ?>
                            <div class="col-md-6 col-sm-5 col-xs-5">
                                <div class="info-box">
                                    <span class="info-box-icon bg-light-green"><i class="ion ion-briefcase"></i></span>
                                    <div class="info-box-content">
                                        <span class="my-block-title"><?= Yii::t('vacantion', 'Vacantions') ?></span>
                                        <div class="grid-buttons">
                                            <?= Html::a($add_label, $urlManager->createUrl('work-vacantion/create'), $options) ?>
                                            <?= Html::a($list_label, $urlManager->createUrl('work-vacantion/index'), $options) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php /* Резюме */ ?>
                            <div class="col-md-6 col-sm-5 col-xs-5">
                                <div class="info-box">
                                    <span class="info-box-icon bg-light-green"><i
                                            class="ion ion-person-stalker"></i></span>
                                    <div class="info-box-content">
                                        <span class="my-block-title"><?= Yii::t('resume', 'Summary') ?></span>
                                        <div class="grid-buttons">
                                            <?= Html::a($add_label, $urlManager->createUrl('/work-resume/create'), $options) ?>
                                            <?= Html::a($list_label, $urlManager->createUrl('work-resume/index'), $options) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php /* Объявления */ ?>
                            <div class="col-md-6 col-sm-5 col-xs-5">
                                <div class="info-box">
                                    <span class="info-box-icon bg-light-green"><i class="ion ion-calendar"></i></span>
                                    <div class="info-box-content">
                                        <span class="my-block-title"><?= Yii::t('ads', 'Ads') ?></span>
                                        <div class="grid-buttons">
                                            <?= Html::a($add_label, $urlManager->createUrl('ads/create'), $options) ?>
                                            <?= Html::a($list_label, $urlManager->createUrl('ads/index'), $options) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php /* Офис */ ?>
                            <div class="col-md-6 col-sm-5 col-xs-5">
                                <div class="info-box">
                                    <span class="info-box-icon bg-light-green"><i class="ion ion-gear-b"></i></span>
                                    <div class="info-box-content">
                                        <span class="my-block-title"><?= Yii::t('app', 'Office') ?></span>
                                        <div class="grid-buttons">
                                            <?= Html::a('<span class="fa fa-area-chart"></span>' . Yii::t('app', 'Office'), $urlManager->createUrl('/'), $options) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-sm-4 col-lg-3">
                    <div class="quad-block-a"><?= AdBlock::getQuad() ?></div>

                    <div class="sidebar sidebar_dashboard">
                        <div class="widget widget_nav_menu"><h2 class="widgettitle">Меню</h2>
                            <div class="menu-dashboard-menu-container">
                                <ul id="menu-dashboard-menu" class="menu">
                                    <li class="menu-item menu-item-type-post_type menu-item-object-page">
                                        <?= Html::a('<i class="fa fa-fw fa-list"></i>' . Yii::t('app', 'Options'), ['/user/profile/index']); ?>
                                    <li class="menu-item menu-item-type-post_type menu-item-object-page">
                                        <?= Html::a('<i class="fa fa-fw fa-user"></i>' . Yii::t('user', 'Account'), ['/user/settings/account']); ?>
                                    <li class="menu-item menu-item-type-post_type menu-item-object-page">
                                        <?= Html::a('<i class="fa fa-fw fa-certificate"></i>' . Yii::t('user', 'Profile'), ['/user/settings/profile']); ?>
                                    </li>
                                    <?php if ($networksVisible) : ?>
                                        <li class="menu-item menu-item-type-post_type menu-item-object-page">
                                            <?= Html::a('<i class="fa fa-fw fa-eye"></i>' . Yii::t('user', 'Networks'), ['/user/settings/networks']); ?>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="left-block-a"><?= AdBlock::getLeft() ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
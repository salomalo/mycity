<?php
/**
 * @var $this \yii\web\View
 * @var $business \common\models\Business
 * @var $models common\models\Comment[]
 * @var $lastCompanyActivity string|null
 * @var $countCompanyAds integer
 * @var $countComments integer
 * @var $lvlGoodComment integer
 */
use common\models\Lang;
use frontend\extensions\CommentStat\CommentStat;
use frontend\extensions\SuperListLinkPager\SuperListLinkPager;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$alias = "{$business->id}-{$business->url}";
$city = Yii::$app->request->city;


$cur_lang = Lang::getCurrent()->url;

$canonical = str_replace('/site', '', Url::current([], 'http'));

$homeTitle = ($city = Yii::$app->request->city) ? Yii::t('app', 'site_of_the_city_of_{cityTitle}', ['cityTitle' => $city->title_ge]) : Yii::t('app', 'Home');
$main = ($canonical === Url::to($cur_lang, true)) ? null : Url::to($cur_lang, true);

$breadcrumbs = [
    ['label' => $business->title, 'url' => Url::to(['/business/view', 'alias' => $alias])],
    ['label' => 'Отзывы о компании ' . $business->title],
];
$this->title = 'Отзывы о компании ' . $business->title;
?>

<div class="main-inner">
    <div class="container">
        <div class="row" style="background-color: #fff;margin-top: 20px;">
            <?= Breadcrumbs::widget([
                'tag' => 'ol',
                'options' => ['class' => 'breadcrumb'],
                'homeLink' => ['label' => $homeTitle, 'url' => $main],
                'links' => $breadcrumbs,
            ]); ?>
            <h2 class="big-header" itemprop="name"><?= $business->title ?></h2>
        </div>
        <div class="row" style="background-color: #fff;margin-bottom: 50px">
            <div class="col-lg-8">
                <div class="b-layout__left-column js-left-column-anchor" data-extend="CropBox" data-cropbox-wrapper="#js-crop-wrapper" data-cropbox-box=".js-crop-box" id="ZA325C152-D237-4C1E-8006-D9D3B5E23482">

                    <div class="row" style="background-color: #f6f6f6">
                        <div class="col-lg-6">
                            <div class="h-layout-hidden h-ml-10" style="margin-top: 20px;">
                                <?php if ($countComments) : ?>
                                    <div class="b-reviews-stat h-vertical-middle h-mb-20">
                                    <div class="b-reviews-stat__column b-reviews-stat__column_theme_blue qa-opinion-count">
                                        <span class="b-reviews-stat__value h-text-center "><?= $countComments ?></span>
                                        <span class="b-reviews-stat__description h-text-center">отзыва</span>
                                        <span class="b-reviews-stat__corner"></span>
                                    </div>
                                    <div class="b-reviews-stat__column ">
                                        <span class="b-reviews-stat__value h-text-center"><?= $lvlGoodComment ?>%</span>
                                        <span class="b-reviews-stat__description">положительных</span>
                                    </div>
                                </div>
                                <?php endif;?>
                                <div>
                                    <div class="b-iconed-text">
                                        <span class="b-iconed-text__icon-holder">
                                            <i class="b-iconed-text__icon icon-term-on-portal h-mr-10"></i>
                                        </span>
                                        <span class="b-iconed-text__text-holder"><b>1 год</b>&nbsp;на портале</span>
                                    </div>

                                    <?php if($countCompanyAds) : ?>
                                        <div class="b-iconed-text">
                                            <span class="b-iconed-text__icon-holder">
                                                <i class="b-iconed-text__icon icon-category_category h-mr-10"></i>
                                            </span>
                                            <span class="b-iconed-text__text-holder">
                                                У компании <a href="<?= Url::to(['/business/goods', 'alias' => "{$business->id}-{$business->url}"])?>"><?= $countCompanyAds ?> предложения</a>
                                            </span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($lastCompanyActivity) : ?>
                                        <?php
                                        $dateCreate = strtotime($lastCompanyActivity);
                                        $date = new DateTime();
                                        $lastCompanyActivity = $date->setTimestamp($dateCreate);
                                        ?>
                                        <div class="b-iconed-text">
                                            <span class="b-iconed-text__icon-holder">
                                                <i class="b-iconed-text__icon icon-man h-mr-10"></i>
                                            </span>
                                            <span class="b-iconed-text__text-holder">
                                                <b><?= $lastCompanyActivity->format('d-m-Y') ?></b>&nbsp;дата последнего визита<span class="h-nowrap">&nbsp;
                                                <i class="b-icon-help js-popup-help" data-body="Время последнего визита представителя компании на свой сайт."></i></span>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6"?>
                            <div class="b-info-panel__wrap-text"  style="margin-top: 20px;background: #fff;border-bottom: 1px solid #ddd;padding: 20px;">
                                <div class="h-mb-15" style="font-size: 13px;">
                                    Оцените качество обслуживания по отзывам других покупателей или добавьте ваш собственный отзыв!
                                </div>

                                <?php if (Yii::$app->user->isGuest) : ?>
                                    <a href="<?= Url::to(['/comment/create', 'idBusiness' => $business->id])?>"
                                       class="b-button b-button_theme_blue h-inline-block"
                                       target="_blank"
                                       rel ="nofollow"
                                       onclick="login('<?= Url::to(['/user/security/login-ajax', 'redirectUrl' =>  Url::to(['/comment/create', 'idBusiness' => $business->id])])?>');return false;">
                                        <i class="icon-add_comment_but h-vertical-middle h-mr-5 h-ml-15"></i>
                                        <span class="b-button__aligner"></span><span class="b-button__text h-font-size-14 h-mr-15">Добавить отзыв</span>
                                    </a>

                                <?php else : ?>
                                    <a href="<?= Url::to(['/comment/create', 'idBusiness' => $business->id])?>" class="b-button b-button_theme_blue h-inline-block">
                                        <i class="icon-add_comment_but h-vertical-middle h-mr-5 h-ml-15"></i>
                                        <span class="b-button__aligner"></span><span class="b-button__text h-font-size-14 h-mr-15">Добавить отзыв</span>
                                    </a>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                    <div class="h-mb-30 h-mr-20">
                        <?= CommentStat::widget(['business' => $business]) ?>
                    </div>

                    <div class="b-line h-mv-40"></div>
                    <a name="opinion_list"></a>
                    <h1 class="large-header">
                        Отзывы о компании <?= $business->title ?>
                    </h1>

                    <?php if (Yii::$app->user->isGuest) : ?>
                        <a href="<?= Url::to(['/comment/create', 'idBusiness' => $business->id])?>"
                           class="b-button b-button_theme_blue h-mt-10"
                           target="_blank"
                           rel ="nofollow"
                           onclick="login('<?= Url::to(['/user/security/login-ajax', 'redirectUrl' =>  Url::to(['/comment/create', 'idBusiness' => $business->id])])?>');return false;">
                            <i class="icon-add_comment_but h-vertical-middle h-ml-15"></i>
                            <span class="b-button__aligner"></span>
                            <span class="b-button__text h-font-size-14 h-ml-5 h-mr-15">Добавить отзыв</span>
                        </a>

                    <?php else : ?>
                        <a class="b-button b-button_theme_blue h-mt-10" href="<?= Url::to(['/comment/create', 'idBusiness' => $business->id])?>">
                            <i class="icon-add_comment_but h-vertical-middle h-ml-15"></i>
                            <span class="b-button__aligner"></span>
                            <span class="b-button__text h-font-size-14 h-ml-5 h-mr-15">Добавить отзыв</span>
                        </a>
                    <?php endif; ?>

                    <div class="b-content">
                        <div class="b-reviews h-mt-30">
                            <div class="h-layout-clear"></div>
                            <?php if ($models) : ?>
                                <?php foreach ($models as $comment) : ?>
                                    <?= $this->render('_short_comment', ['model' => $comment]) ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                Еще нет отзывов  - будьте первым
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-lg-4"></div>

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


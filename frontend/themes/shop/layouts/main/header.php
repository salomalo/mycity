<?php

/**
 * @var $businessModel \common\models\Business
 */
use common\models\Comment;
use common\models\Lang;
use common\models\User;
use frontend\extensions\NumberItemInBasket\NumberItemInBasket;
use yii\helpers\Html;
use yii\helpers\Url;

$datetime = new \DateTime();
$datetime->setTimezone(new \DateTimeZone(Yii::$app->params['timezone']));

$cur_lang = Lang::getCurrent()->url;

$uriWithoutLang = Yii::$app->request->langUrl;
$cityTitle = empty(Yii::$app->params['SUBDOMAIN_TITLE_GE']) ? '' : Yii::$app->params['SUBDOMAIN_TITLE_GE'];

$home_url = (Url::to(['site/index'], true) === Url::current([], 'http')) ? null : Url::to($cur_lang, true);

$alias = "{$businessModel->id}-{$businessModel->url}";
$city = Yii::$app->request->city;
$url = Url::to(['/business/view', 'alias' => $alias]);
$front = Yii::$app->params['appFrontend'];
if ($businessModel->city && (($city && ($businessModel->idCity !== $city->id)) || !$city)) {
    $url = "http://{$businessModel->city->subdomain}.{$front}{$url}";
}

$home_url = (Url::to(['site/index'], true) === Url::current([], 'http')) ? null : Url::to($cur_lang, true);

$countComments = Comment::find()->where(['pid' => $businessModel->id, 'business_type' => Comment::TYPE_COMMENT_SHOP])->count();
$sumCommentRating = Comment::find()
    ->where(['pid' => $businessModel->id, 'business_type' => Comment::TYPE_COMMENT_SHOP])
    ->andWhere(['not', ['rating_business' => null]])
    ->sum('rating_business');
$numCommentRating = Comment::find()
    ->where(['pid' => $businessModel->id, 'business_type' => Comment::TYPE_COMMENT_SHOP])
    ->andWhere(['not', ['rating_business' => null]])
    ->count();

if ($countComments){
    $marksGood = (int)($sumCommentRating / $numCommentRating * 20);
} else {
    $marksGood = 0;
}


$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;

$urlCurrent = Yii::$app->request->getAbsoluteUrl();
$urlCurrent = urlencode($urlCurrent);
?>

<header class="header header-regular shop-header">
    <div class="header-bar">
        <div class="container">
            <div class="header-bar-inner">

                <div class="header-bar-left">
                    <div id="text-8" class="widget widget_text">
                        <div class="textwidget">
                            <ul class="currency-switch">
                                <li>
                                    <?= Html::a(Html::img('img/ua.png', ['alt' => 'uk']), Url::to("/uk{$uriWithoutLang}", true)) ?>
                                    <?= Html::a(Html::img('img/ru.png', ['alt' => 'ru']), Url::to("/ru{$uriWithoutLang}", true)) ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div style="float: left; margin-top: 5px;">
                    <a href="<?= $home_url ?>" rel="home" style="color: #fff;font-size: 14px;">CityLife</a>
                </div>

                <div class="header-bar-right">
                    <div class="widget widget_nav_menu">
                        <div class="menu-header-topbar-anonymous-container">
                            <ul class="menu">
                                <?php if (Yii::$app->user->isGuest) : ?>
                                    <li class="menu-item menu-item-type-post_type menu-item-object-page">
                                        <?= Html::a('<i class="fa fa-key"></i> ' . Yii::t('app', 'authorization'), ['/'], [
                                            'onclick' => 'login("' . Url::to(['/user/security/login-ajax']) . '");return false;',
                                            'rel' =>'nofollow',
                                        ]) ?>
                                    </li>
                                <?php else : ?>

                                    <li class="menu-item menu-item-type-post_type menu-item-object-page">
                                        <button id="btn-username" class="btn dropdown-toggle" type="button" data-toggle="dropdown">
                                            <?= '<i class="fa fa-user" aria-hidden="true"></i>', User::findOne(Yii::$app->user->id)->existName ?>
                                        </button>

                                        <ul class="dropdown-menu profile-menu">
                                            <li><a href="<?= Url::to('/profile') ?>" class="profile-menu-link">Профиль</a></li>
                                            <li><a href="<?= Yii::$app->urlManagerOffice->createUrl('/') ?>" class="profile-menu-link">Личный кабинет</a></li>
                                            <li class="divider"></li>
                                            <li>
                                                <?= Html::a(
                                                    '<i class="fa fa-sign-out" aria-hidden="true"></i>' . (Yii::t('app', 'logout')),
                                                    Url::to('/user/security/logout'),
                                                    ['data' => ['method' => 'post'], 'class' => 'profile-menu-link']
                                                ); ?>
                                            </li>
                                        </ul>
                                    </li>

                                <?php endif; ?>

                                <li class="menu-item menu-item-type-post_type menu-item-object-page">
                                    <span class="b-review-info b-head-control-panel__opinions-bar"><i
                                            class="b-review-info__icon"></i>
                                            <a href="<?= Url::to(['/business/' . $alias . '/commentList'])?>"
                                               target="_blank"
                                               class="b-review-info__link"><?= $countComments ?> отзыва</a>
                                    </span>

                                    <div>
                                        <div id="popup_Z2D9FB9CA-491D-4938-B42A-490E28149888" class="b-popup b-popup_type_hint-with-closer hidden" style="z-index: 10000029; top: 26px; margin-left: -115px;">
                                            <div class="b-popup__tail b-popup__tail_orientation_north" style="top: -5px;"></div>
                                            <div class="b-popup__body h-font-size-13">
                                                <table class="b-strip-list b-strip-list_type_table h-mb-15">
                                                    <tbody>
                                                    <tr class="b-strip-list__row">
                                                        <td class="b-strip-list__name">
                                                            <span class="b-strip-list__text-name">Общая оценка компании:</span>
                                                        </td>
                                                        <td class="b-strip-list__value">
                                                            <span class="b-strip-list__text-value">
                                                                <span class="b-progress b-progress_type_square b-progress_color_green">
                                                                    <span class="b-progress__bg-grey"></span>
                                                                    <span class="b-progress__bar" style="width: <?= $marksGood ?>%"></span>
                                                                    <span class="icon-progress_square b-progress__square"></span>
                                                                </span>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>

                                                <div class="b-opinions-links">
                                                    <a href="<?= Url::to(['/business/' . $alias . '/commentList'])?>" class="b-opinions-links__item" target="_blank">Все отзывы</a>
                                                    <?php if (Yii::$app->user->isGuest) : ?>
                                                        <a href="<?= Url::to(['/comment/create', 'idBusiness' => $businessModel->id])?>"
                                                            class="b-opinions-links__item"
                                                            target="_blank"
                                                            rel ="nofollow"
                                                            onclick="login('<?= Url::to(['/user/security/login-ajax', 'redirectUrl' =>  Url::to(['/comment/create', 'idBusiness' => $businessModel->id])])?>');return false;">Добавить отзыв</a>

                                                    <?php else : ?>
                                                        <a href="<?= Url::to(['/comment/create', 'idBusiness' => $businessModel->id])?>" class="b-opinions-links__item" target="_blank">Добавить отзыв</a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </li>

                                <li class="menu-item menu-item-type-post_type menu-item-object-page li-basket">
                                    <?= Html::a('<i class="fa fa-shopping-cart" aria-hidden="true"></i> ' . Yii::t('app', 'Shopping cart')  .  NumberItemInBasket::widget(), ['/business/' . $alias .'/shopping-cart']) ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="header-wrapper affix-top">
        <div class="container">
            <div class="header-inner">

                <div class="header-navigation-wrapper">
                    <div class="header-navigation" style="float:left;width: 100%">
                        <ul id="menu-main-menu" class="header-nav-primary nav nav-pills collapse navbar-collapse" style="width: 100%">
                            <li class="menu-item menu-item-type-custom menu-item-object-custom" id="menu-link-logo" style="width: 25%">
                                <div class="shop-header-description">
                                    <div class="header-logo">
                                        <a href="<?= $url ?>">
                                            <?= Html::img(Yii::$app->files->getUrl($businessModel, 'image', 200), ['alt' => $businessModel->title]) ?>
                                            <div class="shop-header-title" style="font-size: 19px;">
                                                <?php
                                                mb_internal_encoding("UTF-8");
                                                ?>
                                                <strong><?= mb_substr(strip_tags($businessModel->title), 0, 27) ?></strong>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li class="menu-item menu-item-type-custom menu-item-object-custom <?= $controller === 'business' && $action === 'view' ? 'current-menu-item' : '' ?>">
                                <a href="<?= Url::to(['/business/view', 'alias' => "{$businessModel->id}-{$businessModel->url}"]) ?>">Главная</a></li>
                            <li class="menu-item menu-item-type-custom menu-item-object-custom <?= $controller === 'ads' || $action === 'goods' || $action === 'search'  ? 'current-menu-item' : '' ?>">
                                <a href="<?= Url::to(['/business/goods', 'alias' => "{$businessModel->id}-{$businessModel->url}", 'urlCategory' => 'goods'])?>">Магазин</a></li>
                            <li class="menu-item menu-item-type-custom menu-item-object-custom <?= $controller === 'post' ? 'current-menu-item' : '' ?>">
                                <a href="<?= Url::to(['/business/' . "{$businessModel->id}-{$businessModel->url}" . '/' . 'blog']) ?>">Блог</a></li>
                            <li class="menu-item menu-item-type-custom menu-item-object-custom" id="menu-link-search" style="width: 20%;">
                                <div class="shop-search-input">

                                    <div class="form-group form-group-keyword style-keyword-search-header">
                                        <button type="submit" class="pull-right btn-search" style=""><i class="fa fa-search"></i></button>
                                        <div class="mini-search" style="display: none;width: 300px;height: 73px;padding-top: 0px;margin-top: 46px;padding-bottom: 0px;margin-bottom: 0px;">
                                            <div class="dropBox">
                                                <?= Html::beginForm(['/business/view', 'alias' => $alias], 'get', ['id' => 'find_business', 'class' => 'searchform']) ?>

                                                    <?= Html::input('text', 's', Yii::$app->request->get('s'), ['placeholder' => Yii::t('business', 'Я ищу ...'), 'class' => 'ield searchform-s']) ?>
                                                    <button type="submit" class="submit"><i class="fa fa-search fa-fw"></i></button>

                                                <?= Html::endForm() ?>
                                            </div><!-- ( DROP BOX END ) -->
                                        </div>
                                    </div>

                                </div>
                            </li>
                            <li id="menu_shared_icons" class="menu-item menu-item-type-custom menu-item-object-custom" style="float: right;">
                                <div class="top-bar-social">
                                    <a href="http://vkontakte.ru/share.php?url=<?= $urlCurrent ?>&noparse=true" onclick="window.open(this.href, this.title, 'toolbar=0, status=0, width=548, height=325'); return false" title="Сохранить в Вконтакте" target="_parent"><i class="fa fa-vk"></i>&nbsp;</a>
                                    <a href="http://www.facebook.com/sharer.php?s=100&p[url]=<?= $urlCurrent ?>" onclick="window.open(this.href, this.title, 'toolbar=0, status=0, width=548, height=325'); return false" title="Поделиться ссылкой на Фейсбук" target="_parent"><i class="fa fa-facebook-square"></i>&nbsp;</a>
                                    <a href="http://twitter.com/share?&url=<?= $urlCurrent ?>" title="Поделиться ссылкой в Твиттере" onclick="window.open(this.href, this.title, 'toolbar=0, status=0, width=548, height=325'); return false" target="_parent"><i class="fa fa-twitter-square"></i>&nbsp;</a>
                                </div>
                            </li>
                            <?php if ($businessModel->phone) : ?>
                            <li class="menu-item menu-item-type-custom menu-item-object-custom" id="menu-link-phone" style="margin-top: 22px; float: right;">
                                <div class="site-info">
                                    <div class="site-info__aside hidden-xs">
                                        <div class="site-info__icon" style="cursor: pointer">
                                            <div class="svg-icon">
                                                <i class="fa fa-phone fa-3" style="font-size: 2.1em;"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="site-info__inner">
                                        <?php
                                        $row = preg_replace("/\r\n|\r|\n/", '<br/>', $businessModel->phone);
                                        $str = strpos($row, "<br/>");
                                        if ($str) {
                                            $phone = substr($row, 0, $str);
                                            $row = substr($row, $str + strlen("<br/>"));
                                            $str = strpos($row, "<br/>");
                                            $phone = $phone . "<br/>" . substr($row, 0, $str);
                                        } else {
                                            $phone = $row;
                                        }
                                        ?>
                                        <div class="site-info__title"><?= $phone; ?></div>
                                        <div class="site-info__desc">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php endif; ?>

                        </ul>
                    </div>
                </div>

                <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".header-nav-primary">
                    <span class="sr-only">Показать меню</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
        </div>
    </div>
</header>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>

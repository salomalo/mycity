<?php
/**
 * @var $this \yii\web\View
 * @var $datetime DateTime
 * @var $home string
 */

use frontend\extensions\CityPopup\CityPopup;
use frontend\extensions\NumberItemInBasket\NumberItemInBasket;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\User;

$uriWithoutLang = Yii::$app->request->langUrl;
$cityTitle = empty(Yii::$app->params['SUBDOMAIN_TITLE_GE']) ? '' : Yii::$app->params['SUBDOMAIN_TITLE_GE'];

$home_url = (Url::to(['site/index'], true) === Url::current([], true)) ? false : Url::to($home, true);
?>
<header class="header header-regular">
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

                                <li class="menu-item menu-item-type-post_type menu-item-object-page li-basket">
                                    <?= Html::a('<i class="fa fa-shopping-cart" aria-hidden="true"></i> ' . Yii::t('app', 'Shopping cart')  .  NumberItemInBasket::widget(), ['/shopping-cart/index']) ?>
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
                <div class="header-logo">

                    <?php if ($home_url): ?>
                        <a href="<?= $home_url ?>" rel="home">
                            <?= Html::img('img/logo-bottom.png', ['alt' => 'CityLife']) ?>
                                <strong>CityLife</strong>
                        </a>
                    <?php else: ?>
                        <span>
                            <?= Html::img('img/logo-bottom.png', ['alt' => 'CityLife']) ?>
                            <strong>CityLife</strong>
                        </span>
                    <?php endif;?>

                    <?= CityPopup::widget() ?>
                </div>

                <?= $this->render('head_menu'); ?>

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
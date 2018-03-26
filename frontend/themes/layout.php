<?php
/**
 * @var \yii\web\View $this
 * @var string $content
 * @var \frontend\controllers\BusinessController $controller
 */

use common\extensions\Counters\Counters;
use common\models\Lang;
use common\models\User;
use frontend\extensions\NumberItemInBasket\NumberItemInBasket;
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
use frontend\themes\ShopAsset;
use yii\helpers\Html;

//frontend\themes\super_list\AppAssets::register($this);
ShopAsset::register($this);

Url::remember('', 'actions-redirect');
$controller = $this->context;

$datetime = new \DateTime();
$datetime->setTimezone(new \DateTimeZone(Yii::$app->params['timezone']));

$cur_lang = Lang::getCurrent()->url;
$alt_lang = Lang::getAlternate()->url;
$canonical = str_replace('/site', '', Url::current([], YII_ENV === 'prod' ? 'https' : 'http'));
$alt_canonical = Lang::getAlternateUrl();
$uriWithoutLang = Yii::$app->request->langUrl;
$home_url = (Url::to(['site/index'], true) === Url::current([], 'http')) ? null : Url::to($cur_lang, true);
$marksGood = 0;

if (Yii::$app->session->hasFlash('frontendLogin')) {
    $this->registerJs("yaCounter33738289.reachGoal('FRONTEND_LOGIN');", yii\web\View::POS_LOAD);
}
$this->registerJs('


',\yii\web\View::POS_END);

$alias = $controller->businessModel->id . '-' . $controller->businessModel->url;
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
        <title><?= Html::encode($this->title) ?></title>

        <?= Html::beginTag('base', ['href' => Url::to($cur_lang, true)]), PHP_EOL ?>
        <?= Html::beginTag('link', ['rel' => 'canonical', 'href' => $canonical]), PHP_EOL ?>
        <?php if ($alt_canonical) : ?>
            <?= Html::beginTag('link', ['rel' => 'alternate', 'hreflang' => $alt_lang, 'href' => $alt_canonical]), PHP_EOL ?>
        <?php endif; ?>
        <?= Html::beginTag('link', ['rel' => 'alternate', 'hreflang' => $cur_lang, 'href' => $canonical]), PHP_EOL ?>
        <?= Html::beginTag('link', ['rel' => 'stylesheet', 'href' => '/css/jquery.ad-gallery.css', 'type' => 'text/css']), PHP_EOL ?>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>
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
                <div class="label-citylife">
                    <a href="<?= $home_url ?>" rel="home">CityLife</a>
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
                                    <span class="b-review-info b-head-control-panel__opinions-bar">
                                        <i class="b-review-info__icon"></i>
                                        <a href="<?= Url::to(['/business/' . $alias . '/commentList'])?>"
                                           target="_blank" class="b-review-info__link"><?= $controller->businessModel->commentsCount ?> отзыва
                                        </a>
                                    </span>
                                    <div id="popup_Z2D9FB9CA-491D-4938-B42A-490E28149888" class="b-popup b-popup_type_hint-with-closer hidden" style="z-index: 10000029; top: 26px; margin-left: -115px;">
                                        <div class="b-popup__tail b-popup__tail_orientation_north"></div>
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
                                                <tr class="b-strip-list__row">
                                                    <td class="b-strip-list__name">
                                                        <span class="b-strip-list__text-name">Оценка товара/услуги:</span>
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
                                                    <a href="<?= Url::to(['/comment/create', 'idBusiness' => $controller->businessModel->id])?>"
                                                       class="b-opinions-links__item"
                                                       target="_blank"
                                                       rel ="nofollow"
                                                       onclick="login('<?= Url::to(['/user/security/login-ajax', 'redirectUrl' =>  Url::to(['/comment/create', 'idBusiness' => $controller->businessModel->id])])?>');return false;">Добавить отзыв</a>

                                                <?php else : ?>
                                                    <a href="<?= Url::to(['/comment/create', 'idBusiness' => $controller->businessModel->id])?>" class="b-opinions-links__item" target="_blank">Добавить отзыв</a>
                                                <?php endif; ?>
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
    <?= $this->renderFile('@layout',['content' => $content]); ?>


    <div class="b-footer" itemscope="itemscope" itemtype="http://schema.org/WPFooter">
        <div class="b-footer__row">
            <a class="js-popup" href="<?= Url::to(['/landing/index'])?>" id="834DAD95-85CF-4800-8D5E-C2205220ED21">Сайт создан на платформе CityLife</a>
        </div>

        <div class="b-footer__row">
            <?= $controller->businessModel->title ?>
            | <a href="#" rel="nofollow">Пожаловаться на содержимое</a>
        </div>
    </div>
    <div>
        <div class="bgl-overlay hidden" style="z-index: 90000; display: block;" data-reactid=".i">
            <div class="bgl-overlay__dialog" data-qaid="popup-overlay" data-reactid=".i.0">
                <div class="bgl-overlay__close-button" data-qaid="close-btn" data-reactid=".i.0.0"></div>
                <div class="bgl-overlay-phones" data-reactid=".i.0.1">
                    <div class="bgl-overlay-title qa-overlay-title" data-reactid=".i.0.1.0">Свяжитесь с продавцом
                    </div>
                    <div id="bgl-overlay-seller-phone">
                        <?= preg_replace("/\r\n|\r|\n/", '<br/>', $controller->businessModel->phone); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>
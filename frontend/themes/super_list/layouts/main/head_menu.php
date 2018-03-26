<?php
/**
 * @var $this \yii\web\View
 */
use common\models\City;
use yii\helpers\Html;
use yii\helpers\Url;

$city = (Yii::$app->request->city and ((int)Yii::$app->request->city->code !== 0)) ? Yii::$app->request->city : null;
$city_status = $city ? $city->main : 0;
$curController = Yii::$app->controller->id;
$curAction = Yii::$app->controller->action->id;

$getMenu = function ($controller, $title, $isShow, $action = 'index') use ($curController, $curAction) {
    $text = '';
    if ($isShow) {
        $isThisAction = ($curAction === $action) ? true : false;
        $isThisController = is_array($controller) ? in_array($curController, $controller) : ($curController === $controller);

        $action = $action ? $action : 'index';
        $controller = is_array($controller) ? $controller[0] : $controller;

        $text = ($isThisController and $isThisAction) ? Html::a($title, null) : Html::a($title, ["/$controller/$action"]);
        $class = ($isThisController and ($isThisAction or ($action === 'index'))) ?
            'menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home current-menu-ancestor'
            : 'menu-item menu-item-type-custom menu-item-object-custom';

        $text = "<li class='{$class}'>{$text}</li>";
    }

    return $text;
};

$isCity = $city ? true : false;
$isActive = $city ? ($city->main === City::ACTIVE) : true;

if (Yii::$app->user->isGuest){
    $urlAddAds = Html::a('<div class="postanitem-inner"><span class="fa fa-plus"></span>' .  Yii::t('app', 'Add_advert') . '</div>', ['/'], [
        'onclick' => 'login("' . Url::to(['/user/security/login-ajax', 'redirectUrl' => '/ads/create']) . '");return false;',
        'rel' =>'nofollow',
        'class' => 'menu-link main-menu-link',
        'style' => 'padding-bottom: 25px;'
    ]);
} else {
    $urlAddAds = Html::a('<div class="postanitem-inner"><span class="fa fa-plus"></span>' .  Yii::t('app', 'Add_advert') . '</div>', Url::to(['/ads/create']), ['class' => 'menu-link main-menu-link']);
}

$adPost = '<li class="menu-item menu-item-type-custom menu-item-object-custom" id="pfpostitemlink">' . $urlAddAds . '</li>';

$menu = implode(PHP_EOL, [
    $getMenu('afisha', Yii::t('afisha', 'Poster'), $isActive),
    $getMenu('action', Yii::t('action', 'Promotions'), $isActive),
    $getMenu('post', Yii::t('post', 'Posts'), $isActive),
    $getMenu('business', Yii::t('business', 'Business'), $isActive),
    //$getMenu('product', Yii::t('product', 'Product'), $isActive),
    $getMenu(['vacantion', 'resume'], Yii::t('vacantion', 'Jobs'), $isActive),
    $getMenu('ads', Yii::t('ads', 'Ads'), $isActive),
    $getMenu('site', Yii::t('app', 'About_city'), $isCity, 'about-city'),
    $adPost,
]);
?>

<div class="header-navigation-wrapper">
    <div class="header-navigation">
        <ul id="menu-main-menu" class="header-nav-primary nav nav-pills collapse navbar-collapse">
            <?= $menu ?>
        </ul>
    </div>
</div>
<?php
/**
 * @var \yii\web\View $this
 */

/** @var \frontend\components\LangUrlManager $urlManager */
$urlManager = Yii::$app->urlManagerOffice;
?>

<header>
    <nav class="navigation navigation-header">
        <div class="container">
            <div class="navigation-brand">
                <div class="brand-logo">
                    <a href="" class="logo"></a>
                    <a href="" class="logo logo-alt"></a>
                </div>
            </div>
            <button class="navigation-toggle">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="navigation-navbar collapsed">
                <ul class="navigation-bar navigation-bar-left">
                    <li><a href="http://citylife.dev/ru/landing#hero">Главная</a></li>
                    <li><a href="http://citylife.dev/ru/landing#product">Цены</a></li>
                    <li><a href="http://citylife.dev/ru/landing#about">О нас</a></li>
                    <li><a href="http://citylife.dev/ru/landing#features">Особенности</a></li>
                    <li><a href="http://citylife.dev/ru/landing#example">Примеры</a></li>
                    <li><a href="http://citylife.dev/ru/landing#footer">Контакты</a></li>
                </ul>
                <ul class="navigation-bar navigation-bar-right">
                    <li><a href="<?= $urlManager->createUrl('user/login') ?>">Вход</a></li>
                    <li><a href="https://office.citylife.info/ru/user/register">Регистрация</a></li>
                    <li class="featured"><a id="go-to-consultation" class="btn btn-sm btn-outline-color" href="#hero">Консультация</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>
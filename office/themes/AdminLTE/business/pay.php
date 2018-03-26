<?php
/**
 * @var $this \yii\web\View
 * @var $model Business
 */

use common\models\Business;
use office\assets\AppAssetsForest;
use yii\helpers\Html;

AppAssetsForest::register($this);

$user = Yii::$app->user->identity;
?>

<section id="product" class="section product-section align-center dark-text animated" data-animation="fadeInUp" data-duration="500">
    <div class="container forest">
        <div class="section-header">
            <h2> <span class="highlight"></span></h2>
            <p class="sub-header"></p>
        </div>
        <div class="section-content row">

            <div class="col-sm-4">
                <div class="package-column">
                    <div class="package-title">Free</div>
                    <div class="package-price">
                        <div class="price">0<span class="currency">грн</span></div>
                        <div class="period">&nbsp;</div>
                    </div>
                    <div class="package-detail">
                        <ul class="list-unstyled">
                            <li>Наша реклама</li>
                            <li>Базовая информация</li>
                            <li>0 объявлений</li>
                            <li class="disabled">Галерея</li>
                            <li class="disabled">Видео</li>
                            <li class="disabled">Афиша</li>
                            <li class="disabled">Акции</li>
                            <li class="disabled">Вакансии</li>
                        </ul>
                        <?php if ($model) : ?>
                            <?= Html::a('Выбрать', ['/business/update', 'id' => $model->id, 'tariff' => Business::PRICE_TYPE_FREE], ['class' => 'btn btn-outline-color btn-block']) ?>
                        <?php else : ?>
                            <?= Html::a('Выбрать', ['/business/create', 'tariff' => Business::PRICE_TYPE_FREE], ['class' => 'btn btn-outline-color btn-block']) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="package-column">
                    <div class="package-title">Light</div>
                    <div class="package-price">
                        <div class="price">100<span class="currency">грн</span></div>
                        <div class="period">в месяц</div>
                    </div>
                    <div class="package-detail">
                        <ul class="list-unstyled">
                            <li><strong>Нет рекламы</strong></li>
                            <li><strong>Полная</strong> информация</li>
                            <li>Галерея</li>
                            <li>Видео</li>
                            <li>50 объявлений</li>
                            <li class="disabled">Афиша</li>
                            <li class="disabled">Акции</li>
                            <li class="disabled">Вакансии</li>
                        </ul>
                        <?php if ($model) : ?>
                            <?= Html::a('Выбрать', ['/business/update', 'id' => $model->id, 'tariff' => Business::PRICE_TYPE_SIMPLE], ['class' => 'btn btn-outline-color btn-block']) ?>
                        <?php else : ?>
                            <?= Html::a('Выбрать', ['/business/create', 'tariff' => Business::PRICE_TYPE_SIMPLE], ['class' => 'btn btn-outline-color btn-block']) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="package-column">
                    <div class="package-title">Standart</div>
                    <div class="package-price">
                        <div class="price">250<span class="currency">грн</span></div>
                        <div class="period">в месяц</div>
                    </div>
                    <div class="package-detail">
                        <ul class="list-unstyled">
                            <li><strong>Нет рекламы</strong></li>
                            <li><strong>Полная</strong> информация</li>
                            <li>Галерея</li>
                            <li>Видео</li>
                            <li><strong>Неограничено</strong> объявлений</li>
                            <li>Афиша</li>
                            <li>Акции</li>
                            <li>Вакансии</li>
                        </ul>
                        <?php if ($model) : ?>
                            <?= Html::a('Выбрать', ['/business/update', 'id' => $model->id, 'tariff' => Business::PRICE_TYPE_FULL], ['class' => 'btn btn-outline-color btn-block']) ?>
                        <?php else : ?>
                            <?= Html::a('Выбрать', ['/business/create', 'tariff' => Business::PRICE_TYPE_FULL], ['class' => 'btn btn-outline-color btn-block']) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
    .package-column .package-detail li.disabled {
        color: #c9c9c9;
        font-weight: normal;
        text-decoration: line-through;
    }
    .long-block {
        position: relative;
        padding: 40px 0 25px;
        background-color: #248dc1;
        margin: 15px 0;
    }
    .light-text {
        color: #ffffff;
    }
    .long-block h1, .long-block h2, .long-block h3, .long-block h4, .long-block h5, .long-block h6, .long-block .heading {
        margin: -5px 0 0;
        line-height: 1;
        font-weight: 300;
        display: block;
        font-family: "Lato", "Helvetica Neue", Arial, Helvetica, sans-serif;
    }
    .long-block h2 {
        font-size: 45px;
    }
    .long-block .icon {
        margin-right: 15px;
        font-size: 60px;
        color: #ffffff;
    }
    .thin {
        font-weight: 300;
    }
    p {
        font-family: "PT Sans", Arial, "Helvetica Neue", Helvetica, sans-serif;
        margin: 0 0 10px;
        line-height: 25px;
    }
    strong {
        font-family: "Lato", "Helvetica Neue", Arial, Helvetica, sans-serif;
        font-weight: 700;
    }
    .long-block i.fa {
        line-height: 1;
        margin-right: 15px;
        font-size: 60px;
        color: #ffffff;
    }
</style>

<section id="guarantee" class="long-block light-text guarantee-section">
    <div class="container">
        <div class="col-md-12 col-lg-9">
            <i class="fa fa-bell-o pull-left" aria-hidden="true"></i>
            <article class="pull-left">
                <h2>Акция! Получи скидку 30%</h2>
                <p class="thin" style="margin-top: 10px;">
                    Только сейчас есть возможность получить тариф <strong>STANDART</strong> всего за 2000грн за <strong>целый год!</strong>
                </p>
            </article>
        </div>

        <div class="col-md-12 col-lg-3" style="margin-top: 7px;">
            <?= Html::a('Заказать сейчас', ['/business/create', 'tariff' => Business::PRICE_TYPE_FULL_YEAR], ['class' => 'btn btn-outline']) ?>
        </div>
    </div>
</section>
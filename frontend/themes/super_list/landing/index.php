<?php
/**
 * @var $this \yii\web\View
 */

use common\models\Business;
use yii\helpers\Html;
use yii\web\JqueryAsset;

/** @var \frontend\components\LangUrlManager $urlManager */
$urlManager = Yii::$app->urlManagerOffice;
if (Yii::$app->session->hasFlash('landingFlash')) {
    $this->registerJs("ga('send', 'event', 'reg-form', 'send'); fbq('track', 'Lead'); yaCounter33738289.reachGoal('OFFICE_REGISTRATION');", yii\web\View::POS_LOAD);
}

if (Yii::$app->session->hasFlash('landingConsultation')) {
    $this->registerJs("ga('send','event','request-for-consultation','send'); ", yii\web\View::POS_LOAD);
}
?>

<div id="hero" class="static-header image-version window-height light-text hero-section clearfix" style="min-height: 264px;">
    <div class="container centered-block" style="margin-top: 45px">

        <div class="col-sm-6 align-left">
            <div class="opacity-square">
                <h2 style="font-size: 60px;"><span class="highlight">ИНТЕРНЕТ МАГАЗИН</span> ЗА ЧАС</h2>
                <p style="margin-bottom: 60px;">
                    Наш сервис даёт простую и доступную возможность каждому не только создать свой сайт или интернет магазин за 1 час,
                    но и интегрирует его в платформу CityLife. Благодаря этому Вы сможете существенно увеличить Ваши продажи
                    не прилагая особых усилий!
                </p>
            </div>
        </div>
        <?php if (Yii::$app->session->hasFlash('landingFlash')) : ?>
            <div class="col-sm-6">
                <?= Yii::$app->session->getFlash('landingFlash', null, true) ?>
            </div>
        <?php elseif(Yii::$app->session->hasFlash('landingConsultation')) : ?>
            <div class="col-sm-6">
                <?= Yii::$app->session->getFlash('landingConsultation', null, true) ?>
            </div>
        <?php else : ?>
            <div class="col-sm-6" style="padding: 25px 35px;">
                <div id="form-consult">
                    <?= $this->render('_order_consultation') ?>
                </div>
                <div id="form-register" style="display: none;">
                    <?= $this->render('_register') ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->render('_callback') ?>

<section id="bonus" class="section about-section align-center dark-text">
    <div class="container">
        <div class="section-header">
            <h2>Бонусы</h2>
        </div>
        <div class="row row-feat">
            <div class="col-md-4 text-center">
                <div class="feature-img">
                    <img src="img/landing/presents.jpg" alt="image" class="img-responsive wow animated" data-delay="200" data-duration="700" data-animation="fadeInLeft">
                </div>
            </div>

            <div class="col-md-8 animated" data-delay="200" data-duration="700" data-animation="fadeInRight">
                <ul class="gift">
                    <li>SEO-продвижение в подарок</li>
                    <li>Бесплатное наполнение магазина</li>
                    <li>Интеграция с соцсетями</li>
                    <li>Добавление в гугл и яндекс</li>
                </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="product" class="section product-section align-center dark-text animated" data-animation="fadeInUp" data-duration="500">
    <div class="container">
        <div class="section-header">
            <h2><span class="highlight">ЦЕНЫ</span> ПРОДУКТОВ</h2>
            <p class="sub-header">

            </p>
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
                            <li>0 товаров</li>
                            <li class="disabled">Галерея</li>
                            <li class="disabled">Видео</li>
                            <li class="disabled">Афиша</li>
                            <li class="disabled">Акции</li>
                            <li class="disabled">Вакансии</li>
                        </ul>

                        <?php if (!Yii::$app->user->isGuest) : ?>
                            <?= Html::a('Заказать', $urlManager->createUrl(['/business/create', 'tariff' => Business::PRICE_TYPE_FREE]), ['class' => 'btn btn-outline-color btn-block']) ?>
                        <?php else : ?>
                            <?= Html::a('Заказать', $urlManager->createUrl(['/user/register', 'tariff' => Business::PRICE_TYPE_FREE]), ['class' => 'btn btn-outline-color btn-block']) ?>
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
                            <li>50 товаров</li>
                            <li class="disabled">Афиша</li>
                            <li class="disabled">Акции</li>
                            <li class="disabled">Вакансии</li>
                        </ul>

                        <?php if (!Yii::$app->user->isGuest) : ?>
                            <?= Html::a('Заказать', $urlManager->createUrl(['/business/create', 'tariff' => Business::PRICE_TYPE_SIMPLE]), ['class' => 'btn btn-outline-color btn-block']) ?>
                        <?php else : ?>
                            <?= Html::a('Заказать', $urlManager->createUrl(['/user/register', 'tariff' => Business::PRICE_TYPE_SIMPLE]), ['class' => 'btn btn-outline-color btn-block']) ?>
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
                            <li><strong>Неограничено</strong> товаров</li>
                            <li>Афиша</li>
                            <li>Акции</li>
                            <li>Вакансии</li>
                        </ul>
                        <?php if (!Yii::$app->user->isGuest) : ?>
                            <?= Html::a('Заказать', $urlManager->createUrl(['/business/create', 'tariff' => Business::PRICE_TYPE_FULL]), ['class' => 'btn btn-outline-color btn-block']) ?>
                        <?php else : ?>
                            <?= Html::a('Заказать', $urlManager->createUrl(['/user/register', 'tariff' => Business::PRICE_TYPE_FULL]), ['class' => 'btn btn-outline-color btn-block']) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="about" class="section about-section align-center dark-text">
    <div class="container">
        <div class="section-header">
            <h2>ДЛЯ КОГО НАШИ <span class="highlight">УСЛУГИ?</span></h2>
        </div>
        <div class="row row-feat">
            <div class="col-md-4 text-center">

                <div class="feature-img">
                    <img src="img/landing/business.jpg" alt="image" class="img-responsive wow fadeInLeft animated" style="visibility: visible; animation-name: fadeInLeft;">
                </div>
            </div>

            <div class="col-md-8">

                <div class="col-sm-6 feat-list">
                    <i class="icon icon-shopping-18 pe-5x pe-va wow fadeInUp animated" data-duration="5000" style="visibility: visible; animation-name: fadeInUp;"></i>
                    <div class="inner">
                        <h4>Магазины</h4>
                        <p>Для магазинов мы предоставляем возможность работы с каталогами товаров, корзиной, заказами.</p>
                    </div>
                </div>

                <div class="col-sm-6 feat-list">
                    <i class="icon icon-education-science-18 pe-5x pe-va wow fadeInUp animated" data-duration="5000" style="visibility: visible; animation-name: fadeInUp;"></i>
                    <div class="inner">
                        <h4>Кино, театры</h4>
                        <p>Для кинотеатров у нас есть мощный механизм - афиша, где они могут публиковать свои мероприятия</p>
                    </div>
                </div>

                <div class="col-sm-6 feat-list">
                    <i class="icon icon-food-09 pe-5x pe-va wow fadeInUp animated" data-duration="5000" style="visibility: visible; animation-name: fadeInUp;"></i>
                    <div class="inner">
                        <h4>Кафе, бары</h4>
                        <p>Кафе и бары легко могут сочетать товары и афишу. Товары - для организации меню, а в афише сообщать о мероприятиях в своих заведениях.</p>
                    </div>
                </div>

                <div class="col-sm-6 feat-list">
                    <i class="icon icon-seo-icons-03 pe-5x pe-va wow fadeInUp animated" data-duration="5000" style="visibility: visible; animation-name: fadeInUp;"></i>
                    <div class="inner">
                        <h4>Услуги</h4>
                        <p>Каталогом товаров также можно пользоваться для перечня предоставляемых услуг</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="features" class="section about-section align-center dark-text">
    <div class="container">
        <div class="section-header">
            <h2>ЧТО ВХОДИТ В <span class="highlight">УСЛУГУ?</span></h2>
            <p class="sub-header">
                Конечный результат нашей работы - это полноценный интернет-магазин, готовый к продажам.
            </p>
        </div>

        <ul class="nav nav-tabs alt">
            <li class="active"><a href="#first-tab-alt" data-toggle="tab">ВОЗМОЖНОСТИ</a></li>
            <li><a href="#second-tab-alt" data-toggle="tab">SEO</a></li>
            <li><a href="#third-tab-alt" data-toggle="tab">ПОДДЕРЖКА</a></li>
        </ul>

        <div class="tab-content alt">
            <div class="tab-pane active" id="first-tab-alt">
                <div class="section-content row">
                    <div class="col-sm-12 animated" data-delay="200" data-duration="700" data-animation="fadeInLeft">
                        <div class="col-sm-3 features-list">
                            <div class="img">
                                <img src="/img/landing/online-pay.png" alt="">
                            </div>
                            <div class="txt">Онлайн-оплаты</div>
                        </div>
                        <div class="col-sm-3 features-list">
                            <div class="img">
                                <img src="/img/landing/free-seo.png" alt="">
                            </div>
                            <div class="txt">Бесплатное SEO продвижение</div>
                        </div>
                        <div class="col-sm-3 features-list">
                            <div class="img">
                                <img src="/img/landing/multilang.png" alt="">
                            </div>
                            <div class="txt">Мультиязычность</div>
                        </div>
                        <div class="col-sm-3 features-list">
                            <div class="img">
                                <img src="/img/landing/adjust-design.png" alt="">
                            </div>
                            <div class="txt">Настраиваемый дизайн</div>
                        </div>
                        <div class="col-sm-3 features-list">
                            <div class="img">
                                <img src="/img/landing/high-safety.png" alt="">
                            </div>
                            <div class="txt">Высокая безопасность и надежность</div>
                        </div>
                        <div class="col-sm-3 features-list">
                            <div class="img">
                                <img src="/img/landing/free-hosting.png" alt="">
                            </div>
                            <div class="txt">Бесплатный хостинг</div>
                        </div>
                        <div class="col-sm-3 features-list">
                            <div class="img">
                                <img src="/img/landing/easy-controls.png" alt="">
                            </div>
                            <div class="txt">Удобное управление интернет магазином</div>
                        </div>
                        <div class="col-sm-3 features-list">
                            <div class="img">
                                <img src="/img/landing/high-speed.png" alt="">
                            </div>
                            <div class="txt">Высокая скорость работы</div>
                        </div>
                        <div class="col-sm-3 features-list">
                            <div class="img">
                                <img src="/img/landing/fast-support.png" alt="">
                            </div>
                            <div class="txt">Быстрая техническая поддержка</div>
                        </div>
                        <div class="col-sm-3 features-list not-active">
                            <div class="img">
                                <img src="/img/landing/sms-notif.png" alt="">
                            </div>
                            <div class="txt">SMS уведомление о заказах</div>
                        </div>
                        <div class="col-sm-3 features-list not-active">
                            <div class="img">
                                <img src="/img/landing/fast-import.png" alt="">
                            </div>
                            <div class="txt">Быстрый импорт товаров и синхронизация</div>
                        </div>
                        <div class="col-sm-3 features-list not-active">
                            <div class="img">
                                <img src="/img/landing/own-domen.png" alt="">
                            </div>
                            <div class="txt">Подключение собственного домена</div>
                        </div>
                        <div class="col-sm-3 features-list not-active">
                            <div class="img">
                                <img src="/img/landing/cool-templates.png" alt="">
                            </div>
                            <div class="txt">Удобные и красивые шаблоны</div>
                        </div>
                        <div class="col-sm-3 features-list not-active">
                            <div class="img">
                                <img src="css/forest/img/ComboChart-96.png" alt="">
                            </div>
                            <div class="txt">SEO Аналатика</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="second-tab-alt">
                <div class="section-content row">
                    <div class="col-sm-6 pull-right animated fadeInRight" data-delay="200" data-duration="700" data-animation="fadeInRight" style="animation-delay: 200ms; animation-duration: 0.7s;">
                        <img src="css/forest/img/seo.png" class="img-responsive pull-right" alt="process 2">
                    </div>
                    <div class="col-sm-6 animated fadeInLeft" data-delay="200" data-duration="700" data-animation="fadeInLeft" style="animation-delay: 200ms; animation-duration: 0.7s;">
                        <article class="align-center">
                            <h3><span class="highlight">Встроенные инструменты SEO </span>для достижения ТОП-позиций в поисковой выдаче</h3>
                            <p class="sub-title">Вам обеспечены лучшие возможности для продвижения интернет-магазина:</p>
                            <ul class="list">
                                <li>Вы получаете полностью оптимизированную платформу, которая соответствует 20 главным критериям для продвижения</li>
                                <li>Для вас мы разработаем стратегию продвижения и проведем по ней базовые работы, которые станут стартом популярности Вашего магазина</li>
                                <li>В своем интернет-магазине Вы сможете легко настроить все необходимые сервисы аналитики для дальнейшего учета прогресса</li>
                            </ul>
                        </article>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="third-tab-alt">
                <div class="section-content row">
                    <div class="col-sm-6 pull-left animated fadeInLeft" data-delay="200" data-duration="700" data-animation="fadeInLeft" style="animation-delay: 200ms; animation-duration: 0.7s;">
                        <img src="css/forest/img/support111.jpg" class="img-responsive pull-left" alt="process 2">
                    </div>
                    <div class="col-sm-6 animated fadeInRight" data-delay="200" data-duration="700" data-animation="fadeInRight" style="animation-delay: 200ms; animation-duration: 0.7s;">
                        <article class="align-center">
                            <h3>Мы предоставляем гарантию и <span class="highlight">полную поддержку</span></h3>
                            <p class="sub-title">Покупая у нас Вы покупаете не только то что есть, но и то что будет!</p>
                            <p>
                                80% проектов терпят неудачу из-за некачественной поддержки и невозможности обновлений.
                                С нами вам не о чем волноваться: все продукты включают техническую поддержку и постоянное
                                взаимодействие с нашими консультантами через личный кабинет, что качественно отличает нас от конкурентов.
                                Вы можете рассчитывать на наши подсказки, советы и рекомендации без временных ограничений.
                            </p>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<hr class="no-margin"/>

<section id="process" class="section process-section align-center dark-text">
    <div class="container">
        <div class="section-content row">
            <div class="col-sm-6 pull-right animated" data-duration="500" data-animation="fadeInRight">
                <img src="css/forest/img/content_image1.png" class="img-responsive" alt="process 2"/>
            </div>
            <div class="col-sm-6 align-left animated" data-duration="500" data-animation="fadeInLeft">
                <br/><br/>
                <article>
                    <h3>ПЛАТФОРМА <span class="highlight">CITYLIFE</span></h3>
                    <p class="sub-title">
                        Это наше главное отличие и Ваше основное преимущество!
                    </p>
                    <p>
                        У нас уже есть готовая база товаров, которая постоянно обновляется и индексируется поисковиками.
                        При добавлении товара Вы выбираете его уже из готовой базы. При просмотре товара будет видно и Ваше
                        предложение тоже! Также платформа включает очень мощный инструмент для поиска.
                    </p>
                </article>
            </div>
        </div>
    </div>
</section>

<section class="section process-section align-center dark-text">
    <div class="container">
        <div class="section-header">
            <h2>Расходы на создание обычного <span class="highlight">интернет-магазина</span></h2>
            <p class="sub-header">
                Наши клиенты экономят значительные средства при создании интернет-магазинов с помощью наших технологий и услуг
            </p>
        </div>
        <div class="2">

            <div class="statistic">
                <div class="statistic__body clearfix">
                    <div class="statistic__chart statistic__chart--mobile "><img src="/img/landing/chart.jpg" alt="chart">
                        <div class="statistic__chart-inner">
                            <div class="statistic__text-el">Экономия до</div>
                            <div class="statistic__heading statistic__heading--big">$4900</div>
                        </div>
                    </div>
                    <ul class="statistic__list statistic__list--text-right">
                        <li class="statistic__list-item">
                            <div class="statistic__heading">$500 — $1500
                                <div class="statistic__dotted-line statistic__dotted-line--arrow-left-top">
                                    <div class="statistic__icon-wrapper">
                                        <div class="icon icon-statistic-1"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="statistic__text-el">На создании магазина</div>
                        </li>
                        <li class="statistic__list-item">
                            <div class="statistic__heading">$300—$800
                                <div class="statistic__dotted-line">
                                    <div class="statistic__icon-wrapper">
                                        <div class="icon icon-statistic-2"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="statistic__text-el">На настройке аналитики</div>
                        </li>
                        <li class="statistic__list-item">
                            <div class="statistic__heading">
                                <div class="statistic__dotted-line statistic__dotted-line--arrow-left-bottom">
                                    <div class="statistic__icon-wrapper">
                                        <div class="icon icon-statistic-3"></div>
                                    </div>
                                </div>
                                $300 —$1000</div>
                            <div class="statistic__text-el">На дизайне</div>
                        </li>
                    </ul>
                    <ul class="statistic__list statistic__list--right">
                        <li class="statistic__list-item">
                            <div class="statistic__heading">$100/месяц
                                <div class="statistic__dotted-line statistic__dotted-line--left statistic__dotted-line--arrow-right-top">
                                    <div class="statistic__icon-wrapper">
                                        <div class="icon icon-statistic-4"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="statistic__text-el">На поддержке платформы, <br> бесплатных консультациях</div>
                        </li>
                        <li class="statistic__list-item">
                            <div class="statistic__heading">$200—$500
                                <div class="statistic__dotted-line statistic__dotted-line--left">
                                    <div class="statistic__icon-wrapper">
                                        <div class="icon icon-statistic-5"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="statistic__text-el">На маркетинговых <br> инструментах</div>
                        </li>
                        <li class="statistic__list-item">
                            <div class="statistic__heading">$200—$1000
                                <div class="statistic__dotted-line statistic__dotted-line--left statistic__dotted-line--arrow-right-bottom">
                                    <div class="statistic__icon-wrapper">
                                        <div class="icon icon-statistic-6"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="statistic__text-el">На разработке <br> дополнительных модулей</div>
                        </li>
                    </ul>
                    <div class="statistic__chart"><img src="/img/landing/chart.jpg" alt="chart">
                        <div class="statistic__chart-inner">
                            <div class="statistic__text-el">Экономия до</div>
                            <div class="statistic__heading statistic__heading--big">$4900</div>
                        </div>
                    </div>
                </div>
                <div class="statistic__footer">
                    <p class="statistic__text-el">
                        Пора обсудить другие Ваши выгоды
                    </p>
                    <h5 class="statistic__footer-heading">Закажите связь с нашим экспертом прямо сейчас!</h5>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
    form.subscribe-form {
        padding-top: 10px;
    }
</style>

<?php $this->registerJsFile('/js/forest/subscribe.js', [
    'depends' => [JqueryAsset::className()],
    'publishOptions' => ['forceCopy' => true]
]) ?>

<section id="newsletter" class="long-block newsletter-section light-text">
    <div class="container align-center">
        <div class="col-sm-12 col-lg-5 animated" data-animation="fadeInLeft" data-duration="500">
            <article>
                <h2>Подписаться на новости</h2>
                <p class="">Никакого спама - только новости и акции!</p>
            </article>
        </div>
        <div class="col-sm-12 col-lg-7 animated" data-animation="fadeInRight" data-duration="500">

            <?= Html::beginForm(['subscribe'], 'post', [
                'class' => 'form mailchimp-form subscribe-form',
                'data' => [
                    'success' => 'Спасибо за подписку на наши новости!',
                    'error' => 'Возникла ошибка, проверьте введенные данные!',
                ],
            ])?>
                <div class="form-group form-inline">
                    <?= Html::input('text', 'name', null, [
                        'class' => 'form-control required',
                        'placeholder' => 'Ваше имя',
                        'required' => true,
                        'size' => 15,
                    ]) ?>

                    <?= Html::input('email', 'email', null, [
                        'class' => 'form-control required',
                        'placeholder' => 'your@email.com',
                        'required' => true,
                        'size' => 20,
                    ]) ?>

                    <?= Html::submitInput('Подписаться', ['class' => 'btn btn-outline']) ?>
                </div>
            <?= Html::endForm() ?>

        </div>
    </div>
</section>

<section id="guarantee" class="long-block light-text guarantee-section">
    <div class="container">
        <div class="col-md-12 col-lg-9">
            <i class="icon icon-seo-icons-24 pull-left"></i>
            <article class="pull-left">
                <h2>Акция! Получи скидку 33%</h2>
                <p class="thin" style="margin-top: 10px;">Только сейчас есть возможность получить тариф <strong>STANDART</strong> всего за 2000грн на <strong>целый год!</strong></p>
            </article>
        </div>

        <div class="col-md-12 col-lg-3" style="margin-top: 7px;">
            <?= Html::a('Заказать сейчас', $urlManager->createUrl(['/business/create', 'tariff' => Business::PRICE_TYPE_FULL_YEAR]), ['class' => 'btn btn-outline']) ?>
        </div>
    </div>
</section>

<section id="example" class="section team-section align-center dark-text">
    <div class="container">
        <div class="section-header">
            <h2>ПРИМЕРЫ <span class="highlight">САЙТОВ</span></h2>
            <p class="sub-header">
                Наше портфолио включает в себя множество сайтов, вот некоторые из них:
<!--                <br>-->
            </p>
<!--            <p></p>-->
        </div>
        <div class="section-content row">

            <div class="col-md-4 col-sm-6 col-xs-12 animated fadeInDown" data-animation="fadeInDown" data-duration="500" style="animation-delay: 0ms; animation-duration: 0.5s;">
                <div class="team-member">
                    <div class="photo-wrapper">
                        <div class="overlay-wrapper">
                            <img src="img/forest/ex/ex1.jpg" alt="CityLife пример работы кафе">
                            <div class="overlay-content">
                                <div class="text-wrapper">
                                    <div class="text-container">
                                        <p>Кафе</p>
                                    </div>
                                </div>
                                <a class="btn btn-outline-color btn-block" href="https://nikolaev.citylife.info/ru/business/770917-gentle-cafe" target="_blank">Перейти на сайт</a>
                            </div>
                        </div>
                    </div>
                    <h5 class="name">Gentle Кафе</h5>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 col-xs-12 animated fadeInDown" data-animation="fadeInDown" data-duration="500" style="animation-delay: 0ms; animation-duration: 0.5s;">
                <div class="team-member">
                    <div class="photo-wrapper">
                        <div class="overlay-wrapper">
                            <img src="img/forest/ex/ex2.jpg" alt="CityLife пример работы кинотеатра с афишей">
                            <div class="overlay-content">
                                <div class="text-wrapper">
                                    <div class="text-container">
                                        <p>Кинотеатр Мультиплекс</p>
                                    </div>
                                </div>
                                <a class="btn btn-outline-color btn-block" href="https://kiev.citylife.info/ru/business/609958-kinoteatr-multipleks-trc-karavan" target="_blank">Перейти на сайт</a>
                            </div>
                        </div>
                    </div>
                    <h5 class="name">Кинотеатр Мультиплекс</h5>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 col-xs-12 animated fadeInDown" data-animation="fadeInDown" data-duration="500" style="animation-delay: 0ms; animation-duration: 0.5s;">
                <div class="team-member">
                    <div class="photo-wrapper">
                        <div class="overlay-wrapper">
                            <img src="img/forest/ex/ex3.jpg" alt="CityLife пример работы кафе">
                            <div class="overlay-content">
                                <div class="text-wrapper">
                                    <div class="text-container">
                                        <p>Интернет магазин Торговая сеть "Планета"</p>
                                    </div>
                                </div>
                                <a class="btn btn-outline-color btn-block" href="https://mariupol.citylife.info/ru/business/770920-torgovaa-set-planeta" target="_blank">Перейти на сайт</a>
                            </div>
                        </div>
                    </div>
                    <h5 class="name">Торговая сеть "Планета"</h5>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
/**
 * @var $this \yii\web\View
 * @var $countBusinesses int
 * @var $countUsers int
 * @var $countAds int
 * @var $countAction int
 */

use frontend\extensions\AdBlock;
use frontend\extensions\BusinessCategory\BusinessCategory;
use frontend\extensions\PointFinderGallery\ActionOwlCarousel;
use frontend\extensions\PointFinderGallery\AfishaOwlCarousel;
use frontend\extensions\VideoBlock\VideoBlock;
use yii\helpers\Html;
use yii\helpers\Url;

$this->registerCssFile('css/fonts/inventor-poi/style.css');
?>

<?= VideoBlock::widget() ?>
<?php if (Yii::$app->session->hasFlash('success')) : ?>
    <div class="col-xs-12" style="padding-left: 23%;margin-top: 20px;padding-right: 23%;">
        <div class="alert alert-success">
            <?= \Yii::t('user', 'Thank you, registration is now complete.')?>
        </div>
    </div>
<?php elseif (Yii::$app->session->hasFlash('danger')) : ?>
    <div class="col-xs-12" style="padding-left: 23%;margin-top: 20px;padding-right: 23%;">
        <div class="alert alert-danger">
            <?= \Yii::t('user', 'The confirmation link is invalid or expired. Please try requesting a new one.')?>
        </div>
    </div>
<?php endif; ?>
<div class="main-inner">
    <div class="container">
        <div id="header" class="widget widget_listings">
            <div class="widget-inner widget-border-top widget-pt widget-pb">
                <h1 class="widgettitle"><strong>CityLife</strong>
                    <span>создание и поиск интернет магазинов и товаров в твоём городе</span>
                </h1>
                <div class="row">
                    <div class="boxes-condensed">
                        <div class="col-sm-12 col-md-6">
                            <div class="box box-number-2 ">
                                <div class="box-inner">

                                    <?php if (Yii::$app->user->isGuest): ?>
                                        <?= Html::a('<div class="box-icon"><i class="fa fa-picture-o"></i></div>', ['/'], [
                                            'class' => 'box-read-more',
                                            'onclick' => 'login("' . Url::to(['/user/security/login-ajax', 'redirectUrl' => '/ads/create']) . '");return false;',
                                            'rel' => 'nofollow',
                                        ]) ?>
                                    <?php else: ?>
                                        <?= Html::a('<div class="box-icon"><i class="fa fa-picture-o"></i></div>', ['/ads/create'], [
                                            'class' => 'box-read-more',
                                            'rel' => 'nofollow',
                                        ]) ?>
                                    <?php endif; ?>

                                    <div class="box-body">
                                        <?php if (Yii::$app->user->isGuest): ?>
                                            <?= Html::a('Добавить объявление', ['/'], [
                                                'class' => 'box-read-more',
                                                'onclick' => 'login("' . Url::to(['/user/security/login-ajax', 'redirectUrl' => '/ads/create']) . '");return false;',
                                                'rel' =>'nofollow',
                                            ]) ?>
                                        <?php else: ?>
                                            <?= Html::a('Добавить объявление', Url::to(['/ads/create']), ['class' => 'box-read-more']) ?>
                                        <?php endif;?>

                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <div class="box box-number-3 ">
                                <div class="box-inner">
                                    <?= Html::a(
                                        '<div class="box-icon"><i class="fa fa-shopping-cart"></i></div>',
                                        ['/landing/index'],
                                        ['class' => 'box-read-more']
                                    ) ?>

                                    <div class="box-body">
                                        <?= Html::a('Добавить магазин', ['/landing/index'], ['class' => 'box-read-more']) ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?= AfishaOwlCarousel::widget() ?>
        <?= ActionOwlCarousel::widget() ?>
        <div id="inventor_boxes-4" class="widget widget_inventor_boxes">
            <div class="widget-inner boxes-centered background-primary background-dark boxes-numbers background-semitransparent	widget-pt widget-pb">
                <div class="row">
                    <div class="col-sm-12 col-lg-3 col-md-6">
                        <div class="box box-number-1 ">
                            <div class="box-inner">
                                <div class="box-icon">
                                    <i class="fa fa-industry"></i>
                                </div>
                                <div class="box-body">
                                    <h4 class="box-title">Предприятия</h4><!-- /.box-title -->
                                    <div class="box-number" data-value="<?= $countBusinesses?>"><?= $countBusinesses?></div><!-- /.box-number -->
                                </div><!-- /.box-body -->
                            </div><!-- /.box-inner -->
                        </div><!-- /.box -->
                    </div><!-- /.col-* -->
                    <div class="col-sm-12 col-lg-3 col-md-6">
                        <div class="box box-number-2 ">
                            <div class="box-inner">
                                <div class="box-icon">
                                    <i class="fa fa-user"></i>
                                </div><!-- /.box-icon -->
                                <div class="box-body">
                                    <h4 class="box-title">Пользователи</h4><!-- /.box-title -->
                                    <div class="box-number" data-value="<?= $countUsers?>"><?= $countUsers?></div><!-- /.box-number -->
                                </div><!-- /.box-body -->
                            </div><!-- /.box-inner -->
                        </div><!-- /.box -->
                    </div><!-- /.col-* -->
                    <div class="col-sm-12 col-lg-3 col-md-6">
                        <div class="box box-number-3 ">
                            <div class="box-inner">
                                <div class="box-icon">
                                    <i class="fa fa-picture-o"></i>
                                </div>
                                <div class="box-body">
                                    <h4 class="box-title">Объявления</h4><!-- /.box-title -->
                                    <div class="box-number" data-value="<?= $countAds?>"><?= $countAds?></div><!-- /.box-number -->
                                </div><!-- /.box-body -->
                            </div><!-- /.box-inner -->
                        </div><!-- /.box -->
                    </div><!-- /.col-* -->
                    <div class="col-sm-12 col-lg-3 col-md-6">
                        <div class="box box-number-4 ">
                            <div class="box-inner">
                                <div class="box-icon">
                                    <i class="fa fa-money"></i>
                                </div><!-- /.box-icon -->
                                <div class="box-body">
                                    <h4 class="box-title">Акции</h4><!-- /.box-title -->
                                    <div class="box-number" data-value="<?= $countAction?>"><?= $countAction?></div><!-- /.box-number -->
                                </div><!-- /.box-body -->
                            </div><!-- /.box-inner -->
                        </div>
                    </div><!-- /.col-* -->
                </div><!-- /.row -->
            </div><!-- /.widget-inner -->

        </div>
        <div id="listing_types-3" class="widget widget_listing_types">
            <div class="widget-inner widget-border-bottom widget-pt widget-pb">
                <h2 class="widgettitle">КАТЕГОРИИ <strong>ПРЕДПРИЯТИЙ</strong></h2>
                <div class="description">Самые популярные категории на нашем сайте</div>
                <div class="listing-types-cards items-per-row-4">
                    <?= BusinessCategory::widget([
                        'color' => '21abce',
                        'pid' => 'nedvizhimost',
                        'icon' => 'inventor-poi-condominium',
                        'img' => 'nedvizimost.jpeg',
                    ])?>
                    <?= BusinessCategory::widget([
                        'color' => 'e63434',
                        'pid' => 'avtosalony',
                        'icon' => 'inventor-poi-car',
                        'img' => 'cars.jpg',
                    ])?>
                    <?= BusinessCategory::widget([
                        'color' => 'ff287e',
                        'pid' => 'spa-massazh',
                        'icon' => 'inventor-poi-heart',
                        'img' => 'spa.jpg',
                    ])?>
                    <?= BusinessCategory::widget([
                        'color' => '43ccdb',
                        'pid' => 'obrazovanie',
                        'icon' => 'inventor-poi-university',
                        'img' => 'education.jpeg',
                    ])?>
                    <?= BusinessCategory::widget([
                        'color' => 'ffe123',
                        'pid' => 'kinoteatry-muzei-teatry-vystavki',
                        'icon' => 'inventor-poi-theatre',
                        'img' => 'theatre.jpg',
                    ])?>
                    <?= BusinessCategory::widget([
                        'color' => 'ff963a',
                        'pid' => 'restorany-kafe-piccerii-sushi',
                        'icon' => 'inventor-poi-lunch',
                        'img' => 'food.jpeg',
                    ])?>
                    <?= BusinessCategory::widget([
                        'color' => '008972',
                        'pid' => 'turagentstva-gostinicy-bazy-otdyha',
                        'icon' => 'inventor-poi-hotel',
                        'img' => 'hotels.jpg',
                    ])?>
                    <?= BusinessCategory::widget([
                        'color' => '5b71ab',
                        'pid' => 'poisk-raboty',
                        'icon' => 'inventor-poi-bookcase',
                        'img' => 'work.jpeg',
                    ])?>
                    <?= BusinessCategory::widget([
                        'color' => 'bababa',
                        'pid' => 'zoomir',
                        'icon' => 'inventor-poi-zoo',
                        'img' => 'zoo.jpeg',
                    ])?>
                    <?= BusinessCategory::widget([
                        'color' => '5caf36',
                        'pid' => 'stroitelstvo',
                        'icon' => 'inventor-poi-house',
                        'img' => 'stroyka.jpg',
                    ])?>
                    <?= BusinessCategory::widget([
                        'color' => '8154c9',
                        'pid' => 'magaziny',
                        'icon' => 'inventor-poi-cart',
                        'img' => 'shopes.jpeg',
                    ])?>
                    <?= BusinessCategory::widget([
                        'color' => 'ef7437',
                        'pid' => 'turagentstva-gostinicy-bazy-otdyha',
                        'icon' => 'inventor-poi-airport',
                        'img' => 'travel.jpg',
                    ])?>
            </div><!-- /.widget-inner -->
        </div>
    </div>
</div>
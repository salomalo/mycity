<?php
use backend\themes\adminlte236\AppAsset;
use backend\models\Admin;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div id="wrapper">
        <?=Breadcrumbs::widget([
                        'tag' => 'ol',
                        'homeLink' => [
                        'label' => 'Главная', 'url' => '/',
                        'encode' => false,],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        ]);
        ?>
        <?php
        NavBar::begin([
            'brandLabel' => '<span class="glyphicon glyphicon-home"></span>',
            'options' => [
                'class'=>'navbar navbar-fixed-top navbar-inverse',
            ],
        ]); 
        ?>
              
        
        <?=Nav::widget([
            'options'=>[
                'class'=>'nav side-nav navbar-nav',
            ],
            'items' => [
                [
                    'url' => '/site/index',
                    'label' => 'Home',
                    'icon' => 'home'
                ],
                [
                    'label' => 'Заказы',
                    'items' => [
                        ['label' => 'Заказы', 'icon'=>'phone', 'url'=>['orders/index']],
                        ['label' => 'Содержание заказа', 'icon'=>'phone', 'url'=>['orders-ads/index']],
                        ['label' => 'Cпособ оплаты', 'icon'=>'info-sign', 'url'=>['payment-type/index']],
                    ],
                ],
                [
                    'label' => 'Города',
                    'items' => [
                        ['label' => 'Регионы', 'icon'=>'info-sign', 'url'=>['region/index']],
                        ['label' => 'Города', 'icon'=>'phone', 'url'=>['city/index']],
                        ['label' => 'Сайты городов', 'icon'=>'phone', 'url'=>['parser-domain/index']],
                    ],
                ],
                [
                    'label' => 'Пользователи',
                    'items' => [
                        ['label' => 'Юзеры', 'icon'=>'info-sign', 'url'=>['account/index']],
                        ['label' => 'Админы', 'icon'=>'phone', 'url'=>['admin/index'], 'visible' => Yii::$app->user->getIdentity()->level == Admin::LEVEL_SUPER_ADMIN],
                    ],
                ],
                [
                    'label' => 'Предприятия',
                    'items' => [
                        ['label' => 'Предприятия', 'icon'=>'info-sign', 'url'=>['business/index']],
                        ['label' => 'Предприятия категории', 'icon'=>'phone', 'url'=>['business-category/index']],
                        ['label' => 'Логи парсера', 'icon'=>'info-sign', 'url'=>['log-parse-business/index']],
                    ],
                ],
                [
                    'label' => 'Продукты',
                    'items' => [
                        ['label' => 'Продукты', 'icon'=>'info-sign', 'url'=>['product/index']],
                        ['label' => 'Компании', 'icon'=>'info-sign', 'url'=>['product-company/index']],
                        ['label' => 'Категории продуктов', 'icon'=>'info-sign', 'url'=>['product-category/index']],
                        ['label' => 'Категории Customfield', 'icon'=>'info-sign', 'url'=>['customfield-category/index']],
                        ['label' => 'Customfield продуктов', 'icon'=>'info-sign', 'url'=>['product-customfield/index']],
                        ['label' => 'Логи парсера', 'icon'=>'info-sign', 'url'=>['log-parse/index']],
                    ],
                ],
                [
                    'label' => 'Новости',
                    'items' => [
                        ['label' => 'Категории новостей', 'icon'=>'phone', 'url'=>['post-category/index']],
                        ['label' => 'Новости', 'icon'=>'info-sign', 'url'=>['post/index']],
                    ],
                ],
                [
                    'label' => 'Работа',
                    'items' => [
                        ['label' => 'Категории работы', 'icon'=>'phone', 'url'=>['work-category/index']],
                        ['label' => 'Resume', 'icon'=>'info-sign', 'url'=>['work-resume/index']],
                        ['label' => 'Vacantion', 'icon'=>'info-sign', 'url'=>['work-vacantion/index']],
                    ],
                ],
                [
                    'label' => 'Объявления',
                    'url' => Yii::$app->urlManager->createUrl(array('ads/index')),
                ],
                [
                    'label' => 'Афиша',
                    'items' => [
                        ['label' => 'Кино', 'icon'=>'info-sign', 'url'=>['afisha/index', 'isFilm'=>true]],
                        ['label' => 'Категории Кино', 'icon'=>'phone', 'url'=>['afisha-category/index', 'isFilm' => true]],
                        ['label' => 'Афиша', 'icon'=>'info-sign', 'url'=>['afisha/index']],
                        ['label' => 'Категории Афиши', 'icon'=>'phone', 'url'=>['afisha-category/index']],
                    ],
                ],
                [
                    'label' => 'Комментарии',
                    'url' => Yii::$app->urlManager->createUrl(array('comment/index')),
                ],
                [
                    'label' => 'Акции',
                    'items' => [
                        ['label' => 'Акции', 'icon'=>'phone', 'url'=>['action/index']],
                        ['label' => 'Категории Акций', 'icon'=>'info-sign', 'url'=>['action-category/index']],
                    ],
                ],
                [
                    'label' => 'Друзья',
                    'url' => Yii::$app->urlManager->createUrl(array('friend/index')),
                ],
                [
                    'label' => 'Тикеты',
                    'url' => Yii::$app->urlManager->createUrl(array('ticket/index')),
                ],
            ],
        ]);
        echo Nav::widget([
            'encodeLabels' => false,
            'options'=>[
                'class'=>'pull-right nav navbar-nav',
            ],
            'items' => [
                [
                    'label' => '<span class="glyphicon glyphicon-user"></span> '. Yii::$app->user->identity->username,
                    'items' => [
                        '<li class="divider"></li>',
                        [
                            'label' => 'logout',
                            'url' => Yii::$app->urlManager->createUrl(array('site/logout')),
                        ],
                    ],
                ],
            ],
        ]);
        NavBar::end();
        ?>
        <div id="page-wrapper">
            <?php echo $content;?>
        </div>
    </div>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
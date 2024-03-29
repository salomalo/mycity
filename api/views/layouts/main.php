<?php
use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;
use frontend\extensions\City\City;

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
    <?php $this->head() ?> 
</head>
<body>
    <?php $this->beginBody() ?>

    <div class="main">
    <div class="main-width">
    <div class="main-bgr">

        <div class="header">

            <div class="logo">
                <div class="indent">
                    <h1 onclick="location.href='http://osc.template-help.com/wordpress_27256/'">City Portal</h1>    
                </div>
            </div>

            <div class="search">
                <div class="indent">
                    <h2>site search:</h2>
                    <form method="post" id="searchform" action="<?=\Yii::$app->urlManager->createUrl('product/search')?>">
                        <input type="text" class="text" value="" name="search_text" id="s" />
                        <input class="but" type="image" src="http://osc.template-help.com/wordpress_27256/wp-content/themes/theme997/images/search.gif" value="submit" />
                    </form>
                </div>
                <?php if(Yii::$app->user->isGuest):?>
                    <?= Html::a('Вход', ['site/login'], ['class' => 'btn btn-info']) ?>
                    <?= Html::a('Регистрация', ['/site/signup'], ['class' => 'btn btn-info']) ?>
                <?php else : ?>
                    <?= Html::a('Выход (' . Yii::$app->user->identity->username . ')', ['/site/logout'], ['class' => 'btn btn-info']) ?>
                
                    <?php $form = ActiveForm::begin([
                        'action' => 'http://'. \Yii::$app->params['appOffice'] .'/',
                        'method' => 'post',
                    ]); ?>
                    <?= Html::hiddenInput('key', strrev(Yii::$app->user->identity->auth_key)) ?>
                    <div class="form-group">
                        <?= Html::submitButton('Кабинет', ['class' => 'btn btn-primary']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                
                <?php endif;?>
                
            </div>

            <div class="menu">
                <ul>
                    <li class="page_item page-item-2"><a href="<?=\Yii::$app->urlManager->createUrl('site/index')?>" title="Home"><span><span>Главная</span></span></a></li>
                    <li class="page_item page-item-17"><a href="<?=\Yii::$app->urlManager->createUrl('business/index')?>" title="city"><span><span></span>Бизнесы</span></a></li>
                    <li class="page_item page-item-19"><a href="<?=\Yii::$app->urlManager->createUrl('product/index')?>" title="events"><span><span>Товары</span></span></a></li>
                    <li class="page_item page-item-21"><a href="<?=\Yii::$app->urlManager->createUrl('post/index')?>" tle="map"><span><span>Новости</span></span></a></li>
                    <li class="page_item page-item-23 current_page_item"><a href="<?=\Yii::$app->urlManager->createUrl('vacantion/index')?>" title="tourism"><span><span>Работа</span></span></a></li>
                    <li class="page_item page-item-25"><a href="<?=\Yii::$app->urlManager->createUrl('ads/index')?>" title="business"><span><span>Объявления</span></span></a></li>
                    <li class="page_item page-item-27"><a href="<?=\Yii::$app->urlManager->createUrl('afisha/index')?>" title="search"><span><span>Афиша</span></span></a></li>
                    <li class="page_item page-item-28"><a href="<?=\Yii::$app->urlManager->createUrl('action/index')?>" title="help"><span><span>Акции</span></span></a></li>
                </ul>
            </div>

        </div>

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        
        <div class="content">
            <?= $content;?>
        </div>



    </div>
    </div>

    <div class="footer">
        <div class="width">

            <div class="indent">

                <div class="menu">
                    <ul>
                        <li class="page_item page-item-2"><a href="<?=\Yii::$app->urlManager->createUrl('site/index')?>" title="Home"><span><span>Home</span></span></a></li>
                        <li class="page_item page-item-17"><a href="<?=\Yii::$app->urlManager->createUrl('ticket/index')?>" title="Вопросы"><span><span>Вопросы</span></span></a></li>
                        <li class="page_item page-item-19"><a href="http://osc.template-help.com/wordpress_27256/?page_id=19" title="events"><span><span>events</span></span></a></li>
                        <li class="page_item page-item-21"><a href="http://osc.template-help.com/wordpress_27256/?page_id=21" title="map"><span><span>map</span></span></a></li>
                        <li class="page_item page-item-23 current_page_item"><a href="http://osc.template-help.com/wordpress_27256/?page_id=23" title="tourism"><span><span>tourism</span></span></a></li>
                        <li class="page_item page-item-25"><a href="http://osc.template-help.com/wordpress_27256/?page_id=25" title="business"><span><span>business</span></span></a></li>
                        <li class="page_item page-item-27"><a href="http://osc.template-help.com/wordpress_27256/?page_id=27" title="search"><span><span>search</span></span></a></li><li class="page_item page-item-28"><a href="http://osc.template-help.com/wordpress_27256/?page_id=28" title="help"><span><span>help</span></span></a></li>
                    </ul>
                </div>

            </div>

        </div>
    </div>

    </div>



    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

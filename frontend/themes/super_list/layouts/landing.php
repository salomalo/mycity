<?php
/**
 * @var \yii\web\View $this
 * @var string $content
 */

use common\models\Lang;
use frontend\themes\super_list\AppAssetsForest;
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
use yii\helpers\Html;

AppAssetsForest::register($this);
Url::remember('', 'actions-redirect');
$cur_lang = Lang::getCurrent()->url;
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
    
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge"><![endif]-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
        <title>Создание Интернет-магазина, разработка по всем городам Украины - CityLife</title>
        <?= Html::beginTag('base', ['href' => Url::to("/$cur_lang", true)]), PHP_EOL ?>
        <meta name="description" content="Становись онлайн - выбирай Citylife. Создание интернет магазинов и сайтов за час">
        <meta name="keywords" content="Создание Интернет-магазина, разработка интернет магазина, разработка сайта, создание сайта, CityLife">
        <link rel="shortcut icon" href="img/forest/favicon.ico">
        <link rel="apple-touch-icon" href="img/forest/apple-touch-icon.jpg">
        <link rel="apple-touch-icon" sizes="72x72" href="img/forest/apple-touch-icon-72x72.jpg">
        <link rel="apple-touch-icon" sizes="114x114" href="img/forest/apple-touch-icon-114x114.jpg">
    
        <?php $this->head() ?>
    </head>
    
    <body id="landing-page" class="landing-page">
        <?php $this->beginBody() ?>
        
        <div class="preloader-mask">
            <div class="preloader">
                <div class="spin base_clr_brd">
                    <div class="clip left"><div class="circle"></div></div>
                    <div class="gap"><div class="circle"></div></div>
                    <div class="clip right"><div class="circle"></div></div>
                </div>
            </div>
        </div>
        
        <?= $this->render('landing/header') ?>
        
        <?= $content ?>
        
        <?= $this->render('landing/footer') ?>
        
        <div class="back-to-top"><i class="fa fa-angle-up fa-3x"></i></div>
        
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>

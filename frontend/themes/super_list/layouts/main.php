<?php
/**
 * @var \yii\web\View $this
 * @var string $content
 */

use common\models\Lang;
use frontend\themes\super_list\AppAssets;
use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
use frontend\extensions\VideoBlock\VideoBlock;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

AppAssets::register($this);
Url::remember('', 'actions-redirect');

$datetime = new \DateTime();
$datetime->setTimezone(new \DateTimeZone(Yii::$app->params['timezone']));

$homeTitle = ($city = Yii::$app->request->city) ? Yii::t('app', 'site_of_the_city_of_{cityTitle}', ['cityTitle' => $city->title_ge]) : Yii::t('app', 'Home');

$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;

$cur_lang = Lang::getCurrent()->url;
$alt_lang = Lang::getAlternate()->url;
$canonical = str_replace('/site', '', Url::current([], YII_ENV === 'prod' ? 'https' : 'http'));
$alt_canonical = Lang::getAlternateUrl();

$main = ($canonical === Url::to($cur_lang, true)) ? null : Url::to($cur_lang, true);
$main_string = ($canonical === Url::to($cur_lang, true)) ? null : '/';

if (Yii::$app->session->hasFlash('frontendLogin')) {
    $this->registerJs("yaCounter33738289.reachGoal('FRONTEND_LOGIN');", yii\web\View::POS_LOAD);
}
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
    <body class="single single-property postid-844 header-sticky layout-wide submenu-dark">
        <?php $this->beginBody() ?>
        <div class="page_wrapper">
            <?= $this->render('main/header', ['datetime' => $datetime, 'home' => $cur_lang]) ?>

            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>

            <?= $content; ?>

            <a href="#" id="top">
                <div class="action-bar-title"><i class="glyphicon glyphicon-chevron-up"></i></div>
            </a>

            <?= $this->render('main/footer', ['action' => Yii::$app->controller->action->id]) ?>
        </div>

        <div class="modal-screen">
            <div class="modal-close"><i class="fa fa-times"></i></div>
            <div class="modal-main"></div>
        </div>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
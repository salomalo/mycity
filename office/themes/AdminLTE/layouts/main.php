<?php
/**
 * @var \yii\web\View $this
 * @var string $content
 */

use common\extensions\Counters\Counters;
use common\models\Notification;
use common\models\QuestionConversation;
use office\assets\AdminLTEAsset;
use office\extensions\SupportChat\SupportChat;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

AdminLTEAsset::register($this);
$user = Yii::$app->user->identity;

if (Yii::$app->session->hasFlash('officeLogin')) {
    $this->registerJs("yaCounter33738289.reachGoal('OFFICE_LOGIN');", yii\web\View::POS_LOAD);
}
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
    <body class="hold-transition skin-blue sidebar-mini background-grey">
    <?php $this->beginBody() ?>

    <?= SupportChat::widget() ?>

    <div class="wrapper">
        <header class="main-header">
            <a href="/" class="logo">
                <span class="logo-mini"></span>
                <span class="logo-lg"><?= Yii::t('app', 'My_office') ?></span>
            </a>

            <nav class="navbar navbar-static-top">
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li><a href="http://citylife.info">Перейти на сайт</a></li>
                        <?= \office\extensions\Notification\Notification::widget()?>
                        <?php if ($user) : ?>
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="glyphicon glyphicon-user"></i>
                                    <span><?= $user->username ?><i class="caret"></i></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="user-header">
                                        <?= $user->photoUrl ? Html::img(Yii::$app->files->getUrl($user, 'photoUrl', 100), ['class' => 'img-circle']) : '' ?>
                                        <p><?= $user->username ?></p>
                                    </li>
                                    <li class="user-body"></li>
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <?= Html::a(Yii::t('app', 'Profile'), '/user/settings/profile', ['class' => 'btn btn-default btn-flat']); ?>
                                        </div>
                                        <div class="pull-right">
                                            <?= Html::a(Yii::t('app', 'Logout'), '/user/security/logout', ['class' => 'btn btn-default btn-flat', 'data' => ['method' => 'post']]); ?>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
        </header>

        <aside class="main-sidebar">
            <section class="sidebar">
                <div class="user-panel">
                    <div class="pull-left image">
                        <?php if (!Yii::$app->user->isGuest): ?>
                            <?php if ($user->photoUrl): ?>
                                <?= Html::img(Yii::$app->files->getUrl($user, 'photoUrl', 100), ['class' => 'img-circle', 'alt' => 'User Image']) ?>
                            <?php endif ?>
                        <?php endif; ?>
                    </div>

                    <div class="pull-left info">
                        <p></p>
                        <a href="#"><i class="fa fa-circle text-success"></i><?= Yii::t('app', 'Online') ?></a>
                    </div>
                </div>

                <?= $this->render('_menu') ?>

            </section>
        </aside>


        <aside class="right-side">
            <section class="content-header">
                <h1><?= $this->title ?></h1>

                <?= Breadcrumbs::widget([
                    'tag' => 'ol',
                    'homeLink' => [
                        'label' => '<i class="fa fa-dashboard">Главная</i>',
                        'url' => '/',
                        'encode' => false,
                    ],
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]); ?>
            </section>

            <section class="content">
                <div class="content"><?= $content; ?></div>
            </section>
        </aside>

        <footer class="main-footer">
            <div class="footer-first">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 2.3.7
                </div>
                <strong>Copyright © 2016 <a href="https://citylife.info/ru">CityLife</a>.</strong> All rights reserved.
            </div>

            <div class="footer-second">
                <div class="counter">
                    <?php if (YII_ENV === 'prod') : ?>
                        <?= Counters::widget(['app' => 'office']) ?>
                    <?php endif?>
                </div>
            </div>
        </footer>
    </div>

    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
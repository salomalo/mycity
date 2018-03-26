<?php
/** @var \common\models\Business $model */

use yii\helpers\Html;
use yii\helpers\Url;

$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;

$url = Yii::$app->request->getAbsoluteUrl();
$url = urlencode($url);

$row = preg_replace("/\r\n|\r|\n/", '<br/>', $model->phone);
$str = strpos($row, "<br/>");
if ($str) {
    $phone = substr($row, 0, $str);
    $phoneShort = $phone;
    $row = substr($row, $str + strlen("<br/>"));
    $str = strpos($row, "<br/>");
    $phone = $phone . "<br/>" . substr($row, 0, $str);
} else {
    $phone = $row;
    $phoneShort = $row;
}
?>
<header class="header style1">
    <div class="top_bar"  data-spy="affix" data-offset-top="30">
        <div class="container">

            <div class="col-md-12 top_right">
                <ul class="list-inline">
                    <?php if ($phoneShort) : ?>
                        <li><div class="header-business-phone" style="display: none;"><?= $phoneShort?></div></li>
                    <?php endif; ?>
                    <li><a href="http://vkontakte.ru/share.php?url=<?= $url ?>&noparse=true" onclick="window.open(this.href, this.title, 'toolbar=0, status=0, width=548, height=325'); return false" title="Сохранить в Вконтакте" target="_parent"><i class="fa fa-vk"></i>&nbsp;</a></li>
                    <li><a href="http://www.facebook.com/sharer.php?s=100&p[url]=<?= $url ?>" onclick="window.open(this.href, this.title, 'toolbar=0, status=0, width=548, height=325'); return false" title="Поделиться ссылкой на Фейсбук" target="_parent"><i class="fa fa-facebook-square"></i>&nbsp;</a></li>
                    <li><a href="http://twitter.com/share?&url=<?= $url ?>" title="Поделиться ссылкой в Твиттере" onclick="window.open(this.href, this.title, 'toolbar=0, status=0, width=548, height=325'); return false" target="_parent"><i class="fa fa-twitter-square"></i>&nbsp;</a></li>
                </ul>
            </div><!-- ( TOP RIGHT END ) -->
        </div>
    </div><!-- ( TOP BAR END ) -->

    <div class="container">
        <div class="head_inner">
            <div class="logo">
                <a href="<?= Url::to(['/business/view', 'alias' => "{$model->id}-{$model->url}"]) ?>">
                    <img src="<?= Yii::$app->files->getUrl($model, 'image', 200); ?>" alt="" style="width: <?= $phone ? '96px;' : '208px;' ?> height: 66px; object-fit: contain;"/>
                </a>
            </div><!-- ( LOGO END ) -->

            <div class="business-title-header" style="display:table-cell;vertical-align: middle;min-width: 200px;">
                <strong><?= $model->title ?></strong>
            </div>

            <nav class="clearfix">
                <div class="mbmenu">
                    <a href="#">Menu
                        <span class="lines"><span></span><span></span><span></span></span>
                    </a>
                </div><!-- ( MBMENU END ) -->

                <div class="main_menu_cont">
                    <ul class="main_menu">
                        <li class="mobSearch">
                            <div class="nav_search">
                                <?= Html::beginForm(['/business/search', 'alias' => "{$model->id}-{$model->url}"], 'get', ['id' => 'find_ads', 'class' => 'searchform']) ?>

                                <?= Html::input('text', 's', Yii::$app->request->get('s'), ['placeholder' => 'Введите и нажмите enter ...', 'class' => 'field searchform-s']) ?>
                                <?= Html::button('<i class="fa fa-search fa-fw"></i>', ['class' => 'submit', 'type' => 'submit']) ?>

                                <?= Html::endForm() ?>
                            </div><!-- ( NAV SEARCH END ) -->
                        </li>
                        <li class="menu-item ">
                            <a
                                href="<?= Url::to(['/business/view', 'alias' => "{$model->id}-{$model->url}"]) ?>"
                                class="<?= $controller === 'business' && $action === 'view' ? 'active' : '' ?>">Главная
                            </a>
                        </li>
                        <li class="menu-item">
                            <a
                                href="<?= Url::to(['/business/goods', 'alias' => "{$model->id}-{$model->url}", 'urlCategory' => 'goods'])?>"
                                class="<?= $controller === 'ads' || $action === 'goods' || $action === 'search'  ? 'active' : '' ?>">Магазин</a>
                        </li>
                        <li class="menu-item">
                            <a
                                href="<?= Url::to(['/business/' . "{$model->id}-{$model->url}" . '/' . 'blog']) ?>"
                                class="<?= $controller === 'post' ? 'active' : '' ?>">Блог</a>
                        </li>
                    </ul>
                </div><!-- ( MAIN MENU END ) -->

                <div class="nav_search mobile_none">
                    <a href="#" class="searchBTN fa fa-search fa-fw"></a>
                    <div class="mini-search">
                        <div class="dropBox">
                            <?= Html::beginForm(['/business/search', 'alias' => "{$model->id}-{$model->url}"], 'get', ['id' => 'find_ads', 'class' => 'searchform']) ?>

                            <?= Html::input('text', 's', Yii::$app->request->get('s'), ['placeholder' => 'Введите и нажмите enter ...', 'class' => 'field searchform-s']) ?>
                            <?= Html::button('<i class="fa fa-search fa-fw"></i>', ['class' => 'submit', 'type' => 'submit']) ?>

                            <?= Html::endForm() ?>
                        </div><!-- ( DROP BOX END ) -->
                    </div><!-- ( MINI SEARCH END ) -->
                </div><!-- ( NAV SEARCH END ) -->
            </nav>

            <div class="phone-header">
                <?= $phone; ?>
            </div>
        </div><!-- ( HEADER INNER END ) -->
    </div>
</header><!-- ( HEADER END ) -->
<?php
/** @var \common\models\Business $businessModel */

use frontend\controllers\ShoppingCartController;
use frontend\extensions\KuteshopHeaderCategory\KuteshopHeaderCategory;
use frontend\themes\kuteshop\AppAssets;
use yii\helpers\Html;
use yii\helpers\Url;

$bundle = AppAssets::register($this);

$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;

$alias = $businessModel->id . '-' . $businessModel->url;

$businessAddress = null;
if ($businessModel->address) {
    foreach ($businessModel->address as $address){
        if (isset($address->street)){
            $businessAddress = $address->street . ', ' . $address->city . ' ' . $address->country;
        } else {
            $businessAddress = $address->address;
        }
        break;
    }
}

$row = preg_replace("/\r\n|\r|\n/", '<br/>', $businessModel->phone);
$str = strpos($row, "<br/>");
if ($str) {
    $phone = substr($row, 0, $str);
    $row = substr($row, $str + strlen("<br/>"));
} else {
    $phone = $row;
}

$url = Yii::$app->request->getAbsoluteUrl();
$url = urlencode($url);

$countBasket = ShoppingCartController::getNumbetItem();
?>
<!-- HEADER -->
<header class="site-header header-opt-5">
    <!-- header-top -->
    <div class="header-top" data-spy="affix" data-offset-top="160">
        <div class="container">
            <div class="phone-header nav-right" style="display: none;">
                <?php if ($phone) : ?>
                    <strong>тел.: <?= $phone ?></strong>
                <?php endif; ?>
            </div>

            <!-- nav-left -->
            <ul class="nav-left" >
                <li class="social">
                    <a href="http://vkontakte.ru/share.php?url=<?= $url ?>&noparse=true" onclick="window.open(this.href, this.title, 'toolbar=0, status=0, width=548, height=325'); return false" title="Сохранить в Вконтакте" target="_parent" class="sh-facebook"><i class="fa fa-vk" aria-hidden="true"></i></a>
                    <a href="http://www.facebook.com/sharer.php?s=100&p[url]=<?= $url ?>" onclick="window.open(this.href, this.title, 'toolbar=0, status=0, width=548, height=325'); return false" title="Поделиться ссылкой на Фейсбук" target="_parent" class="sh-twitter"><i class="fa fa-facebook-square" aria-hidden="true"></i></a>
                    <a href="http://twitter.com/share?&url=<?= $url ?>" title="Поделиться ссылкой в Твиттере" onclick="window.open(this.href, this.title, 'toolbar=0, status=0, width=548, height=325'); return false" target="_parent" class="sh-pinterest"><i class="fa fa-twitter-square" aria-hidden="true"></i></a>
                </li>
            </ul><!-- nav-left -->

        </div>
    </div> <!-- header-top -->

    <!-- header-content -->
    <div class="header-content">
        <div class="container">

            <div class="row">

                <div class="col-md-4 col-md-push-4 nav-left">
                    <div class="row">
                        <!-- logo -->
                        <strong class="logo">
                            <a href="<?= Url::to(['/business/view', 'alias' => "{$businessModel->id}-{$businessModel->url}"]) ?>">
                                <img src="<?= Yii::$app->files->getUrl($businessModel, 'image', 200) ?>" alt="logo" style="max-height:100px;">
                            </a>
                        </strong><!-- logo -->
                    </div>
                    <div class="row">
                        <strong class="logo"><?= $businessModel->title ?></strong>
                    </div>

                </div>

                <div class="col-md-4 col-md-pull-4 nav-mind">

                    <!-- block search -->
                    <div class="block-search">
                        <div class="block-title">
                            <span>Search</span>
                        </div>
                        <div class="block-content">

                            <div class="form-search">
                                <?= Html::beginForm(['/business/search', 'alias' => "{$businessModel->id}-{$businessModel->url}"], 'get', ['id' => 'find_ads']) ?>

                                <div class="box-group">
                                    <?= Html::input('text', 's', Yii::$app->request->get('s'), ['placeholder' => 'Я ищу...', 'class' => 'form-control']) ?>

                                    <?= Html::button('<span>search</span>', ['class' => 'btn btn-search', 'type' => 'submit']) ?>

                                </div>
                                <?= Html::endForm() ?>
                            </div>

                        </div>
                    </div><!-- block search -->

                </div>

                <div class="col-md-4 nav-right">
                    <table class="address">
                        <?php if ($businessAddress) : ?>
                            <tr>
                                <td><b>Адрес: </b></td>
                                <td>
                                    <?= $businessAddress ?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php if ($businessModel->phone) : ?>
                            <tr>
                                <td><b>Телефон: </b></td>
                                <td><?= $businessModel->phone ?></td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>



            </div>

        </div>
    </div><!-- header-content -->

    <div class="  header-nav mid-header">
        <div class="container">
            <div class="box-header-nav">

                <span data-action="toggle-nav-cat" class="nav-toggle-menu nav-toggle-cat"><span>Categories</span><i aria-hidden="true" class="fa fa-bars"></i></span>

                <?= KuteshopHeaderCategory::widget(['business' => $businessModel]) ?>

                <!-- menu -->
                <div class="block-nav-menu">
                    <div class="clearfix"><span data-action="close-nav" class="close-nav"><span>close</span></span></div>
                    <ul class="ui-menu">
                        <li class="<?= $controller === 'business' && $action === 'view' ? 'active' : '' ?>"><a href="<?= Url::to(['/business/view', 'alias' => "{$businessModel->id}-{$businessModel->url}"]) ?>"> Главная </a></li>
                        <li class="<?= $controller === 'ads' || $action === 'goods' || $action === 'search'  ? 'active' : '' ?>"><a href="<?= Url::to(['/business/goods', 'alias' => "{$businessModel->id}-{$businessModel->url}", 'urlCategory' => 'goods'])?>">Магазин</a></li>
                        <li class="<?= $controller === 'post' ? 'active' : '' ?>">
                            <a href="<?= Url::to(['/business/' . "{$businessModel->id}-{$businessModel->url}" . '/' . 'blog']) ?>">Блог</a>
                        </li>
                        <li class="<?= $controller === 'business' && $action === 'about'  ? 'active' : '' ?>">
                            <a href="<?= Url::to(['/business/' . "{$businessModel->id}-{$businessModel->url}" . '/' . 'about']) ?>">О нас</a>
                        </li>
                    </ul>

                </div><!-- menu -->

                <span data-action="toggle-nav" class="nav-toggle-menu"><span>Меню</span><i aria-hidden="true" class="fa fa-bars"></i></span>

                <div class="block-minicart">
                    <?php if ($countBasket) : ?>
                        <span class="notify notify-shopping-cart" id="kuteshop-notify-cart"><?= ShoppingCartController::getNumbetItem() ?></span>
                    <?php endif; ?>
                    <a class="dropdown-toggle" href="<?= Url::to(['/business/' . $alias .'/shopping-cart']) ?>">
                        <span class="cart-icon"></span>
                    </a>
                </div>

                <div class="block-search">
                    <div class="block-title">
                        <span>Search</span>
                    </div>
                    <div class="block-content">
                        <div class="form-search">
                            <?= Html::beginForm(['/business/search', 'alias' => "{$businessModel->id}-{$businessModel->url}"], 'get', ['id' => 'find_ads']) ?>

                            <div class="box-group">
                                <?= Html::input('text', 's', Yii::$app->request->get('s'), ['placeholder' => 'Я ищу...', 'class' => 'form-control']) ?>

                                <?= Html::button('<span>search</span>', ['class' => 'btn btn-search', 'type' => 'submit']) ?>

                            </div>
                            <?= Html::endForm() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</header><!-- end HEADER -->
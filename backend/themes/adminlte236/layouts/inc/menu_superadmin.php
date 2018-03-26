<?php
/**
 * @var $this \yii\web\View
 */
use backend\extensions\NotVisitTicket\NotVisitTicket;
use yii\helpers\Url;
use backend\extensions\NotCheckedAfish\NotCheckedAfish;

?>
<ul class="sidebar-menu">
    <li class="header">Навигация</li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-book"></i>
            <span>Заказы</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?= Url::to(['orders/index']) ?>"><i class="fa fa-book"></i> Заказы</a></li>
            <li><a href="<?= Url::to(['orders-ads/index']) ?>"><i class="fa fa-align-left"></i> Содержание заказа</a></li>
            <li><a href="<?= Url::to(['provider/index']) ?>"><i class="fa fa-align-left"></i> ПОставщики пользователей</a></li>
            <li><a href="<?= Url::to(['payment-type/index']) ?>"><i class="fa fa-credit-card"></i> Cпособ оплаты</a></li>
            <li><a href="<?= Url::to(['liqpay-payment/index']) ?>"><i class="fa fa-cc-visa"></i> LiqPay</a></li>
            <li><a href="<?= Url::to(['notification/index']) ?>"><i class="fa fa-bell"></i> Оповещения</a></li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-map-marker"></i>
            <span>Города</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?= Url::to(['region/index']) ?>"><i class="fa fa-map"></i> Регионы</a></li>
            <li><a href="<?= Url::to(['city/index']) ?>"><i class="fa fa-map-marker"></i> Города</a></li>
            <li><a href="<?= Url::to(['parser-domain/index']) ?>"><i class="fa fa-globe"></i> Сайты городов</a></li>
            <li><a href="<?= Url::to(['widget-city-public/index']) ?>"><i class="fa fa-vk"></i> Виджет паблика города</a></li>
            <li><a href="<?= Url::to(['counter/index']) ?>"><i class="fa fa-calculator"></i> Счетчики городов</a></li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-user"></i>
            <span>Пользователи</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?= Url::to(['user/index']) ?>"><i class="fa fa-users"></i> Юзеры</a></li>
            <li><a href="<?= Url::to(['admin/index']) ?>"><i class="fa fa-male"></i> Админы</a></li>
            <li><a href="<?= Url::to(['subscription/index']) ?>"><i class="fa fa-user-secret"></i> Подписчики</a></li>
            <li><a href="<?= Url::to(['lead/index']) ?>"><i class="fa fa-list"></i> Заказ консультации</a></li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-briefcase"></i>
            <span>Предприятия</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?= Url::to(['business/index']) ?>"><i class="fa fa-briefcase"></i> Предприятия</a></li>
            <li><a href="<?= Url::to(['business-category/index']) ?>"><i class="fa fa-list-ul"></i> Категории предприятий</a></li>
            <li><a href="<?= Url::to(['/business-custom-field/index']) ?>"><i class="fa fa-check-square-o"></i> Кастомфилды предприятий</a></li>
            <li><a href="<?= Url::to(['log-parse-business/index']) ?>"><i class="fa fa-file-text-o"></i> Логи парсера</a></li>
            <li><a href="<?=Url::to(['/business-owner-application/index'])?>"><i class="fa fa-circle-o"></i> Заявки пользователей</a></li>
            <li><a href="<?= Url::to(['invoice/index']) ?>"><i class="fa fa-file-text-o"></i> Инвоисы</a></li>
            <li><a href="<?= Url::to(['business-template/index']) ?>"><i class="fa fa-file-text-o"></i>Шаблоны</a></li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-shopping-basket"></i>
            <span>Продукты</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?= Url::to(['product/index']) ?>"><i class="fa fa-shopping-basket"></i> Продукты</a></li>
            <li><a href="<?= Url::to(['product-company/index']) ?>"><i class="fa fa-copyright"></i> Компании</a></li>
            <li><a href="<?= Url::to(['product-category/index']) ?>"><i class="fa fa-list-ul"></i> Категории продуктов</a></li>
            <li><a href="<?= Url::to(['customfield-category/index']) ?>"><i class="fa fa-list-ul"></i> Категории Customfield</a></li>
            <li><a href="<?= Url::to(['product-customfield/index']) ?>"><i class="fa fa-check-square-o"></i> Customfield продуктов</a></li>
            <li><a href="<?= Url::to(['log-parse/index']) ?>"><i class="fa fa-file-text-o"></i> Логи парсера</a></li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-newspaper-o"></i>
            <span>Новости</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?= Url::to(['/post/index']) ?>"><i class="fa fa-newspaper-o"></i> Новости</a></li>
            <li><a href="<?= Url::to(['/post-category/index']) ?>"><i class="fa fa-list-ul"></i> Категории новостей</a></li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-industry"></i>
            <span>Работа</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?= Url::to(['/work-category/index']) ?>"><i class="fa fa-list-ul"></i> Категории работы</a></li>
            <li><a href="<?= Url::to(['/work-resume/index']) ?>"><i class="fa fa-graduation-cap"></i> Резюме</a></li>
            <li><a href="<?= Url::to(['/work-vacantion/index']) ?>"><i class="fa fa-industry"></i> Вакансии</a></li>
        </ul>
    </li>

    <li><a href="<?= Url::to(['/ads/index']) ?>"><i class="fa fa-file-text-o"></i> Объявления</a></li>

    <?= NotCheckedAfish::widget()?>

    <li><a href="<?= Url::to(['/comment/index']) ?>"><i class="fa fa-comments"></i> Комментарии</a></li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-shopping-cart"></i>
            <span>Акции</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?= Url::to(['/action/index']) ?>"><i class="fa fa-shopping-cart"></i> Акции </a></li>
            <li><a href="<?= Url::to(['/action-category/index']) ?>"><i class="fa fa-list-ul"></i> Категории Акций</a></li>
        </ul>
    </li>

    <li><a href="<?= Url::to(['/advertisement/index']) ?>"><i class="fa fa-chrome"></i> Рекламная компания</a></li>
    <li><a href="<?= Url::to(['/friend/index']) ?>"><i class="fa fa-users"></i> Друзья</a></li>
    <li>
        <a href="<?= Url::to(['/ticket/index']) ?>">
            <?php
            $countNewTickets = NotVisitTicket::widget();
            ?>
            <small class="label pull-right bg-yellow"><?= $countNewTickets ? $countNewTickets : '' ?></small>
            <i class="fa fa-ticket"></i> Тикеты
        </a>
    </li>
    <li><a href="<?= Url::to(['/lang/index']) ?>"><i class="fa fa-language"></i> Языки</a></li>
    <li><a href="<?= Url::to(['/wall/index']) ?>"><i class="fa fa-rss-square"></i> RSS</a></li>
    <li><a href="<?= Url::to(['/tag/index']) ?>"><i class="fa fa-tags"></i> Теги</a></li>


    <li class="treeview">
        <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Логи</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?= Url::to(['/system-log/index']) ?>"><i class="fa fa-cogs"></i> SystemLog </a></li>
            <li><a href="<?= Url::to(['/view-count/index']) ?>"><i class="fa fa-eye"></i> Просмотры</a></li>
            <li><a href="<?= Url::to(['/admin-log/index']) ?>"><i class="fa fa-user-secret"></i> Логи админа</a></li>
            <li><a href="<?= Url::to(['/admin-log/auth']) ?>"><i class="fa fa-user-secret"></i> Логи аутентификации</a></li>
        </ul>
    </li>
</ul>
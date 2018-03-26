<?php
/**
 * @var $this \yii\web\View
 */
use backend\extensions\NotCheckedAfish\NotCheckedAfish;
use backend\extensions\NotVisitTicket\NotVisitTicket;
use yii\helpers\Url;
?>

<ul class="sidebar-menu">
    <li class="header">Навигация</li>

    <li>
        <a href="<?= Url::to(['/ticket/index']) ?>">
            <?php
            $countNewTickets = NotVisitTicket::widget();
            ?>
            <small class="label pull-right bg-yellow"><?= $countNewTickets ? $countNewTickets : '' ?></small>
            <i class="fa fa-ticket"></i> Тикеты
        </a>
    </li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-briefcase"></i>
            <span>Предприятия</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?= Url::to(['business/index']) ?>"><i class="fa fa-briefcase"></i> Предприятия</a></li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-shopping-cart"></i>
            <span>Акции</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?= Url::to(['/action/index']) ?>"><i class="fa fa-shopping-cart"></i> Акции </a></li>
        </ul>
    </li>

    <?= NotCheckedAfish::widget()?>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-newspaper-o"></i>
            <span>Новости</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?= Url::to(['/post/index']) ?>"><i class="fa fa-newspaper-o"></i> Новости</a></li>
        </ul>
    </li>

    <li><a href="<?= Url::to(['/orders/index']) ?>"><i class="fa fa-book"></i> Заказы</a></li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-industry"></i>
            <span>Работа</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?= Url::to(['/work-resume/index']) ?>"><i class="fa fa-graduation-cap"></i> Резюме</a></li>
            <li><a href="<?= Url::to(['/work-vacantion/index']) ?>"><i class="fa fa-industry"></i> Вакансии</a></li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-user"></i>
            <span>Пользователи</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?= Url::to(['user/index']) ?>"><i class="fa fa-users"></i> Юзеры</a></li>
            <li><a href="<?= Url::to(['admin-log/index']) ?>"><i class="fa fa-user-secret"></i> Логи админа</a></li>
        </ul>
    </li>

    <li><a href="<?= Url::to(['/comment/index']) ?>"><i class="fa fa-comments"></i> Комментарии</a></li>

    <li><a href="<?= Url::to(['/advertisement/index']) ?>"><i class="fa fa-chrome"></i> Рекламная компания</a></li>

    <li><a href="<?= Url::to(['/ads/index']) ?>"><i class="fa fa-file-text-o"></i> Объявления</a></li>

    <li><a href="<?= Url::to(['product/index']) ?>"><i class="fa fa-shopping-basket"></i> Продукты</a></li>
</ul>
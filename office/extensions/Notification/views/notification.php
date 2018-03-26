<?php

use yii\helpers\Url;

/**@var int $count*/
?>

<li class="dropdown notifications-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-url="<?= Url::to(['/site/notification-count']) ?>">
        <i class="fa fa-bell-o"></i>
        <span class="label label-warning"><?= $count ? $count : '' ?></span>
    </a>
    <ul class="dropdown-menu">
        <li class="header">Уведомления</li>
        <li><ul class="menu"></ul></li>
    </ul>
</li>
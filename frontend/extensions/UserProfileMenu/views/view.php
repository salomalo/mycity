<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="profile-button">
    <button class="btn btn-link dropdown-toggle" type="button" data-toggle="dropdown">
        <span class="glyphicon glyphicon-th-list"></span>
    </button>
    <ul class="dropdown-menu">
        <li><a href="<?= Url::to('/profile') ?>">Профиль</a></li>
        <li><a href="<?= Yii::$app->urlManagerOffice->createUrl('/user/security/login') ?>">Личный кабинет</a></li>
        <li class="divider"></li>
        <li>
            <?= Html::a(
                (Yii::t('app', 'logout')),
                Url::to('/user/security/logout'),
                ['data' => ['method' => 'post']]
            ); ?>
        </li>
    </ul>
    <ul class="dropdown-menu">
        <li><a href="<?= Url::to('/profile') ?>">Профиль</a></li>
        <li><a href="<?= Yii::$app->urlManagerOffice->createUrl('/user/security/login') ?>">Личный кабинет</a></li>
        <li class="divider"></li>
        <li>
            <?= Html::a(
                (Yii::t('app', 'logout')),
                Url::to('/user/security/logout'),
                ['data' => ['method' => 'post']]
            ); ?>
        </li>
    </ul>
</div>
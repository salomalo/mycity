<?php
/**
 * @var $this \yii\web\View
 * @var $countNotification array
 */

use backend\models\Admin;
use yii\helpers\Url;
$admin = Yii::$app->user->identity;


?>

<li class="treeview">
    <a href="#">
        <i class="fa fa-film"></i>
        <span>Афиша</span>
        <span class="pull-right-container">
            <small class="label pull-right bg-yellow"><?= $countNotification['all'] ? $countNotification['all'] : ''?></small>
            <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li>
            <a href="<?= Url::to(['/afisha/index', 'isFilm' => true]) ?>">
                <i class="fa fa-film"></i> Кино<small class="label pull-right bg-yellow"><?= $countNotification['film'] ? $countNotification['film'] : ''?></small>
            </a>
        </li>
        <li><a href="<?= Url::to(['/afisha-category/index', 'isFilm' => true]) ?>"><i class="fa fa-list-ul"></i> Категории Кино</a></li>
        <li><a href="<?= Url::to(['/kino-genre/index']) ?>"><i class="fa fa-trophy"></i> Жанры Кино</a></li>
        <?= ($admin->level === Admin::LEVEL_SUPER_ADMIN) ? '<li><a href="' .  Url::to(['/parse-kino/index']) .'"><i class="fa fa-list-ul"></i> Ассоциации Кинотеатров</a></li>' : ''?>
        <li>
            <a href="<?= Url::to(['/afisha/index']) ?>">
                <i class="fa fa-calendar-check-o"></i> Афиша<small class="label pull-right bg-yellow"><?= $countNotification['afisha'] ? $countNotification['afisha'] : ''?></small>
            </a>
        </li>
        <li><a href="<?= Url::to(['/afisha-category/index']) ?>"><i class="fa fa-list-ul"></i> Категории Афиши</a></li>
    </ul>
</li>
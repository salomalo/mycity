<?php
use common\models\search\City;
use yii\helpers\Html;
use common\models\Lang;

/**
 * @var $models City[]
 * @var $arr array
 */
$base_frontend = Yii::$app->params['appFrontend'] . '/' . Lang::getCurrent()->url;
$frontend = $base_frontend . $arr['link'];
?>

<h2 class="widgettitle"><?= $arr['title'] ?> <i class="fa fa-long-arrow-down"></i></h2>
<div class="menu-quick-links-container">
    <ul id="menu-quick-links" class="menu">

        <?php foreach ($models as $item): ?>
            <li class="menu-item menu-item-type-post_type menu-item-object-page">
                <?= Html::a("{$arr['label']} {$item->$arr['attr']}", "http://{$item->subdomain}.{$frontend}") ?>
            </li>
        <?php endforeach; ?>

        <?php $url = $arr['main_default_url'] ? $base_frontend : $frontend ?>
        <li class="menu-item menu-item-type-post_type menu-item-object-page">
            <?= Html::a($arr['main'], "http://{$url}") ?>
        </li>
    </ul>
</div>
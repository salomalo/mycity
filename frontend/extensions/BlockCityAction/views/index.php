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
<div>
    <div class="footer-title"><?= $arr['title'] ?> <i class="fa fa-long-arrow-down"></i></div>

    <div class="footer-block-links">
        <?php foreach ($models as $item): ?>
            <?= Html::a("{$arr['label']} {$item->$arr['attr']}", "http://{$item->subdomain}.{$frontend}") ?>
        <?php endforeach; ?>

        <?php $url = $arr['main_default_url'] ? $base_frontend : $frontend ?>
        <?= Html::a($arr['main'], "http://{$url}") ?>
    </div>
</div>
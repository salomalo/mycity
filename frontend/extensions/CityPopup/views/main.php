<?php
/**
 * @var $this \yii\web\View
 * @var $cities City[]
 */

use common\models\City;
use yii\helpers\Html;

$city = Yii::$app->request->city;
?>

<div class="cities">
    <span class="city-title">
        <i class="fa fa-map-marker"></i>
        <?= $this->render($city ? '_title_subdomain' : '_title') ?>
    </span>

    <div class="city-popup">
        <h2><?= Yii::t('widgets', 'Select your city') ?></h2>

        <div class="city-list">
            <?php foreach (Yii::$app->params['cities'][City::ACTIVE] as $curCity) : ?>
                <?= $this->render($city ? '_city_item_subdomain' : '_city_item', ['city' => $curCity]) ?>
            <?php endforeach; ?>

            <?= $this->render($city ? '_item_subdomain' : '_item') ?>
        </div>
    </div>
</div>
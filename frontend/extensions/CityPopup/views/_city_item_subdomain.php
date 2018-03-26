<?php
/**
 * @var $this \yii\web\View
 * @var $city \common\models\City
 */

use yii\helpers\Html;

$front = Yii::$app->params['appFrontend']
?>

<p>
    <?php if ($city->id !== Yii::$app->request->city->id) : ?>
        <?php if (YII_ENV === 'prod') : ?>
            <?= Html::a($city->title, "https://{$city->subdomain}.$front", ['class' => 'change-city']) ?>
        <?php else : ?>
            <?= Html::a($city->title, "http://{$city->subdomain}.$front", ['class' => 'change-city']) ?>
        <?php endif; ?>
    <?php else : ?>
        <span class="active-city"><?= $city->title ?></span>
    <?php endif; ?>
</p>
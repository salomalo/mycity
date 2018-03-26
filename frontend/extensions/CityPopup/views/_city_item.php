<?php
/** 
 * @var $this \yii\web\View
 * @var $city \common\models\City
 */

use yii\helpers\Html;

$front = Yii::$app->params['appFrontend'];
?>

<p>
    <?= Html::a($city->title, "http://{$city->subdomain}.$front", ['class' => 'change-city']) ?>
</p>
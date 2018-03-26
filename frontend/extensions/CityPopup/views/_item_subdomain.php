<?php
/**
 * @var $this \yii\web\View
 */

use yii\helpers\Html;

$front = Yii::$app->params['appFrontend'];
?>

<?php if (YII_ENV === 'prod') : ?>
    <p><?= Html::a('Главная',  "https://$front") ?></p>
<?php else : ?>
    <p><?= Html::a('Главная',  "http://$front") ?></p>
<?php endif; ?>

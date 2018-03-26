<?php
/**
 * @var $this \yii\web\View
 */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<?= Html::a('<span>wishlist</span>', null, [
    'class' => ['btn', 'btn-wishlist', 'btn-add-to-wish-list'],
    'onclick' => 'login("' . Url::to(['/user/security/login-ajax']) . '");return false;',
    'rel' => 'nofollow',
]) ?>

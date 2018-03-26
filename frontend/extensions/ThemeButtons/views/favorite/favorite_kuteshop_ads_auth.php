<?php
/**
 * @var $this \yii\web\View
 * @var $url string
 * @var $id string
 * @var $type integer
 * @var $isMarked boolean
 */
use yii\helpers\Html;
use yii\helpers\Url;

?>

<?= Html::a('<span>В избранное</span>', null, [
    'class' => ['action', 'btn-wishlist', 'btn-add-to-wish-list'],
    'onclick' => 'login("' . Url::to(['/user/security/login-ajax']) . '");return false;',
    'rel' => 'nofollow',
]) ?>

<?php
/**
 * @var $this \yii\web\View
 */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<?= Html::a('<i class="fa fa-heart-o"></i>', null, [
    'class' => ['heart', 'hovicon', 'btn-add-to-wish-list'],
    'onclick' => 'login("' . Url::to(['/user/security/login-ajax']) . '");return false;',
    'rel' => 'nofollow',
]) ?>

<?php
/**
 * @var $this \yii\web\View
 */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<?= Html::a('<i class="fa fa-heart-o"></i> <span>Добавить в избранное</span>', ['/'], [
    'class' => 'inventor-favorites-btn-toggle',
    'onclick' => 'login("' . Url::to(['/user/security/login-ajax']) . '");return false;',
    'rel' => 'nofollow',
]) ?>
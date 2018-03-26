<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\User $user
 */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/account-activate', 'key' => $user->auth_key]);
?>

Здравствуйте <?= Html::encode($user->username) ?>,

Ссылка для активации Вашего аккаунта:

<?= Html::a(Html::encode($resetLink), $resetLink) ?>

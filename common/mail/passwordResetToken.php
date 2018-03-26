<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\User $user
 */

//$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>

Здравствуйте <?= Html::encode($user->username) ?>,

<p>С Вашего аккаунта был запрошен сброс пароля :</p>
<p>Ваш новый пароль: <?=$password?></p>
<p>Не забудьте изменить пароль. Для улучшения безопасности. </p>

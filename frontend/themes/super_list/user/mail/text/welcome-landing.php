<?php
/**
 * @var $url string
 * @var common\models\User $user
 */
?>
<?= Yii::t('user', 'Hello').' '.$user->username ?>!

<?= Yii::t('app', 'Email_msg_thanks_{0}', Yii::$app->name) ?>!
<?= Yii::t('app', 'Email_msg_success') ?>.

<?= Yii::t('app', 'Email_msg_activate') ?>.
<?= $url ?>
<?= Yii::t('user', 'If you cannot click the link, please try pasting the text into your browser') ?>.

<?= Yii::t('app', 'Email_msg_ignore_{0}', Yii::$app->name) ?>.

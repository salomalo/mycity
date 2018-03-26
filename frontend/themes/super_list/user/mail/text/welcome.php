<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var dektrium\user\models\User
 */
?>
<?= Yii::t('user', 'Hello').' '.$user->username ?>!

<?= Yii::t('app', 'Email_msg_thanks_{0}', Yii::$app->name) ?>!
<?= Yii::t('app', 'Email_msg_success') ?>.
<?php if ($module->enableGeneratingPassword): ?>
    <?= Yii::t('user', 'We have generated a password for you') ?>:
    <?= $user->password ?>
<?php endif ?>

<?php if ($token !== null): ?>
    <?= Yii::t('app', 'Email_msg_activate') ?>.
    <?= $token->url ?>
    <?= Yii::t('user', 'If you cannot click the link, please try pasting the text into your browser') ?>.
<?php endif ?>

<?= Yii::t('app', 'Email_msg_ignore_{0}', Yii::$app->name) ?>.

<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
use yii\helpers\Html;

/**
 * @var string $content main view render result
 */
?>

<?php $this->beginPage() ?>
<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>
<?= Yii::t('app', 'Email_msg_respect') ?>
<?= Yii::t('app', 'Email_msg_administration_{0}', Html::a('CityLife', 'https://' . Yii::$app->params['appFrontend'])) ?> © <?= Html::a('CityLife', 'https://' . Yii::$app->params['appFrontend']) ?> <?= date('Y') ?>.
<?php $this->endPage() ?>

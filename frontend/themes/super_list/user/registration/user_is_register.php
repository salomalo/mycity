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
 * @var yii\web\View $this
 */

$this->title = Yii::t('user', 'Sign in');
?>
<div class="row" style="min-height: 500px">
    <div class="col-md-4 col-md-offset-4">
        <p class="text-center" style="margin-top: 50px">
            <?= Html::a(Yii::t('app', 'connect_auth'), ['/user/settings/networks']) ?>.
        </p>
    </div>
</div>

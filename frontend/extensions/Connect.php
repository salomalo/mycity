<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace frontend\extensions;

use yii;
use yii\helpers\Url;
use dektrium\user\widgets\Connect as BaseConnect;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Connect extends BaseConnect
{
    public function init()
    {
        Yii::$app->session->set('urlBeforeConnect', Yii::$app->request->referrer);
        parent::init();
    }

    public function createClientUrl($provider)
    {
        if ($this->isConnected($provider)) {
            return Url::to(['/user/settings/disconnect', 'id' => $this->accounts[$provider->getId()]->id]);
        } else {
            return 'http://' . Yii::$app->params['appFrontend'] . parent::createClientUrl($provider);
        }
    }
}

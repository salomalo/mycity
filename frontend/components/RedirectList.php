<?php

namespace frontend\components;

use common\models\Lang;
use yii;
use yii\web\Request;

/**
 * Description of CityRequest
 *
 * @author dima
 */
class RedirectList extends Request
{
    public function init()
    {
        $req = Yii::$app->request;
        $resp = Yii::$app->response;

        $front = Yii::$app->params['appFrontend'];

        if ((stripos($req->serverName, ".$front") === false) and ($req->serverName !== $front)) {
            $resp->redirect("http://$front", 301)->send();
        }

        if (!$req->isAjax) {
            $url = $req->url;
            $lang = Lang::getCurrent() ? Lang::getCurrent()->url : '';

            if (strripos($url, '?archive=0') !== false) {
                $url = str_replace('?archive=0', '', $url);
                $resp->redirect($url, 301)->send();
            } elseif ($req->pathInfo === 'site') {
                $resp->redirect("/$lang", 301)->send();
            } elseif (strripos($url, 'action?archive=1') !== false) {
                $resp->redirect(['action/index', 'archive' => 'archive'], 301)->send();
            }
        }
        return null;
    }
}

<?php
namespace common\components;

use common\models\Lang;
use yii;
use yii\base\Event;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Request;
use common\models\City;

/**
 * Description of CityRequest
 *
 * @author dima
 */
class CityRequest extends Request
{
    public function init()
    {
        //redirect на адресс с языком(кроме ajax)
        if (!Yii::$app->request->isAjax and !in_array(explode('/', $_SERVER['REQUEST_URI'])[1], Lang::getActive())) {
            $default_lang = Lang::getDefaultLang()->url;
            Yii::$app->response->redirect("http://{$_SERVER['SERVER_NAME']}/{$default_lang}{$_SERVER['REQUEST_URI']}", 301);
        }

        //установка языка
        $url_list = explode('/', $this->url);
        $lang_url = isset($url_list[1]) ? $url_list[1] : null;
        Lang::setCurrent($lang_url);

        $sub = explode('.', $_SERVER['SERVER_NAME']);

        if ((count($sub) > 2) and !in_array($sub[0], ['www', 'site'])) {
            if ($city = Yii::$app->request->city) {

                //Отключение функционала наполняемых городов
                if ($city->main === City::PENDING) {
                    Event::on(Controller::className(), Controller::EVENT_BEFORE_ACTION, function () {
                        $ctrl = Yii::$app->controller;
                        if (in_array($ctrl->id, City::$deniedControllers)) {
                            throw new HttpException(404);
                        } elseif (($ctrl->id === 'site') and ($ctrl->action->id === 'index')) {
                            $ctrl->redirect(['/business/index']);
                        }
                    });
                }

                $params = &Yii::$app->params;
                $params['SUBDOMAINTITLE'] = $city->title;
                $params['SUBDOMAIN_TITLE_GE'] = $city->title_ge;
                $params['SUBDOMAINID'] = (int)$city->id;
                $params['SUBDOMAINREGIONID'] = (int)$city->idRegion;
                $params['SUBDOMAINREGIONTITLE'] = ArrayHelper::getValue($city->region, 'title', '');
                $params['SUBDOMAIN_STATUS'] = (int)$city->main;
            } else {
                unset($sub[0]);
                $url = implode('.', $sub);

                Yii::$app->response->redirect("http://$url", 301);
            }
        }
    }
}

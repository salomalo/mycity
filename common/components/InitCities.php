<?php

namespace common\components;

use yii;
use yii\web\Request;
use common\models\City;

/**
 * Description of CityRequest
 *
 * @author dima
 */
class InitCities extends Request
{
    public function init()
    {
        $params = &Yii::$app->params;
        /** @var City[] $models */
        $models = City::find()->where(['not', ['main' => City::CLOSED]])->orderBy(['main' => SORT_DESC, 'id' => SORT_ASC])->all();
        $cities = [];
        $activeCitys = [];

        $subDomain = null;
        $sub = explode('.', $this->serverName);
        if ((count($sub) > 2) and !in_array($sub[0], ['www', 'backend', 'office'])) {
            $subDomain = $sub[0];
        }

        foreach ($models as $city) {
            $cities[$city->main][$city->id] = $city;

            if ($city->main === City::ACTIVE) {
                $activeCitys[] = $city->id;
            }

            if ($subDomain === $city->subdomain) {
                $params['city'] = $city;
            }
        }

        $params['cities'] = $cities;
        $params['activeCitysBackend'] = $models;
        $params['activeCitys'] = $activeCitys;
    }
}

<?php

namespace backend\components;

use yii;
use yii\helpers\ArrayHelper;
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

        $params['activeCitysBackend'] = City::find()->where(['not', ['main' => City::CLOSED]])->orderBy(['main' => SORT_DESC, 'id' => SORT_ASC])->all();

        $adminCities = Yii::$app->user->identity ? Yii::$app->user->identity->citiesId : [];
        $params['adminCities'] = [];

        /** @var City $city */
        foreach ($params['activeCitysBackend'] as $city) {
            $params['cities'][$city->main][$city->id] = $city;

            if ($city->main === City::ACTIVE) {
                $params['activeCitys'][] = $city->id;
            }

            if (in_array($city->id, $adminCities) or empty($adminCities)) {
                $params['adminCities']['id'][] = $city->id;
                $params['adminCities']['obj'][] = $city;
            }
        }

        $params['adminCities']['select'] = ArrayHelper::map($params['adminCities']['obj'], 'id', 'title');
    }
}

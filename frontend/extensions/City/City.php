<?php

namespace frontend\extensions\City;

use Yii;
use common\models\City as CityModel;
use common\models\Region;
use yii\base\Widget;

/**
 * Description of City
 *
 * @author dima
 */
class City extends Widget
{
    private $modelCity = false;

    public function init()
    {
        parent::init();
        Assets::register($this->view);
    }

    public function run()
    {
        $this->modelCity = Yii::$app->request->city ? Yii::$app->request->city : new CityModel();

        return $this->render('index', [
            'modelCity' => $this->modelCity,
            'cityList' => Yii::$app->params['cities'][CityModel::ACTIVE],
        ]);
    }
}

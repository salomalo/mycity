<?php

namespace backend\extensions\City;

use Yii;
use common\models\City as CityModel;
use yii\base\Widget;

/**
 * Description of City
 *
 * @author dima
 */
class City extends Widget
{
    private $model = false;
    private $modelCity = false;
    private $modelRegion = false;

    public function init()
    {
        parent::init();

        Assets::register($this->view);
    }

    public function run()
    {
        $getCookies = Yii::$app->request->cookies;
        
        if ($getCookies->has('SUBDOMAINID')) {
            $this->modelCity = CityModel::findOne($getCookies->get('SUBDOMAINID'));
        }

        return $this->render('index', ['modelCity' => $this->modelCity ? $this->modelCity : new CityModel()]);
    }
}

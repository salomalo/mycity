<?php

namespace common\models\traits;

use common\models\City;

trait GetCityTrait
{
    /**
     * @return City
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'idCity']);
    }
}
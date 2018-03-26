<?php

namespace common\models\queries;

use Yii;
use yii\mongodb\ActiveQuery;

class AdsQuery extends ActiveQuery
{
    public function city()
    {
        $city = Yii::$app->request->city;
        if ($city) {
            $this->andWhere(['idCity'=>$city->id]);;
        }
        return $this;
    }
}
<?php

namespace common\models\queries;

use Yii;
use yii\db\ActiveQuery;

class BusinessQuery extends ActiveQuery
{
    public function city()
    {
        $city = Yii::$app->request->city;
        if ($city) {
            $this->andWhere('"idCity" = :id_city',[':id_city' => $city->id]);
        }
        return $this;
    }
}
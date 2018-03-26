<?php

namespace common\models\queries;

use Yii;
use yii\db\ActiveQuery;

class ActionQuery extends ActiveQuery
{
    public function city()
    {
        $city = Yii::$app->request->city;
        if ($city) {
            $this->joinWith('companyName');
            $this->andWhere('business."idCity" = :id_city',[':id_city' => $city->id]);
        }
        return $this;
    }
}
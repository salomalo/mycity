<?php

namespace common\models\traits;

use common\models\Business;

trait GetBusinessTrait
{
    /**
     * @return Business
     */
    public function getBusiness()
    {
        return $this->hasOne(Business::className(), ['id' => 'idBusiness']);
    }
}
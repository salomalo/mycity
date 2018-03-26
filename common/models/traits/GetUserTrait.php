<?php

namespace common\models\traits;

use common\models\User;

trait GetUserTrait
{
    /**
     * @return User
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'idUser']);
    }
}
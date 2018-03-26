<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Description of Filter
 *
 * @author dima
 */
class Filter extends ActiveRecord
{
    public static function tableName()
    {
        return 'product_customfield';
    }
}

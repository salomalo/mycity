<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 16.12.2016
 * Time: 12:26
 */

namespace office\models\search;


use Yii;

class Invoice extends \common\models\search\Invoice
{
    protected function getModelQuery()
    {
        return parent::getModelQuery()->andWhere(['user_id' => Yii::$app->user->id]);
    }
}
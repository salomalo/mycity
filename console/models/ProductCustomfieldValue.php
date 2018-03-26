<?php
namespace console\models;

use yii;
use common\models\ProductCustomfieldValue as ProductCustomfieldValueDB;

class ProductCustomfieldValue extends ProductCustomfieldValueDB
{
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    public static function copyFromParser()
    {
        $dbCFVsId = ProductCustomfieldValueDB::find()->select('id')->column();
        $db2CFVsQuery = self::find()->where(['not', ['id' => $dbCFVsId]])->orderBy('id');

        foreach ($db2CFVsQuery->batch() as $db2CFVs) {
            foreach ($db2CFVs as $db2CFV) {
                $dbCFV = new ProductCustomfieldValueDB();
                $dbCFV->attributes = $db2CFV->attributes;
                $dbCFV->save();
            }
        }
    }
}

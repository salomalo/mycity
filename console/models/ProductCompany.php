<?php
namespace console\models;

use yii;
use common\models\ProductCompany as ProductCompanyDB;

class ProductCompany extends ProductCompanyDB
{
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    public static function copyFromParser()
    {
        $dbCompaniesId = ProductCompanyDB::find()->select('id')->column();
        $db2Companies = self::find()->where(['not', ['id' => $dbCompaniesId]])->orderBy('id')->all();

        foreach ($db2Companies as $db2Company) {
            $dbCompany = new ProductCompanyDB();
            $dbCompany->attributes = $db2Company->attributes;
            $dbCompany->save();
        }
    }
}

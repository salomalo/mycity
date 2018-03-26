<?php
namespace console\models;

use common\models\Lang;
use yii;
use common\models\ProductCustomfield as ProductCustomfieldDB;

class ProductCustomfield extends ProductCustomfieldDB
{
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    public static function copyFromParser()
    {
        $dbCustomfieldId = ProductCustomfieldDB::find()->select('id')->column();
        $db2Customfields = self::find()->where(['not', ['id' => $dbCustomfieldId]])->orderBy('id')->all();

        foreach ($db2Customfields as $db2Customfield) {
            $dbCompany = new ProductCustomfieldDB();

            Lang::setCurrent('ru');
            $dbCompany->attributes = $db2Customfield->attributes;
            $dbCompany->save();

            Lang::setCurrent('uk');
            $dbCompany->attributes = $db2Customfield->attributes;
            $dbCompany->save();

            Lang::setCurrent('ru');
        }
    }
}

<?php

namespace console\models;

use yii;
use common\models\ProductCategoryCategory as PCCDB;
use common\models\ProductCategory as ProductCategoryDB;

class ProductCategoryCategory extends PCCDB
{
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    public static function copyFromParser()
    {
        $dbPCCsId = PCCDB::find()->select('id')->column();
        /** @var self[] $db2PCCs */
        $db2PCCs = self::find()->where(['not', ['id' => $dbPCCsId]])->orderBy('id')->all();

        foreach ($db2PCCs as $db2PCC) {
            $dbPCC = new PCCDB();
            $dbPCC->attributes = $db2PCC->attributes;

            /** @var ProductCategory $db2Category */
            $db2Category = $db2PCC->ProductCategory ? ProductCategory::findOne($db2PCC->ProductCategory) : null;
            /** @var ProductCategory $db2RootCategory */
            $db2RootCategory = $db2Category ? $db2Category->parents(1)->one() : null;
            /** @var ProductCategoryDB $dbRootCategory */
            $dbRootCategory = $db2RootCategory ? ProductCategoryDB::find()->where(['like', 'title', addslashes($db2RootCategory->title)])->one() : null;
            /** @var ProductCategoryDB $dbCategory */
            $dbCategory = $dbRootCategory ? $dbRootCategory->children()->where(['like', 'title', addslashes($db2Category->title)])->one() : null;

            $dbPCC->ProductCategory = $dbCategory ? $dbCategory->id : null;
            $dbPCC->save();
        }
    }
}

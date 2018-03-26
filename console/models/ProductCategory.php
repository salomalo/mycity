<?php
namespace console\models;

use common\models\Lang;
use yii;
use common\models\ProductCategory as ProductCategoryDB;

class ProductCategory extends ProductCategoryDB
{
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    public static function copyFromParser()
    {
        /**
         * Создает новый объект
         *
         * @param ProductCategoryDB|null $dbParent
         * @param ProductCategory $db2Category
         *
         * @return ProductCategoryDB
         */
        $cloneObject = function ($dbParent, $db2Category) {
            $dbCategory = new ProductCategoryDB();

            Lang::setCurrent('ru');
            $dbCategory->attributes = $db2Category->attributes;
            $dbParent ? $dbCategory->appendTo($dbParent) : $dbCategory->makeRoot();

            Lang::setCurrent('uk');
            $dbCategory->attributes = $db2Category->attributes;
            $dbCategory->save();

            Lang::setCurrent('ru');

            return $dbCategory;
        };

        /**
         * Рекурсивно обходим детей и создаем новые объекты
         *
         * @param ProductCategoryDB $dbParent
         * @param ProductCategory $db2Parent
         */
        $foreachChildren = function ($dbParent, $db2Parent) use (&$foreachChildren, &$cloneObject) {
            /** @var ProductCategory[] $db2Children */
            $db2Children = $db2Parent->children(1)->all();

            foreach ($db2Children as $db2Category) {
                $dbCategory = $dbParent->children(1)->where(['like', 'title', addslashes($db2Category->title)])->one();
                if (!$dbCategory) {
                    $dbCategory = $cloneObject($dbParent, $db2Category);
                }

                $foreachChildren($dbCategory, $db2Category);
            }
        };
        
        /** @var self[] $db2Roots */
        $db2Roots = self::find()->roots()->all();

        foreach ($db2Roots as $db2Category) {
            $dbCategory = ProductCategoryDB::find()->roots()->where(['like', 'title', addslashes($db2Category->title)])->one();
            if (!$dbCategory) {
                $dbCategory = $cloneObject(null, $db2Category);
            }

            $foreachChildren($dbCategory, $db2Category);
        }
    }
}

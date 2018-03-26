<?php
namespace console\models;

use common\models\ProductCategory as ProductCategoryDB;
use yii;
use common\models\Product as ProductDB;

class Product extends ProductDB
{
    public static function getDb()
    {
        return Yii::$app->get('mongodb2');
    }

    public static function copyFromParser()
    {
        $db2Count = self::find()->count();

        for ($i = 0; $i < $db2Count; $i++) {
            /** @var self[] $db2Products */
            $db2Products = self::find()->limit(100)->offset(100 * $i)->all();

            foreach ($db2Products as $db2Product) {
                $dbCount = ProductDB::find()
                    ->where(['title' => $db2Product->title])
                    ->andWhere(['idCompany' => $db2Product->idCompany])
                    ->andWhere(['image' => $db2Product->image])
                    ->count();

                if (0 === $dbCount) {
                    /** @var yii\mongodb\Collection $collection */
                    $collection = Yii::$app->mongodb->getCollection('product');
                    $collectionValues = $db2Product->attributes;

                    unset($collectionValues['_id']);

                    /** @var ProductCategory $db2Category */
                    $db2Category = $db2Product->idCategory ? ProductCategory::findOne($db2Product->idCategory) : null;
                    /** @var ProductCategory $db2RootCategory */
                    $db2RootCategory = $db2Category ? $db2Category->parents(1)->one() : null;
                    /** @var ProductCategoryDB $dbRootCategory */
                    $dbRootCategory = $db2RootCategory ? ProductCategoryDB::find()->where(['like', 'title', addslashes($db2RootCategory->title)])->one() : null;
                    /** @var ProductCategoryDB $dbCategory */
                    $dbCategory = $dbRootCategory ? $dbRootCategory->children()->where(['like', 'title', addslashes($db2Category->title)])->one() : null;

                    if (!$dbCategory) {
                        $dbCategory = ProductCategory::find()->roots()->where(['like', 'title', 'Без категории'])->one();
                        if (!$dbCategory) {
                            $dbCategory = new ProductCategory(['title' => 'Без категории']);
                            $dbCategory->makeRoot();
                        }
                    }

                    $collectionValues['idCat'] = $dbCategory->id;

                    @$collection->insert($collectionValues);
                }
            }
        }
    }
}

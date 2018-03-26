<?php
namespace console\controllers;

use common\models\Product;
use common\models\ProductCategory;
use common\models\ProductCategoryCategory;
use common\models\ProductCompany;
use yii;
use yii\console\Controller;
use common\models\Lang;
use console\models\ProductCategory as ProductCategoryConsole;
use console\models\ProductCategoryCategory as ProductCategoryCategoryConsole;
use console\models\ProductCustomfieldValue as ProductCustomfieldValueConsole;
use console\models\ProductCompany as ProductCompanyConsole;
use console\models\ProductCustomfield as ProductCustomfieldConsole;
use console\models\Product as ProductConsole;
use console\models\File as FileConsole;

class ProductController extends Controller
{
    public function actionCopyFromParse()
    {
        Lang::setCurrent('ru');
        
        echo 'Processing categories', PHP_EOL;
//        ProductCategoryConsole::copyFromParser();
        echo 'Processing companies', PHP_EOL;
//        ProductCompanyConsole::copyFromParser();
        echo 'Processing customfield', PHP_EOL;
//        ProductCustomfieldConsole::copyFromParser();
        echo 'Processing customfield value', PHP_EOL;
//        ProductCustomfieldValueConsole::copyFromParser();
        echo 'Processing category-company', PHP_EOL;
//        ProductCategoryCategoryConsole::copyFromParser();
        echo 'Processing product', PHP_EOL;
        ProductConsole::copyFromParser();
        echo 'Processing files', PHP_EOL;
        FileConsole::copyFromParser();
    }

    public function actionClearRepeatProducts()
    {
        $count = Product::find()->count();
        echo 'У нас ', $count, ' продуктов', PHP_EOL;

        for ($i = 0; $i < $count; $i++) {
            /** @var Product $product */
            $product = Product::find()->select(['title', 'idCategory', 'idCompany', 'image'])->orderBy('id')
                ->limit(1)->offset($i)
                ->asArray()->one();
            if (!$product) {
                break;
            }

            /** @var Product[] $duplicates */
            $duplicates = Product::find()
                ->where(['like', 'title', addslashes($product['title'])])
                ->andWhere(['idCategory' => $product['idCategory']])
                ->andWhere(['idCompany' => $product['idCompany']])
                ->andWhere(['image' => $product['image']])
                ->orderBy(['dateUpdate' => SORT_ASC])
                ->all();

            if (count($duplicates) > 1) {
                unset($duplicates[0]);
                foreach ($duplicates as $duplicate) {
                    echo $duplicate->delete() ? '.' : ',';
                }
            }
        }
        echo 'У нас на ', $count - Product::find()->count(), ' меньше продуктов', PHP_EOL;
    }

    public function actionSetDefaultCat($id)
    {
        $query = Product::find();
        $count = $query->count();
        $query->limit(100);

        for ($i = 0; $i < $count; $i += 100) {
            /** @var Product[] $models */
            $models = $query->offset($i)->all();

            foreach ($models as $model) {
                if (!$model->idCategory || (ProductCategory::find()->where(['id' => $model->idCategory])->count() === 0)) {
                    $model->idCategory = $id;
                    if (!$model->save(true, ['idCategory'])) {
                        var_dump($model->errors);
                    }
                }
            }
            echo '.';
        }
        echo PHP_EOL;
    }

    /**
     * Проходим по всем компаниям и добавляем им отсутствующие связи с категориями из бд парсера
     */
    public function actionFixCompanyCategoryLink()
    {
        $companiesQuery = ProductCompany::find()->orderBy('id');
        echo PHP_EOL;

        /** @var ProductCompany[] $companies */
        foreach ($companiesQuery->batch() as $companies) {
            foreach ($companies as $company) {

                /** @var ProductCompanyConsole $db2Company */
                $db2Company = ProductCompanyConsole::find()->where(['title' => $company->title])->one();
                if (!$db2Company) {
                    continue;
                }

                $db2CategoriesId = ProductCategoryCategoryConsole::find()->select('ProductCategory')->where(['ProductCompany' => $db2Company->id]);
                $db2Categories = ProductCategoryConsole::findAll(['id' => $db2CategoriesId]);

                if (!$db2Categories) {
                    continue;
                }

                /** @var ProductCategory[] $dbCategoriesFromDb2 */
                $dbCategoriesFromDb2 = [];
                foreach ($db2Categories as $db2Category) {
                    /** @var ProductCategory $cat */
                    $cat = ProductCategory::find()->where(['like', 'title', addslashes($db2Category->title)])->one();
                    if ($cat) {
                        $dbCategoriesFromDb2[] = $cat;
                    }
                }
                $dbCategoriesId = $company->getCategories()->select('id')->column();

                if ($dbCategoriesId) {
                    //Удаляем категории, связи с которыми уже есть
                    $dbCategoriesFromDb2 = array_filter($dbCategoriesFromDb2, function ($v) use ($dbCategoriesId) {
                        /** @var ProductCategory $v */
                        return (!is_object($v) || in_array($v->id, $dbCategoriesId));
                    });
                }

                if (!$dbCategoriesFromDb2) {
                    continue;
                }

                foreach ($dbCategoriesFromDb2 as $dbCategory) {
                    $link = new ProductCategoryCategory([
                        'ProductCategory' => $dbCategory->id,
                        'ProductCompany' => $company->id,
                    ]);

                    if ($link->save()) {
                        echo $link->ProductCompany, ' -> ', $link->ProductCategory, PHP_EOL;
                    }
                }
                echo PHP_EOL;
            }
        }
    }
}

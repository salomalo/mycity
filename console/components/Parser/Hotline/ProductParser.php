<?php
namespace console\components\Parser\Hotline;

use common\models\Lang;
use common\models\LogParse;
use common\models\Product;
use common\models\ProductCategory;
use common\models\ProductCategoryCategory;
use common\models\ProductCompany;
use common\models\ProductCustomfield;
use common\models\ProductCustomfieldValue;
use console\components\Parser\HttpParser;
use dosamigos\transliterator\TransliteratorHelper;
use Exception;
use yii;
use yii\helpers\Inflector;
use yii\helpers\Json;

class ProductParser extends HttpParser
{
    /** @var ProductCategory $catalog */
    public $catalog;
    
    public $log;
    
    public function parseData()
    {
        if (empty($this->contents['ru']) or empty($this->contents['uk'])) {
            return $this;
        }

        $document_ru = $this->contents['ru'];
        $document_uk = $this->contents['uk'];

        //Кастомфилды в таблице
        $table_ru = $document_ru->find('table.cf > tr');
        $table_uk = $document_uk->find('table.cf > tr');

        //Удаляем категорию и див из заголовка и берем его
        $document_ru->find('div.txt-center > h1 > span')->remove();
        $document_ru->find('div.txt-center > h1 > div')->remove();

        $title_ru = trim($document_ru->find('div.txt-center > h1')->text());

        $model_ru = $title_ru;

        $image = $document_ru->find('div.block-img-gall > a')->attr('href');
        
        if ($image) {
            if (stripos($image, 'http://') === false) {
                $image = "http://hotline.ua{$image}";
            }
            try {
                $headers = get_headers($image, 1);
                if (strripos($headers[0], '200') !== false) {

                    $file = basename($image);
                    if ($content = file_get_contents($image)) {
                        $f = fopen($file, 'w');
                        fwrite($f, $content);
                        fclose($f);
                    }
                    $image = Yii::$app->files->uploadFromUrl(new Product(), 'image', ['file' => $file, 'filesize' => $headers['Content-Length'], 'mimeType' => $headers['Content-Type']]);
                    unlink($file);
                }
            } catch (Exception $e) {
                $image = '';
                echo 'Error while download image: ', $e->getMessage(), PHP_EOL;
            }

        } else {
            $image = '';
        }

        $desc = trim(strip_tags($document_ru->find('p.full-desc')->text()));

        $cf = [];
        $developer = 'Неизвестный производитель';

        //Парсим кастомфилды
        for ($i = 0; $i < count($table_ru->elements); $i++) {
            if (!isset($table_ru->elements[$i]) or !isset($table_uk->elements[$i])) {
                continue;
            }
            $tr_ru = pq($table_ru->elements[$i]);
            $tr_uk = pq($table_uk->elements[$i]);

            //Удаляем (i) - всплывающую подсказку в имени кастомфилда
            $tr_ru->find('th > span > span')->remove();
            $tr_uk->find('th > span > span')->remove();

            //Получаем имя кастомфилда
            $th_ru = trim($tr_ru->find('th > span')->text());
            $th_uk = trim($tr_uk->find('th > span')->text());

            //Получаем значение кастомфилда(текст из ссылки или текст)
            if ($td_ru = trim($tr_ru->find('td > span > a')->text())) {
                $td_uk = trim($tr_uk->find('td > span > a')->text());
            } else {
                $td_ru = trim($tr_ru->find('td > span')->text());
                $td_uk = trim($tr_uk->find('td > span')->text());
            }

            //Проверяем и сохраняем кастомфилд
            if (in_array($th_ru, ['Ссылка на сайт производителя'])) {
                continue;
            } elseif (($th_ru === 'Производитель') and $td_ru) {
                $model_ru = trim(str_replace($td_ru, '', $title_ru));
                $developer = $td_ru;
            } elseif ($th_ru and $td_ru) {
                $cf[] = ['title' => ['ru' => $th_ru, 'uk' => $th_uk], 'value' => ['ru' => $td_ru, 'uk' => $td_uk]];
            }
        }

        //Создаем записи в бд
        $company = $this->getCompany($developer);
        $custom_fields = [];
        
        foreach ($cf as $field) {
            $custom_field = $this->getCustomField($field['title'], $this->catalog->id);
            if ($custom_field) {
                $this->createCategoryCompanyLink($this->catalog->id, $company->id);
                $custom_fields[] = $this->createCFValue($custom_field->id, $field['value']);
            }
        }
        $this->createProduct($title_ru, $desc, $model_ru, $image, $this->catalog->id, $company->id, $custom_fields);

        return $this;
    }

    private function getCompany($title)
    {
        if (!($company = ProductCompany::find()->where(['title' => $title])->one())) {
            echo "+ Create new Company $title", PHP_EOL;
            $company = new ProductCompany(['title' => $title]);
            $company->save();
        }
        return $company;
    }

    private function getCustomField($title, $category)
    {
        $search_full = Json::encode(['ru-RU' => $title['ru'], 'uk-Uk' => $title['uk']]);
        $custom_field = ProductCustomfield::find()->where(['title' => $search_full])->one();

        if (!$custom_field) {
            $custom_field = ProductCustomfield::find()->where(['alias' => $this->slug($title['ru'])])->one();

            if (!$custom_field) {
                $custom_field = ProductCustomfield::find()
                    ->where(['~~*', 'title', $title['ru']])->andWhere(['~~*', 'title', $title['uk']])
                    ->one();

                if (!$custom_field) {
                    $custom_field = ProductCustomfield::find()
                        ->where(['~~*', 'title', $title['ru']])->orWhere(['~~*', 'title', $title['uk']])
                        ->one();

                    if (!$custom_field) {
                        echo "+ Create new CF {$this->slug($title['ru'])}", PHP_EOL;
                        Lang::setCurrent('ru');

                        $custom_field = new ProductCustomfield([
                            'title' => $title['ru'],
                            'idCategory' => $category,
                            'alias' => $this->slug($title['ru']),
                            'type' => ProductCustomfield::TYPE_STRING,
                            'isFilter' => 0,
                        ]);

                        if (!$custom_field->save()) {
                            var_dump($custom_field->errors);
                        } else {
                            Lang::setCurrent('uk');
                            $custom_field->updateAttributes(['title' => $title['uk']]);
                        }
                        Lang::setCurrent('ru');
                    }
                }
            }
        }

        return $custom_field;
    }

    private function createCategoryCompanyLink($cat_id, $comp_id)
    {
        if (ProductCategoryCategory::find()->where(['ProductCategory' => $cat_id, 'ProductCompany' => $comp_id])->count() === 0) {
            $product_cat_comp = new ProductCategoryCategory([
                'ProductCategory' => $cat_id,
                'ProductCompany' => $comp_id,
            ]);
            $product_cat_comp->save();
        }
    }

    private function slug($string)
    {
        return Inflector::slug(TransliteratorHelper::process($string), '', true);
    }

    private function createCFValue($cf, $value)
    {
        if (!($product_cat_comp = ProductCustomfieldValue::find()->where(['idCustomfield' => $cf, 'value' => $value['ru']])->one())) {
            $product_cat_comp = new ProductCustomfieldValue([
                'idCustomfield' => $cf,
                'value' => $value['ru'],
            ]);
            $product_cat_comp->save();
        }

        return $product_cat_comp;
    }

    private function createProduct($title, $desc, $model, $image, $category, $company, $custom_fields)
    {
        $product = Product::find()->where(['title' => $title])->count();
        if ($product === 0) {
            $collection = Yii::$app->mongodb->getCollection('product');

            $collectionValues = [
                'title' => $title,
                'description' => $desc,
                'model' => $model,
                'image' => $image,
                'idCategory' => $category,
                'idCompany' => $company,
            ];

            /** @var ProductCustomFieldValue $custom_field */
            foreach ($custom_fields as $custom_field) {
                $collectionValues[$custom_field->customField->alias] = $custom_field->value;
            }
            try {
                if ($collection->insert($collectionValues)) {
                    $this->dbLog($this->link, $title);
                    echo '.';
                }
            } catch (Exception $e) {
                $this->dbLog($this->link, $title, true);
                echo ',';
            }
        }
    }
    
    private function dbLog($url, $product, $fail = false)
    {
        $log = new LogParse();
        $log->url = $url;
        $log->idProduct = $product;
        $log->message = $this->log;
        $log->isFail = $fail ? 1 : 0;
        $log->save();
    }
}

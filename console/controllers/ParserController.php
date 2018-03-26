<?php
namespace console\controllers;

use InvalidArgumentException;
use yii;
use yii\base\ErrorException;
use yii\console\Controller;
use common\models\Product;
use common\models\ProductCategory;
use common\models\ProductCustomfield;
use common\models\ProductCustomfieldValue;
use common\models\ProductCompany;
use common\models\LogParse;
use common\models\File;

/**
 * ParserController parse HotLine products
 */
class ParserController extends Controller
{
    public $startPodCat = false;
    public $startPodPodCat = false;
    public $startPage = false;
    public $idProduct;
    public $url;
    public $companyName;
    public $idMenu;
    public $noCustomfield = ['Товар на сайте производителя', 'Производитель', "&#173", '.'];

    public function actionHotLine($parse_cat = -1, $podCat = false, $podPodCat = false, $page = null)
    {
        $this->startPodCat = $podCat ? true : false;
        $this->startPodPodCat = $podPodCat ? true : false;
        $this->startPage = (($page != null) && ($page > 1)) ? true : false;

        $site = @file_get_contents("http://m.hotline.ua/");

        if (!$site) {
            throw new InvalidArgumentException("no site \n");
        }

        $match = $this->getPage("/<a href=\"(.*?)\" class=\"\"><b>(.*?)<\/b><\/a>/iu", $site);

        foreach ($match[2] as $key => $value) { // главные категории

            $link_main = $match[1][$key];

            $id_root = $this->saveCategory($value);

            $this->log("$key - $value ($link_main) - (id - $id_root) - $podCat\n"); //0 - Авто и Мото - /auto/

            if (($key >= $parse_cat || $parse_cat == -1) && $parse_cat != 7 && $key != 7) {
                $site = @file_get_contents("http://m.hotline.ua{$link_main}");

                $this->idMenu = $key;

                $match2 = $this->getPage("/<a href=\"(.*?)\" class=\"razd_title\"><b>(.*?)<\/b><\/a><\/h5>/iu", $site); // подкатегории

                preg_match_all('~<ul.*?>(.+?)</ul>~is', $site, $matches);

                foreach ($match2[2] as $key2 => $value1) {

                    if ($value1 == $podCat) {
                        $this->startPodCat = false;
                    }

                    $this->log("\t{$key2} - {$value1}\n");

                    if (!$this->startPodCat) {
                        $id_pod_cat = $this->saveCategory($value1, $id_root);

                        preg_match_all("~<li><a href=\"(.*?)\"><b>(.*?)<\/b><\/a><\/li>~iu", $matches[1][$key], $list);

                        foreach ($list[2] as $key3 => $value2) {

                            if ($value2 == $podPodCat) {
                                $this->startPodPodCat = false;
                            }

                            if (!$this->startPodPodCat) {
                                $id_cat_prod = $this->saveCategory($value2, $id_pod_cat);

                                $url = preg_replace('#http://m.hotline.ua#is', '', $list[1][$key3]);

                                $content = $this->getContent($url); // получаем ссылки с 1-ой страницы

                                if (!$this->startPage) {
                                    $breadcrumbs = "$value/$value1/$value2 - Страница: 1 из {$content[0]}";
                                    $this->log("\t\t{$breadcrumbs}\n");
                                    $this->log(" ************************************************************************************\n");
                                    $this->getLinksContent($content, $id_cat_prod, $breadcrumbs); // парсим ссылки с 1-ой страницы
                                }

                                if ($content[0] > 0) { // проходим по следующим страницам
                                    for ($i = 1; $i <= $content[0]; $i++) {

                                        if (($i + 1) == $page && $this->startPage) {
                                            $this->startPage = false;
                                        }

                                        if (!$this->startPage) {
                                            $breadcrumbs = "$value/$value1/$value2 - Страница: " . ($i + 1) . " из {$content[0]}";
                                            $this->log("$breadcrumbs \n");
                                            $this->log(" ************************************************************************************\n");
                                            $array = $this->getContent($url, $i);
                                            $this->getLinksContent($array, $id_cat_prod, $breadcrumbs);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function getPage($reg, $site)
    {
        preg_match_all($reg, $site, $match);

        return $match;
    }

    private function saveCategory($title, $pid = null)
    {
        $model = ProductCategory::find()->where(['title' => $title])->one();
        if (!$model) {
            $model = new ProductCategory();
            $model->title = $title;
            $model->image = '';
            $model->pid = $pid;
            $model->save();
        }
        return $model->id;
    }

    private function getContent($url, $page = null)
    {
        if (!$page) {
            $site = @file_get_contents("http://hotline.ua" . $url . '?catmode=lines');
            $site = iconv('cp1251', 'utf-8', $site);

            preg_match_all('~<div class="pager">(.+?)</div>~is', $site, $match);

            $titles = [];
            $type = '';

            if (empty($match[1][0])) {
                return [-1, $titles, $type]; // страница пустая
            }

            preg_match_all('~<a href=\"(.+?)\">(\d+)</a>~is', $match[1][0], $matches);

            if (strstr($url, 'knigi')) {
                $titles = $this->getTitlesKnigi($site);
                $type = 'knigi';
            } else {
                $titles = $this->getTitles($site);
            }

            if (!empty($matches[2])) {
                return [($matches[2][count($matches[2]) - 1]) - 1, $titles, $type]; //  несколько страниц с записями
            }
            return [0, $titles, $type]; // одна страница с записями
        } else {
            $site = @file_get_contents("http://hotline.ua" . $url . '?p=' . $page);
            $site = iconv('cp1251', 'utf-8', $site);
            $type = '';

            if (strstr($url, 'knigi')) {
                $titles = $this->getTitlesKnigi($site);
                $type = 'knigi';
            } else {
                $titles = $this->getTitles($site);
            }

            return [$page, $titles, $type];
        }
    }

    private function getTitlesKnigi($site)
    {
        preg_match_all('~<h2>(.+?)</h2>~is', $site, $temp);
        $res = [];
        foreach ($temp[1] as $item) {
            preg_match_all('/<a href=\"(.*?)\">(<span (.*?)>)?(.*?)(<\/span>)?<\/a>/iu', $item, $match);
            $res[] = $match;
        }

        return $res;
    }

    private function getTitles($site)
    {
        preg_match_all('~<h3>(.+?)</h3>~is', $site, $temp);
        $res = [];
        foreach ($temp[1] as $item) {
            preg_match_all('/href=\"(.*?)\">(.*?)\n(.*?)<\/a>/iu', $item, $match);
            if (!empty($match[0][0])) {
                $res[] = $match;
            }
        }

        foreach ($temp[1] as $item) {
            preg_match_all('/<a  href=\"(.*?)\">(.*?)<\/a>(.*?)/iu', $item, $match);
            if (!empty($match[0][0])) {
                $res[] = $match;
            }
        }

        return $res;
    }

    private function getLinksContent($content, $id_cat_prod, $breadcrumbs)
    {
        if (!empty($content[1])) {
            foreach ($content[1] as $item) { // проходим по ссылкам на товары

                $title_tovar = '';
                $link_tovar = '';

                if ($content[2] == '') {
                    if (!empty($item[2][0])) {
                        $title_tovar = $item[2][0];
                        $link_tovar = $item[1][0];
                    }
                }

                if ($content[2] == 'knigi') {
                    if (!empty($item[4][0])) {
                        $title_tovar = $item[4][0];
                        $link_tovar = $item[1][0];
                    }
                }

                echo (!empty($title_tovar)) ? "\t\t\t" . $title_tovar . ' - ' . $link_tovar . "\n" : '';

                if ($title_tovar != '') {
                    $this->getTovar($title_tovar, $link_tovar, $id_cat_prod, $content[2], $breadcrumbs);
                }
            }
        }
    }

    private function getTovar($title_tovar, $url, $id_cat_prod, $type, $breadcrumbs)
    {
        if (strpos($url, '/url=http://hotline.ua')) {
            $temp = explode('/url=http://hotline.ua', $url);
            $url = $temp[1];
        }
        $this->url = $url;
        $this->idProduct = $title_tovar;

        $site = @file_get_contents("http://hotline.ua" . $url);
        $site = iconv('cp1251', 'utf-8', $site);

        if ($type == 'knigi') {
            preg_match_all('~<div class="th-tabl">(.+?)</div>~is', $site, $temp1);
            preg_match_all('~<table cellpadding="0" cellspacing="0">(.+?)</table>~is', $temp1[1][0], $temp);
        } else {
            preg_match_all('~<table style="display: none;" id="full-props-list">(.+?)</table>~is', $site, $temp);

            preg_match_all('~<div class="inner-g" (.+?)>(.+?)</div>~is', $site, $tempImg); // основное фото
            if (is_array($tempImg) && !empty($tempImg[2][0])) {
                preg_match_all('~<img src="(.+?)" (.+?)>~is', $tempImg[2][0], $tempImg);
            }
        }

        if (empty($temp[0][0])) {
            return;
        }

        $prop = preg_replace('/ {2,}/', ' ', $temp[0][0]);

        preg_match_all('~<tr>(.+?)</tr>~is', $prop, $match);

        if (empty($match[1][0])) {
            return;
        }

        $prefix = '';

        $collection = Yii::$app->mongodb->getCollection('product');
        $collectionValues = [];
        $collectionValues['title'] = $title_tovar;
        $collectionValues['idCategory'] = $id_cat_prod;

        foreach ($match[1] as $key => $value) {

            preg_match_all("/<th class=\"title\" colspan=\"2\">(.*?)<\/th>/iu", $value, $cat);

            if ($type == 'knigi') {
                preg_match_all('~<th><span>(.+?)</span></th>\n(.+?)<td>(.+?)</td>~is', $value, $matches);
            } else {
                preg_match_all('~<th>\n <span>(.+?)</span>\n </th>\n(.+?)<td><span>(.+?)</span>~is', $value, $matches);
            }

            if (!empty($cat[1][0]) && $cat[1][0] != "&#173") {
                $prefix = $cat[1][0] . '_';
            }

            if (!empty($matches[1][0])) {
                $customfield = $this->cleanValue(strip_tags($matches[1][0]));
                $customfield_value = $this->cleanValue(strip_tags(trim($matches[3][0])));

                if ($key == 0) {

                    if ($type == 'knigi') {
                        $collectionValues['idCompany'] = 0;
                    } else {
                        $collectionValues['idCompany'] = $this->saveCompany($customfield_value);
                        $this->companyName = $customfield_value;
                    }

                }

                $this->log("\t\t\t\t" . $prefix . $customfield . ' - ' . $customfield_value . "\n");

                if (!array_search($customfield, $this->noCustomfield)) {
                    $customfieldNew = $this->saveCustomfield($prefix . $customfield, $id_cat_prod);
                    $this->saveCustomfieldValue($customfield_value, $customfieldNew['id']);

                    $collectionValues[$customfieldNew['alias']] = $customfield_value;
                }
            }
        }

        $collectionValues['model'] = str_replace($this->companyName . ' ', '', $collectionValues['title']);

        $model = Product::find()->where(['title' => $title_tovar])->one();
        if (!$model) {
            if (is_array($tempImg) && !empty($tempImg[1])) {
                $img = $this->img('http://hotline.ua' . $tempImg[1][0]);
                $file = \Yii::$app->files->uploadFromUrl(new Product(), 'image', $img);
                if (!empty($file)) {
                    $collectionValues['image'] = $file;
                    unlink($img['file']);
                }

                if (count($tempImg[1]) > 1) {
                    $imgGal = [];
                    for ($i = 1; $i < count($tempImg[1]); $i++) {
                        $img = $this->img('http://hotline.ua' . $tempImg[1][$i]);
                        $fileGal = \Yii::$app->files->uploadFromUrl(new Product(), 'gallery', $img);
                        $imgGal[] = $fileGal;
                        unlink($img['file']);
                    }
                    $collectionValues['gallery'] = $imgGal;
                }
            }

            if (!($id = $collection->insert($collectionValues))) {
                //$this->log("Product error {$this->categoryName} - {$this->companyName} - {$title_tovar}\n\n", true, true);
                $this->log("Product error: {$breadcrumbs}\n\n", true, true);
                throw new ErrorException("Product from Hot-Line not saved");
            } else {
                if (!empty($file)) {
                    $product = Product::find()->select(['_id'])->where(['image' => $file])->one();
                    $model = File::find()->where(['name' => $file])->one();
                    if ($model) {
                        $model->pidMongo = $product->_id;
                        $model->update();
                    }
                }

                if (!empty($imgGal)) {
                    foreach ($imgGal as $item) {
                        $model = File::find()->where(['name' => $item])->one();
                        if ($model) {
                            $model->pidMongo = $product->_id;
                            $model->update();
                        }
                    }
                }

                //$this->log("Product added {$this->categoryName} - {$this->companyName} - {$title_tovar}\n\n", false, true);
                $this->log("Product added: {$breadcrumbs}\n", false, true);
                $this->lastProduct($breadcrumbs);
            }
        } else {
            $this->log("Product: {$breadcrumbs} already saved \n\n", true, true);
        }

        $this->log("\n");
    }

    public function lastProduct($breadcrumbs)
    {
        $file = "lastProduct.log";

        preg_match_all("/(.*?) \/ (.*?) \/ (.*?) - Страница: (\d+) из (\d+)/iu", $breadcrumbs, $match);

        $str = $this->idMenu . ";" . $match[2][0] . ";" . $match[3][0] . ";" . $match[4][0] . ';';

        $f = fopen("$file", "w");
        if (fwrite($f, $str) === FALSE) {
            echo "\e[31mНе могу сохранить файл. \e[0m\n";
        } else {
            echo "\e[32mФайл $file записан. \e[0m\n";
        }
    }

    public function img($url)
    {
        if (!empty($url)) {
            $file = basename($url);
            if (file_get_contents($url)) {
                $content = file_get_contents($url);
                $f = fopen("$file", "w");
                if (fwrite($f, $content) === FALSE) {
                    echo "Не могу произвести запись в файл.";
                    return;
                } else {
                    $filesize = filesize($file);
                    $mimeType = mime_content_type($file);
                    echo "\033[32m" . "Файл " . $file . " записан. " . $filesize . " байт. mime: " . $mimeType . "\033[0m" . "\n";
                    fclose($f);
                    return ['file' => $file, 'filesize' => $filesize, 'mimeType' => $mimeType];
                }
            } else echo "\e[31m" . "Не могу качать файл. \e[0m" . "\n";
        }
    }

    public function cleanValue($value)
    {
        $value = trim($value, " \n");
        return $value;
    }

    private function saveCompany($companyName)
    {
        $company = ProductCompany::find()->where(['title' => $companyName])->one();
        if (!$company) {
            $company = new ProductCompany();
            $company->title = $companyName;
            $company->save();
        }
        return $company->id;
    }

    private function saveCustomfield($title, $id_cat_prod)
    {
        $customfield = ProductCustomfield::find()->where(['title' => $title, 'idCategory' => $id_cat_prod])->one();
        if (!$customfield) {
            $customfield = new ProductCustomfield();
            $customfield->title = $title;
            $customfield->idCategory = $id_cat_prod;
            $customfield->alias = $this->encodeString($title);
            $customfield->isFilter = 0;
            $customfield->type = 0;
            $customfield->save();
        }
        return [
            'id' => $customfield->id,
            'alias' => $customfield->alias
        ];
    }

    function encodeString($string, $gost = false)
    {
        if ($gost) {
            $replace = array("А" => "A", "а" => "a", "Б" => "B", "б" => "b", "В" => "V", "в" => "v", "Г" => "G", "г" => "g", "Д" => "D", "д" => "d",
                "Е" => "E", "е" => "e", "Ё" => "E", "ё" => "e", "Ж" => "Zh", "ж" => "zh", "З" => "Z", "з" => "z", "И" => "I", "и" => "i",
                "Й" => "I", "й" => "i", "К" => "K", "к" => "k", "Л" => "L", "л" => "l", "М" => "M", "м" => "m", "Н" => "N", "н" => "n", "О" => "O", "о" => "o",
                "П" => "P", "п" => "p", "Р" => "R", "р" => "r", "С" => "S", "с" => "s", "Т" => "T", "т" => "t", "У" => "U", "у" => "u", "Ф" => "F", "ф" => "f",
                "Х" => "Kh", "х" => "kh", "Ц" => "Tc", "ц" => "tc", "Ч" => "Ch", "ч" => "ch", "Ш" => "Sh", "ш" => "sh", "Щ" => "Shch", "щ" => "shch",
                "Ы" => "Y", "ы" => "y", "Э" => "E", "э" => "e", "Ю" => "Iu", "ю" => "iu", "Я" => "Ia", "я" => "ia", "ъ" => "", "ь" => "");
        } else {
            $arStrES = array("ае", "уе", "ое", "ые", "ие", "эе", "яе", "юе", "ёе", "ее", "ье", "ъе", "ый", "ий");
            $arStrOS = array("аё", "уё", "оё", "ыё", "иё", "эё", "яё", "юё", "ёё", "её", "ьё", "ъё", "ый", "ий");
            $arStrRS = array("а$", "у$", "о$", "ы$", "и$", "э$", "я$", "ю$", "ё$", "е$", "ь$", "ъ$", "@", "@");

            $replace = array("А" => "A", "а" => "a", "Б" => "B", "б" => "b", "В" => "V", "в" => "v", "Г" => "G", "г" => "g", "Д" => "D", "д" => "d",
                "Е" => "Ye", "е" => "e", "Ё" => "Ye", "ё" => "e", "Ж" => "Zh", "ж" => "zh", "З" => "Z", "з" => "z", "И" => "I", "и" => "i",
                "Й" => "Y", "й" => "y", "К" => "K", "к" => "k", "Л" => "L", "л" => "l", "М" => "M", "м" => "m", "Н" => "N", "н" => "n",
                "О" => "O", "о" => "o", "П" => "P", "п" => "p", "Р" => "R", "р" => "r", "С" => "S", "с" => "s", "Т" => "T", "т" => "t",
                "У" => "U", "у" => "u", "Ф" => "F", "ф" => "f", "Х" => "Kh", "х" => "kh", "Ц" => "Ts", "ц" => "ts", "Ч" => "Ch", "ч" => "ch",
                "Ш" => "Sh", "ш" => "sh", "Щ" => "Shch", "щ" => "shch", "Ъ" => "", "ъ" => "", "Ы" => "Y", "ы" => "y", "Ь" => "", "ь" => "",
                "Э" => "E", "э" => "e", "Ю" => "Yu", "ю" => "yu", "Я" => "Ya", "я" => "ya", "@" => "y", "$" => "ye");

            $string = str_replace($arStrES, $arStrRS, $string);
            $string = str_replace($arStrOS, $arStrRS, $string);
        }
        $string = str_replace(['&mdash;', '&#8212;', '&hellip;', '&#8230;', '&quot;', '&#34;'], '', htmlentities($string));
        $string = iconv("UTF-8", "UTF-8//IGNORE", strtr(html_entity_decode($string), $replace));
        $string = str_replace([
            0, 1, 2, 3, 4, 5, 6, 7, 8, 9, ' ', '(', ')', "\n", '{', '}', '.', '-'
        ], '', $string);
        $string = preg_replace("/[^a-zA-ZА-Яа-я0-9\s]/", "", $string);
        return $string;
    }

    private function saveCustomfieldValue($value, $customfieldId)
    {
        $value = trim($value);
        $csValue = ProductCustomfieldValue::find()->where(['value' => $value, 'idCustomfield' => $customfieldId])->one();
        if (!$csValue) {
            $csValue = new ProductCustomfieldValue();
            $csValue->value = $value;
            $csValue->idCustomfield = $customfieldId;
            $csValue->save();
        }
    }

    public function actionParse($start = 10000000, $end = 110000000)
    {
        $error = 0;
        for ($i = $start; $i <= $end; $i++) {
            $this->idYandex = $i;
            $this->idProduct = null;
            try {
                $site = @file_get_contents("http://market.yandex.ua/model-spec.xml?modelid={$i}");
                if ($site) {
                    $error = 0;
                    $this->log("Index - $i\n");
                    preg_match_all("/\<span itemprop\=\"title\"\>(.*?)\<\/span\>\<\/a\>/iu", $site, $match);
                    $categoryName = $this->cleanValue($match[1][0]);
                    echo "Category - $categoryName\n";

                    if ($categoryName == 'Книги') {
                        $this->log("Skip\n", false, true);
                        continue;
                    }

                    $category = ProductCategory::find()->where(['title' => $categoryName])->one();
                    if (!$category) {
                        $category = new ProductCategory();
                        $category->title = $categoryName;
                        $category->save();
                        $this->log("Add new category - $categoryName\n");
                    }

                    if (isset($match[1][1])) {
                        $companyName = $this->cleanValue($match[1][1]);
                        $this->log("Company - $companyName\n");
                        $company = ProductCompany::find()->where(['title' => $companyName])->one();
                        if (!$company) {
                            $company = new ProductCompany();
                            $company->title = $companyName;
                            $company->save();
                            $this->log("Add new category - $companyName\n");
                        }
                    } else {
                        $companyName = "";
                        $company = new \stdClass();
                        $company->id = null;
                    }


                    preg_match("/\<h1 class\=\"b\-page\-title\_\_title\"\>(.*?)\<\/h1\>/iu", $site, $match);
                    if (isset($match[1])) {
                        $modelName = str_replace($companyName, "", $match[1]);
                        $modelName = trim(substr($modelName, 2));
                        $this->cleanValue($modelName);
                    } else {
                        $modelName = "";
                    }

                    $this->log("Model Name - $modelName\n");

                    $collection = Yii::$app->mongodb->getCollection('product');
                    $collectionValues = [];
                    $collectionValues['idCategory'] = $category->id;
                    $collectionValues['model'] = $modelName;
                    $collectionValues['idCompany'] = $company->id;
                    $collectionValues['idYandex'] = $i;

                    preg_match_all("/<th class=\"b-properties__label b-properties__label-title\"><span>(.*?)<\/span><\/th>/iu", $site, $matchcf);
                    preg_match_all("/<td class=\"b-properties__value\">(.*?)<\/td>/isu", $site, $matchcfv);
                    if (count($matchcf[1])) {
                        foreach ($matchcf[1] as $key => $cf) {
                            $cf = $this->cleanValue($cf);
                            $this->log("Custom Field - $cf\n");
                            $customfield = ProductCustomfield::find()->where(['title' => $cf, 'idCategory' => $category->id])->one();
                            if (!$customfield) {
                                $customfield = new ProductCustomfield();
                                $customfield->title = $cf;
                                $customfield->idCategory = $category->id;
                                $customfield->alias = $this->encodeString($cf);
                                $customfield->isFilter = 0;
                                $customfield->type = 0;
                                $customfield->save();
                                $this->log("Add new customfield - $cf\n");
                            }

                            $value = $this->cleanValue($matchcfv[1][$key]);
                            $this->log("Custom Field Value - $value\n");
                            $csValue = ProductCustomfieldValue::find()->where(['value' => $value, 'idCustomfield' => $customfield->id])->one();
                            if (!$csValue) {
                                $csValue = new ProductCustomfieldValue();
                                $csValue->value = $value;
                                $csValue->idCustomfield = $customfield->id;
                                $csValue->save();
                                $this->log("Add new customfield value - $value\n");
                            }
                            $collectionValues[$customfield->alias] = $value;
                        }
                    }
                    if (!($id = $collection->insert($collectionValues))) {
                        throw new ErrorException("Product with Yandex ID: {$i} not saved");
                    }
                    $this->idProduct = (string)$id;
                    $this->log("Product added {$categoryName} {$companyName} {$modelName}\n\n", false, true);
                } else {
                    $error++;
                    if ($error < 3) {
                        $i--;
                        $this->log("\nIndex - $i ---Not Found;\n");
                    } else {
                        $error = 0;
                        $this->log("\nIndex - $i ---Not Found;\n", false, true);
                    }
                }
            } catch (ErrorException $e) {
                $this->log("\nIndex - $i ---Error:{$e->getMessage()};\n", true, true);
                $error++;
                if ($error < 3) {
                    $i--;
                } else {
                    $error = 0;
                }
            }

        }
    }

    function log($string, $fail = false, $db = false)
    {
        if ($fail) {
            echo "\033[31m" . $string . "\033[0m";
        } else {
            echo $string;
        }

        \Yii::info($string, 'parse');
        if ($db) {
            $log = new LogParse();
            $log->url = $this->url;
            $log->idProduct = $this->idProduct;
            $log->message = $string;
            $log->isFail = $fail ? 1 : 0;
            $log->save();
        }
    }

    public function cropTag($text)
    {
        preg_match("/\>(\w*)\</i", $text, $croped);
        return $croped[1];
    }
}

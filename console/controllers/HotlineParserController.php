<?php
namespace console\controllers;

use common\models\Lang;
use common\models\LogParse;
use common\models\ParseHistory;
use common\models\Product;
use common\models\ProductCategory;
use common\models\ProductCategoryCategory;
use common\models\ProductCompany;
use common\models\ProductCustomfield;
use common\models\ProductCustomfieldValue;
use console\components\Parser\Hotline\CategoryParser;
use console\components\Parser\Hotline\LinkParser;
use console\components\Parser\Hotline\ProductParser;
use yii;
use yii\console\Controller;
use yii\helpers\Json;

class HotlineParserController extends Controller
{
    const PARSER_ID = 200;
    
    const TIMEOUT = 10;

    const CATEGORY_CACHE = 'hotline_category_data';
    
    public function actionIndex($cont = false, $save = null)
    {
        Lang::setCurrent('ru');
        $i = 0;
        $j = 0;
        $k = 0;

        //Для продолжения парсинга
        if ($cont) {
            if (!Yii::$app->cache->exists(self::CATEGORY_CACHE)) {
                echo 'Кеша категорий не найдено', PHP_EOL;
                die();
            }
            $sections = Yii::$app->cache->get(self::CATEGORY_CACHE);

            //Если нету файла лога
            if (!($file = $this->readFile())) {
                echo 'Файла сохранения не найдено', PHP_EOL;

                //Если данные введены
                if ($save and ($json = Json::decode($save))) {
                    $i = $json['section'];
                    $j = $json['subsection'];
                    $k = $json['catalog'];
                } elseif (!$this->ask('Продолжить парсинг сначала?')) {
                    die();
                }
            } else {
                $json = Json::decode($file);
                $i = $json['section'];
                $j = $json['subsection'];
                $k = $json['catalog'];

                if ($sections[$i]['subsection'][$j]['catalog'][$k]['url'] !== $json['url']) {
                    echo 'Url каталога не совпадает, в сохранении ',
                        $json['url'], PHP_EOL,
                        ' а текщуий ',
                        $sections[$i]['subsection'][$j]['catalog'][$k]['url'], PHP_EOL;

                    if (!$this->ask('Продолжить с нового url?')) {
                        die();
                    }
                }
            }
        }

        $sections = empty($sections) ? $this->getCategory() : $sections;

        //Разделы
        for (; $i < count($sections); $i++) {
            $section = $this->getSection($sections[$i]['title']);

            //Подразделы
            for (; $j < count($sections[$i]['subsection']); $j++) {
                $subsection = $this->getSubsection($sections[$i]['subsection'][$j]['title'], $section);

                //Каталоги
                for(; $k < count($sections[$i]['subsection'][$j]['catalog']); $k++) {
                    $catalog = $this->getCatalog($sections[$i]['subsection'][$j]['catalog'][$k]['title'], $subsection);

                    $this->saveFile(Json::encode([
                        'section' => $i,
                        'subsection' => $j,
                        'catalog' => $k,
                        'url' => $sections[$i]['subsection'][$j]['catalog'][$k]['url'],
                    ]));

                    $links = $this->getLink($sections[$i]['subsection'][$j]['catalog'][$k]['url']);

                    echo 'У нас ', count($links), ' ссылок в ', $catalog->title, PHP_EOL;

                    //Ссылки
                    if (count($links) > 0) {
                        foreach ($links as $link) {
                            if ($this->checkLink($link)) {
                                $this->parseProduct("{$link}#prop", $catalog, Json::encode([
                                    'section' => $i,
                                    'subsection' => $j,
                                    'catalog' => $k,
                                    'url' => $sections[$i]['subsection'][$j]['catalog'][$k]['url'],
                                ]));
                                $this->saveLink($link);
                            }
                        }
                        echo PHP_EOL;
                    }
                }
            }
        }
        $this->delFile();
    }

    public function actionParseCategory($url)
    {
        Lang::setCurrent('ru');
        if (!Yii::$app->cache->exists(self::CATEGORY_CACHE)) {
            echo 'Нету напаршеных категорий', PHP_EOL;
            die();
        }

        $sections = Yii::$app->cache->get(self::CATEGORY_CACHE);

        $count_sections = count($sections);
        //Разделы
        for ($i = 0; $i < $count_sections; $i++) {
            $count_subsection = count($sections[$i]['subsection']);
            //Подразделы
            for ($j = 0; $j < $count_subsection; $j++) {
                $count_catalog = count($sections[$i]['subsection'][$j]['catalog']);
                //Каталоги
                for($k = 0; $k < $count_catalog; $k++) {
                    if ($sections[$i]['subsection'][$j]['catalog'][$k]['url'] === $url) {
                        echo 'Парсим ', $sections[$i]['subsection'][$j]['catalog'][$k]['title']['ru'], PHP_EOL;

                        $section = $this->getSection($sections[$i]['title']);
                        $subsection = $this->getSubsection($sections[$i]['subsection'][$j]['title'], $section);
                        $catalog = $this->getCatalog($sections[$i]['subsection'][$j]['catalog'][$k]['title'], $subsection);

                        if (Yii::$app->cache->exists("cat_links_$url") and $this->ask('Использовать ссылки из кеша?')) {
                            $links = Yii::$app->cache->get("cat_links_$url");
                        } else {
                            $links = $this->getLink($sections[$i]['subsection'][$j]['catalog'][$k]['url']);
                            if ($links) {
                                Yii::$app->cache->set("cat_links_$url", $links);
                            }
                        }
                        echo 'У нас ', count($links), ' ссылок в ', $catalog->title, PHP_EOL;

                        //Ссылки
                        foreach ($links as $link) {
                            if ($this->checkLink($link) and $this->parseProduct("{$link}#prop", $catalog)) {
                                $this->saveLink($link);
                            }
                        }

                        die();
                    }
                }
            }
        }
        echo 'Url не найден, проверьте формат' . PHP_EOL;
    }

    public function actionClearCategoryCache()
    {
        if (!Yii::$app->cache->exists(self::CATEGORY_CACHE)) {
            echo 'Кеша категорий не найдено', PHP_EOL;
            die();
        }

        if (!$this->ask('Вы желаете удалить кеш категорий?')) {
            echo 'Отменено', PHP_EOL;
            die();
        }

        Yii::$app->cache->delete(self::CATEGORY_CACHE);
        echo 'Удалено', PHP_EOL;
    }

    public function actionCategories()
    {
        Lang::setCurrent('ru');
        if (Yii::$app->cache->exists(self::CATEGORY_CACHE)) {
            if ($this->ask('Кеш уже есть, очистить?')) {
                Yii::$app->cache->delete(self::CATEGORY_CACHE);
            } else {
                die(1);
            }
        }
        $this->getCategory();
    }

    public function actionGetCatUrl($section, $subsection, $catalog)
    {
        $section = (int)$section;
        $subsection = (int)$subsection;
        $catalog = (int)$catalog;

        if (!Yii::$app->cache->exists(self::CATEGORY_CACHE)) {
            echo 'Нету напаршеных категорий', PHP_EOL;
            die();
        }

        $sections = Yii::$app->cache->get(self::CATEGORY_CACHE);

        if (!isset($sections[$section])) {
            echo "Нету такой секции - $section", PHP_EOL;
            die();
        } elseif (!isset($sections[$section]['subsection'][$subsection])) {
            echo "Нету такой подсекции - $section:$subsection", PHP_EOL;
            die();
        } elseif (!isset($sections[$section]['subsection'][$subsection]['catalog'][$catalog])) {
            echo "Нету такого каталога - $section:$subsection:$catalog", PHP_EOL;
            die();
        } elseif (empty($sections[$section]['subsection'][$subsection]['catalog'][$catalog]['url'])) {
            echo "Нету url каталога - $section:$subsection:$catalog", PHP_EOL;
            die();
        }

        echo $sections[$section]['subsection'][$subsection]['catalog'][$catalog]['url'] . PHP_EOL;
    }

    public function actionGetCatId($url)
    {
        if (!Yii::$app->cache->exists(self::CATEGORY_CACHE)) {
            echo 'Нету напаршеных категорий', PHP_EOL;
            die();
        }

        $sections = Yii::$app->cache->get(self::CATEGORY_CACHE);

        $count_sections = count($sections);
        //Разделы
        for ($i = 0; $i < $count_sections; $i++) {
            $count_subsection = count($sections[$i]['subsection']);
            //Подразделы
            for ($j = 0; $j < $count_subsection; $j++) {
                $count_catalog = count($sections[$i]['subsection'][$j]['catalog']);
                //Каталоги
                for($k = 0; $k < $count_catalog; $k++) {
                    if ($sections[$i]['subsection'][$j]['catalog'][$k]['url'] === $url) {
                        echo "$i:$j:$k" . PHP_EOL;
                        die();
                    }
                }
            }
        }
        echo 'Url не найден, проверьте формат' . PHP_EOL;
    }

    public function actionClearProduct()
    {
        if (!$this->ask('Удалить все продукты?')) {
            die();
        }
        Product::deleteAll();
    }

    public function clearLog()
    {
        if (!$this->ask('Удалить весь лог?')) {
            die();
        }
        LogParse::deleteAll();
    }

    public function clearHistory()
    {
        if (!$this->ask('Удалить историю запаршеных ссылок?')) {
            die();
        }
        ParseHistory::deleteAll(['parser_id' => self::PARSER_ID]);
    }

    public function clearCompany()
    {
        if (!$this->ask('Удалить всех производителей?')) {
            die();
        }
        ProductCompany::deleteAll();
    }

    public function clearCustomField()
    {
        if (!$this->ask('Удалить все кастом филды продуктов?')) {
            die();
        }
        ProductCustomfield::deleteAll();
    }

    public function clearCustomFieldValue()
    {
        if (!$this->ask('Удалить все значения кастом филдов продуктов?')) {
            die();
        }
        ProductCustomfieldValue::deleteAll();
    }

    public function clearCategory()
    {
        if (!$this->ask('Удалить все категории продуктов?')) {
            die();
        }
        ProductCategory::deleteAll();
        ProductCategoryCategory::deleteAll();
    }

    public function actionClearAll()
    {
        if ($this->ask('Удалить все продукты?')) {
            Product::deleteAll();
            ParseHistory::deleteAll(['parser_id' => self::PARSER_ID]);
        }
        if ($this->ask('Удалить всех производителей?')) {
            ProductCompany::deleteAll();
        }
        if ($this->ask('Удалить все кастом филды продуктов?')) {
            ProductCustomfield::deleteAll();
            ProductCustomfieldValue::deleteAll();
        }
        if ($this->ask('Удалить все категории продуктов?')) {
            ProductCategory::deleteAll();
            ProductCategoryCategory::deleteAll();
        }
        if ($this->ask('Удалить логи парсера?')) {
            LogParse::deleteAll();
        }
    }

    private function getCategory()
    {
        echo 'Парсим категории', PHP_EOL;
        $categoryParser = new CategoryParser('http://hotline.ua/catalog/', self::TIMEOUT);

        if (!Yii::$app->cache->exists(self::CATEGORY_CACHE)) {
            $categoryParser->downloadContent()->parseData()->saveDataToCache(self::CATEGORY_CACHE);
        } else {
            $categoryParser->initDataFromCache(self::CATEGORY_CACHE);
        }

        return $categoryParser->getData();
    }

    private function getLink($link)
    {
        $linkParser = new LinkParser($link, self::TIMEOUT);

        $linkParser->downloadContent()->parseData();

        return $linkParser->getData();
    }

    private function parseProduct($link, $catalog, $log = null)
    {
        $productParser = new ProductParser($link, self::TIMEOUT);

        $productParser->catalog = $catalog;
        $productParser->log = $log;


        $productParser->downloadContent();

        if ($productParser->download_status === $productParser::DOWNLOAD_STATUS_OK) {
            $productParser->parseData();

            return true;
        }

        return false;
    }

    private function getSection($title)
    {
        $search_full = Json::encode(['ru-RU' => $title['ru'], 'uk-Uk' => $title['uk']]);
        $section = ProductCategory::find()->roots()->where(['title' => $search_full])->one();

        if (!$section) {
            $section = ProductCategory::find()->roots()->where(['~~*', 'title', $title['ru']])->andWhere(['~~*', 'title', $title['uk']])->one();

            if (!$section) {
                $section = ProductCategory::find()->roots()->where(['~~*', 'title', $title['ru']])->orWhere(['~~*', 'title', $title['uk']])->one();

                if (!$section) {
                    echo "+ Create new Section {$title['ru']}", PHP_EOL;
                    Lang::setCurrent('ru');

                    $section = new ProductCategory();
                    $section->title = $title['ru'];
                    $section->makeRoot();

                    Lang::setCurrent('uk');
                    $section->updateAttributes(['title' => $title['uk']]);
                    Lang::setCurrent('ru');
                }
            }
        }

        return $section;
    }

    private function getSubsection($title, ProductCategory $section)
    {
        $search_full = Json::encode(['ru-RU' => $title['ru'], 'uk-Uk' => $title['uk']]);
        /** @var ProductCategory $subsection */
        $subsection = $section->children(1)->where(['title' => $search_full])->one();

        if (!$subsection) {
            /** @var ProductCategory $subsection */
            $subsection = $section->children(1)->where(['~~*', 'title', $title['ru']])->andWhere(['~~*', 'title', $title['uk']])->one();

            if (!$subsection) {
                /** @var ProductCategory $subsection */
                $subsection = $section->children(1)->where(['~~*', 'title', $title['ru']])->orWhere(['~~*', 'title', $title['uk']])->one();

                if (!$subsection) {
                    echo "+ Create new Subsection {$title['ru']}", PHP_EOL;
                    Lang::setCurrent('ru');

                    $subsection = new ProductCategory([
                        'title' => $title['ru'],
                        'root' => $section->id,
                    ]);
                    $subsection->appendTo($section);

                    Lang::setCurrent('uk');
                    $subsection->updateAttributes(['title' => $title['uk']]);
                    Lang::setCurrent('ru');
                }
            }
        }

        return $subsection;
    }

    private function getCatalog($title, ProductCategory $subsection)
    {
        $search_full = Json::encode(['ru-RU' => $title['ru'], 'uk-Uk' => $title['uk']]);
        /** @var ProductCategory $catalog */
        $catalog = $subsection->children(1)->where(['title' => $search_full])->one();

        if (!$catalog) {
            /** @var ProductCategory $catalog */
            $catalog = $subsection->children(1)->where(['~~*', 'title', $title['ru']])->andWhere(['~~*', 'title', $title['uk']])->one();

            if (!$catalog) {
                /** @var ProductCategory $catalog */
                $catalog = $subsection->children(1)->where(['~~*', 'title', $title['ru']])->orWhere(['~~*', 'title', $title['uk']])->one();

                if (!$catalog) {
                    echo "+ Create new Catalog {$title['ru']}", PHP_EOL;
                    Lang::setCurrent('ru');

                    $catalog = new ProductCategory([
                        'title' => $title['ru'],
                        'root' => $subsection->id,
                    ]);
                    $catalog->appendTo($subsection);

                    Lang::setCurrent('uk');
                    $catalog->updateAttributes(['title' => $title['uk']]);
                    Lang::setCurrent('ru');
                }
            }
        }

        return $catalog;
    }

    /**
     * Сохраняет ссылку в историю парсинга
     * @param string $link
     */
    private function saveLink($link)
    {
        $history = new ParseHistory([
            'element' => $link,
            'parser_id' => self::PARSER_ID,
        ]);
        $history->save();
    }

    /**
     * Проверяет на существование ссылки в истории парсинга
     * @param string $link
     * @return bool
     */
    private function checkLink($link)
    {
        $history = ParseHistory::find()->where(['parser_id' => self::PARSER_ID, 'element' => $link])->count();

        return ($history === 0) ? true : false;
    }

    /**
     * Сохраняет log файл
     * @param string $string
     */
    private function saveFile($string)
    {
        chdir(Yii::$aliases['@console']);

        $lock_file = fopen('hotline_parser.log', 'wt');
        if ($lock_file) {
            fwrite($lock_file, $string);
            fclose($lock_file);
        }
    }

    /**
     * Читает log файл
     * @return string
     */
    private function readFile()
    {
        $string = null;
        chdir(Yii::$aliases['@console']);

        if (file_exists('hotline_parser.log')) {
            $lock_file = fopen('hotline_parser.log', 'rt');
            $string = fread($lock_file, filesize('hotline_parser.log'));
            fclose($lock_file);
        }

        return $string;
    }

    /**
     * Удаляет log файл
     */
    private function delFile()
    {
        chdir(Yii::$aliases['@console']);

        $lock_file = fopen('hotline_parser.log', 'rt');
        if ($lock_file) {
            fclose($lock_file);
            unlink('hotline_parser.log');
        }
    }
    
    /**
     * Выводит сообщение и считывает введенные данные в консоль
     * @param string $msg
     * @return string
     */
    private function readConsole($msg)
    {
        echo "\e[32m{$msg}\e[0m", PHP_EOL;

        $fp = @fopen('php://stdin', 'r');

        return rtrim(fgets($fp, 1024));
    }

    /**
     * Задает вопрос да\нет в консоль и возвращает bool
     * @param string $question
     * @return boolean
     */
    private function ask($question)
    {
        $answers = ['yes' => true, 'no' => false];
        do {
            $answer = $this->readConsole($question . ' Введите "yes" или "no"!');
        } while (!in_array($answer, array_keys($answers)));

        return $answers[$answer];
    }

    /**
     * Пересохраняет модели продуктов с целью генерации поля url
     * @throws yii\mongodb\Exception
     */
    public function actionGenerateUrl()
    {
        $limit = 100;
        $totalCount = Product::find()->count();

        for ($i = 0; $i < $totalCount; $i += $limit) {
            $products = Product::find()->limit($limit)->offset($i)->all();
            foreach ($products as $product) {
                $product->save();
            }
        }
    }
}

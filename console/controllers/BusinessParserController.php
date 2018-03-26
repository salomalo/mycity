<?php
namespace console\controllers;

use common\models\Action;
use common\models\Afisha;
use common\models\City;
use common\models\Lang;
use common\models\ScheduleKino;
use Exception;
use yii;
use yii\console\Controller;
use common\models\Business;
use common\models\BusinessCategory;
use common\models\BusinessAddress;
use common\models\LogParseBusiness as LogParse;
use common\models\Gallery;
use common\models\File;
use common\models\ParserDomain;
use console\extensions\simpleParser\simple_html_dom;

/**
 * Description of ParserFirmaController
 *
 * @author dima
 */
class BusinessParserController extends Controller
{
    const HDOM_TYPE_ELEMENT = 1;
    const HDOM_TYPE_COMMENT = 2;
    const HDOM_TYPE_TEXT = 3;
    const HDOM_TYPE_ENDTAG = 4;
    const HDOM_TYPE_ROOT = 5;
    const HDOM_TYPE_UNKNOWN = 6;
    const HDOM_QUOTE_DOUBLE = 0;
    const HDOM_QUOTE_SINGLE = 1;
    const HDOM_QUOTE_NO = 3;
    const HDOM_INFO_BEGIN = 0;
    const HDOM_INFO_END = 1;
    const HDOM_INFO_QUOTE = 2;
    const HDOM_INFO_SPACE = 3;
    const HDOM_INFO_TEXT = 4;
    const HDOM_INFO_INNER = 5;
    const HDOM_INFO_OUTER = 6;
    const HDOM_INFO_ENDSPACE = 7;
    const DEFAULT_TARGET_CHARSET = 'UTF-8';
    const DEFAULT_BR_TEXT = "\r\n";
    const DEFAULT_SPAN_TEXT = " ";
    const MAX_FILE_SIZE = 600000;

    const LANG_RU = 'ru-RU';
    const LANG_UA = 'uk-UK';

    public $lang = 'ru-RU';

    const SPAN_PHONE_RU = 'Телефон';
    const SPAN_PHONE_UA = 'Телефон';
    const SPAN_ADDRESS_RU = 'Адрес';
    const SPAN_ADDRESS_UA = 'Адреса';
    const SPAN_TIME_RU = 'Время работы';
    const SPAN_TIME_UA = 'Час роботи';
    const SPAN_SITE_RU = 'Сайт';
    const SPAN_SITE_UA = 'Сайт';
    const SPAN_EMAIL_RU = 'Email адрес';
    const SPAN_EMAIL_UA = 'Email адрес';

    public $mDomain;
    public $domain;
    public $startCat = false;
    public $startPodCat = false;
    public $startPage = false;
    public $cat = '';
    public $podCat = '';
    public $lastFullLink = false;

    public $title;
    public $url;
    public $companyName;
    public $idMenu;

    /** @var City $city */
    public $city;
    public $null_category;
    private $count = 0;

    public function file_get_html($url, $use_include_path = false, $context = null, $offset = -1, $maxLen = -1,
                                  $lowercase = true, $forceTagsClosed = true, $target_charset = self::DEFAULT_TARGET_CHARSET,
                                  $stripRN = true, $defaultBRText = self::DEFAULT_BR_TEXT, $defaultSpanText = self::DEFAULT_SPAN_TEXT
    )
    {
        // We DO force the tags to be terminated.
        $dom = new simple_html_dom(null, $lowercase, $forceTagsClosed, $target_charset, $stripRN, $defaultBRText, $defaultSpanText);
        // For sourceforge users: uncomment the next line and comment the retreive_url_contents line 2 lines down if it is not already done.
        $contents = @file_get_contents($url, $use_include_path, $context, $offset);
        // Paperg - use our own mechanism for getting the contents as we want to control the timeout.
        //$contents = retrieve_url_contents($url);
        if (empty($contents) || strlen($contents) > self::MAX_FILE_SIZE) {
            return false;
        }
        // The second parameter can force the selectors to all be lowercase.
        $dom->load($contents, $lowercase, $stripRN);
        return $dom;
    }

    public function getDomains($idCity)
    {
        $model = ParserDomain::find()->where(['idCity' => $idCity])->one();
        if ($model) {
            $this->domain = 'http://' . $model->domain;
            $this->mDomain = 'http://' . $model->mDomain;
        }
    }

    public function actionClear($idCity = null, $auto = false)
    {
        if (empty($idCity) and !$auto) {
            $idCity = (int)$this->readConsole("Введите id города");
        }
        if ($city = City::findOne($idCity)) {
            $this->log("Будут удалены все предприятия города \e[31m{$city->title}\e[0m");
            $rand = rand(1, 10);
            $input = $auto ?: (int)$this->readConsole("Введите $rand для подтвержения");
            if (($input === $rand) or $auto) {
                $query = Business::find()->where(['idCity' => $idCity]);
                $count = $query->count();
                $this->log("Предприятий: $count");
                /** @var Business[] $models */
                foreach ($query->batch() as $models) {
                    foreach ($models as $model) {
                        // удаляем логотипы
                        \Yii::$app->files->deleteFile($model, 'image');
                        // удаляем галереи
                        \Yii::$app->files->deleteFile($model, 'gallery');
                        echo $model->delete() ? '.' : ',';
                    }
                    echo PHP_EOL;
                }
                $count -= $query->count();
                $this->log("Готово, удалено $count");
            } else {
                $this->log("Удаление предприятий $city->title отменено", true);
            }
        } else {
            $this->log("Неизвестный город $idCity", true);
        }
    }

    public function actionIndex($idCity, $lang = 'ru-RU', $parse_cat = '', $parse_Podcat = '', $page = '', $lastFullLink = '')
    {
        $this->city = City::findOne($idCity);
        if ($this->city = City::findOne($idCity)) {
            $this->log("Парсинг предприятий города \e[31m{$this->city->title}\e[0m на языке - \e[32m$lang\e[0m");
            sleep(3);
        } else {
            $this->log("Неведомый город!", true);
            die();
        }
        Lang::setCurrent($lang);
        $this->lang = $lang;
        $this->getDomains($idCity);

        $site = @file_get_contents($this->mDomain . '/catalog');

        if (empty($site)) {
            $this->log("no site");
            die();
        }

        $this->null_category = BusinessCategory::find()->where(['~~*', 'title', '%Без категории%'])->one();

        preg_match_all('~<div class="poster">(.+?)<footer>~is', $site, $match1);
        preg_match_all('~<div class="(.+?)">(.+?)<a href="(.+?)" class="news int">(.+?)<h2>(.+?)</h2>(.+?)</a>(.+?)</div>~is', $match1[1][0], $cat);


        foreach ($cat[3] as $key => $linkMain) {

            if ($parse_cat  && '/catalog/'.$parse_cat == $linkMain) {
                $this->startCat = true;
            }


            $this->cat = $cat[5][$key];

            if ($this->startCat || $parse_cat == '') {

                $site = @file_get_contents($this->mDomain . $linkMain);
                preg_match_all('~<div class="poster">(.+?)<footer>~is', $site, $match1);
                preg_match_all('~<div class="(.+?)">(.+?)<a href="(.+?)" class="news int">(.+?)<h2>(.+?)</h2>(.+?)</a>(.+?)</div>~is', $match1[1][0], $podCat);

                foreach ($podCat[3] as $key => $linkPodCat) {

                    if ($parse_Podcat  && '/catalog/'.$parse_cat.'/'.$parse_Podcat == $linkPodCat) {
                        $this->startPodCat = true;
                    }

                    $this->podCat = $podCat[5][$key];

                    if (($this->startPodCat || $parse_Podcat == '') && $podCat[5][$key] != 'Все подрубрики') {
                        if (!empty($page)) {
                            $this->getContent($idCity, $linkPodCat, $lastFullLink, $page);
                            $page = '';
                        } else {
                            $this->getContent($idCity, $linkPodCat, $lastFullLink);
                        }
                    }
                }
            }
        }
    }

    private function getContent($idCity, $url, $lastFullLink, $page = null)
    {
        if (!$page) {
            $html = $this->file_get_html("{$this->mDomain}{$url}");
        } else {
            $html = $this->file_get_html("{$this->mDomain}{$url}/page/$page");
        }
        
        if ($html instanceof simple_html_dom) {
            foreach ($html->find('div.enterprise a.block_item') as $item) {
                if ($this->lastFullLink || empty($lastFullLink)) {
                    $title = $item->find('h2.grn', 0);
                    $this->title = $this->cleanValue($title->plaintext);
                    $this->url = $item->href;

                    $this->getFirma($idCity, $item->href, $url);
                }
                if ($item->href == $lastFullLink) {
                    $this->lastFullLink = true;
                }
            }
            $nextPage = $html->find('div.enterprise div.lastt a.news', 0);  // след. страница
            if (!empty($nextPage->href)) {
                $this->getContent($idCity, $nextPage->href, $lastFullLink);
            }
            $html->clear();
        } else {
            $this->log("not html",true);
        }
        unset($html);
    }

    private function getSpanText()
    {
        switch ($this->lang) {
            case self::LANG_RU:
                return [
                    'phone' => self::SPAN_PHONE_RU,
                    'address' => self::SPAN_ADDRESS_RU,
                    'time' => self::SPAN_TIME_RU,
                    'site' => self::SPAN_SITE_RU,
                    'email' => self::SPAN_EMAIL_RU,
                ];
            case self::LANG_UA:
                return [
                    'phone' => self::SPAN_PHONE_UA,
                    'address' => self::SPAN_ADDRESS_UA,
                    'time' => self::SPAN_TIME_UA,
                    'site' => self::SPAN_SITE_UA,
                    'email' => self::SPAN_EMAIL_UA,
                ];
            default:
                throw new Exception('Lang Error');
        }
    }

    public function getFirma($idCity, $fullLink, $url)
    {
        $temp = explode('/', $fullLink);

        if (empty($temp[3])) {
            return;
        }

        $html = $this->file_get_html($this->domain . $fullLink);

        if (!$html) {
            unset($html);
            return;
        }

        $business = new Business();
        $business->title = $this->title;
        $business->idCity = $idCity;
        $business->idUser = 1;
        $business->idProductCategories = [];

        $coord = ['lon' => 0, 'lat' => 0];
        $time = '';

        $spans = $this->getSpanText();

        foreach ($html->find('div.block_info div') as $infoBox) {

            $name = $infoBox->find('span.name', 0);
            if (!empty($name)) {
                $name->plaintext = trim($name->plaintext);

                foreach ($infoBox->find('span.text') as $value) {
                    switch ($name->plaintext) {
                        case $spans['address']:
                            $coord = $this->getCoord($infoBox);
                            $address = $this->cleanValue($value->plaintext);
                            break;
                        case $spans['phone']:
                            $business->phone = $this->cleanValue($value->plaintext);
                            break;
                        case $spans['time']:
                            $time = $value->plaintext;
                            break;
                        case $spans['site']:
                            $business->site = $value->plaintext;
                            break;
                        case 'Twitter':
                            $business->urlTwitter = $value->plaintext;
                            break;
                        case 'Vk':
                            $business->urlVK = $value->plaintext;
                            break;
                        case 'Fb':
                            $business->urlFB = $value->plaintext;
                            break;
                        default:
                            if ($name->plaintext != $spans['email']) {
                                $value->plaintext = trim($value->plaintext);
                            } else {
                                $business->email = $this->getEmail($value);
                            }
                    }
                }
            }
        }

        $desc = $html->find('div.company_info div.text_info', 0);
        if (!empty($desc->plaintext)) {
            $business->description = $desc->plaintext;
        }

        $arrCat = [$this->saveCategory($this->cat, $this->podCat)];

        foreach ($html->find('div.company_info h2.open span') as $catList) {
            $temp = explode(' / ', $this->cleanValue($catList->plaintext));
            if (!empty($temp[1]) && is_array($temp)) {
                $arrCat[] = $this->saveCategory($temp[0], $temp[1]);
            }
        }
        $business->idCategories = array_unique($arrCat);

        echo PHP_EOL;

        if ($business->save()) {

            $this->lastFirma($idCity, $url, $this->domain . $fullLink, $business);

            if ($coord['lat'] != 0) {
                $modelAddress = new BusinessAddress();
                $modelAddress->idBusiness = $business->id;
                $modelAddress->lat = $coord['lat'];
                $modelAddress->lon = $coord['lon'];
                $modelAddress->address = isset($address) ? $address : null;
                $modelAddress->phone = $business->phone;
                $modelAddress->save();
            }
            try {
                unset($logo);
                $logo = $html->find('div.comp_logo img', 0);
                if (!isset($logo->src)) {
                    $logo = $html->find('div.premium_logo img', 0);
                }
                if (isset($logo->src)) {
                    try {
                        $img = $this->img($logo->src);
                        $this->log($logo->src, true);
                        if ($img['mimeType'] != 'text/html') {
                            $file = Yii::$app->files->uploadFromUrl($business, 'image', $img);
                            if (!empty($file)) {
                                $business->updateAttributes(['image'=>(string)$file ]);
                            }
                        }
                        @unlink($img['file']);
                        unset($img);
                    } catch (Exception $e) {
                        $this->log("Ошибка при сохранении логотипа: {$e->getMessage()}", true);
                    }
                } else {
                    $business->updateAttributes(['image'=>"" ]);
                }

                $business->image ? $this->log(" картинка ".$business->image, true) : null;

                $gal = $html->find('div.gallery_box', 0);

                if ($gal) {

                    $albom = $gal->find('option[selected=selected]', 0);

                    $modelGallery = new Gallery();
                    $modelGallery->type = File::TYPE_BUSINESS;
                    $modelGallery->pid = $business->id;
                    $modelGallery->title = $this->cleanValue($albom->plaintext);
                    $modelGallery->save();

                    foreach ($gal->find('div.foto_content ul li a img') as $img) {
                        try {
                            $img = $this->img($img->src);
                            if ($img['mimeType'] != 'text/html') {
                                $file = Yii::$app->files->uploadFromUrl($modelGallery, 'attachments', $img, 'business/' . $modelGallery->id);
                                if (!empty($file)) {
                                    $modelFile = File::find()->select('id')->where(['type' => File::TYPE_GALLERY, 'name' => $file])->one();
                                    $modelFile->pid = $modelGallery->id;
                                    $modelFile->save(false, ['pid']);
                                }
                            }
                            @unlink($img['file']);
                        } catch (Exception $e) {
                            $this->log("Ошибка при сохранении изображения галереи: {$e->getMessage()}", true);
                        }
                    }
                }
            } catch(yii\base\Exception $e){
                $this->log("Ошибка при сохранении изображения: {$e->getMessage()}", true);
            }
        }
        unset($business);

        $html->clear();
        unset($html);
    }

    private function saveCategory($cat, $podCat)
    {
        $model = BusinessCategory::find()->where(['~~*', 'title', "%$podCat%"])->one();

        if (empty($model)) {
            $category = str_replace([',', '-', '/', '\\', '\'', '"', '(', ')', '.', '!', '&', ';'], '', $podCat);
            $titles = array_values(array_filter(explode(' ', $category)));

            $fullStringCat = $category;
            $fullStringCat = str_replace(['   ', '  ', ' '], '%', $fullStringCat);
            $titles[] = $fullStringCat;
            if (($countExplode = count($titles) - 1) > 1) {
                for ($i = 1; $i < $countExplode; $i++) {
                    $titles[] = $titles[$i];
                }
            }

            $search = [];
            foreach ($titles as $title) {
                if (mb_strlen($title) > 2) {
                    $search[] = BusinessCategory::find()->select(['id'])->where(['~~*', 'title', "%$title%"])->column();
                }
            }

            $stats = [];
            foreach ($search as $item) {
                foreach ($item as $id) {
                    if (isset($stats[$id])) {
                        $stats[$id]++;
                    } else {
                        $stats[$id] = 1;
                    }
                }
            }

            arsort($stats);
            foreach ($stats as $id => $count) {
                if ($count > 0) {
                    $model = BusinessCategory::find()->where(['id' => $id])->one();
                }
                break;
            }
        }
        if (empty($model)) {
            $model = $this->null_category;
        }

        return $model->id;
    }

    public function img($url)
    {
        if (!empty($url)) {
            $file = @basename($url);
            $i = 5;
            while (empty($content) and ($i > 0)) {
                $i--;
                $content = @file_get_contents($url);
            }
            if ($content) {
                $f = @fopen($file, 'w');
                if (@fwrite($f, $content) === false) {
                    $this->log('Не могу произвести запись в файл.', true);
                    return null;
                } else {
                    $filesize = @filesize($file);
                    $mimeType = @mime_content_type($file);
                    $this->log("\e[32m Файл $file записан. $filesize байт. mime: $mimeType \e[0m");
                    @fclose($f);
                    return ['file' => $file, 'filesize' => $filesize, 'mimeType' => $mimeType];
                }
            } else {
                $this->log('Не могу качать файл.', true);
            }
        }
    }

    public function getEmail($value)
    {
        preg_match_all('/<span class="text">(.*?)mail_user=\'(.*?)\'(.*?)\+\'(.*?)\'(.*?)<\/span>/iu', $value, $match);
        if (!empty($match[2][0])) {
            $email = $match[2][0] . '@' . $match[4][0];
        } else {
            preg_match_all('/<span class="text">(.*?)mail_user = \'(.*?)\'(.*?)\+ \'(.*?)\'(.*?)<\/span>/iu', $value, $match);
            if (!empty($match[2][0])) {
                $email = $match[2][0] . '@' . $match[4][0];
            } else {
                $email = '';
            }
        }
        return $email;
    }

    public function getCoord($infoBox)
    {
        foreach ($infoBox->find('span.address') as $map) {
            preg_match_all('/<span class="address" data-markerinfo=\'(.*?)\'>(.*?)<\/span>/iu', $map, $match);
            if (!empty($match[1][0])) {
                $list = explode(',', str_replace('"', '', $match[1][0]));
                $coord = [];
                foreach ($list as $item) {
                    $arr = explode(':', $item);

                    if ($arr[0] == 'geo_coord_lng') {
                        $coord['lon'] = $arr[1];
                    }

                    if ($arr[0] == 'geo_coord_lat') {
                        $coord['lat'] = $arr[1];
                    }
                }

                return $coord;
            }
        }
        $coord['lon'] = 0;
        $coord['lat'] = 0;
        return $coord;
    }

    public function lastFirma($idCity, $url, $fullLink, Business $business)
    {
        $file = "lastFirma.log";

        $str = "{$this->city->title}; {$this->cat}, {$this->podCat}; $url; $fullLink;";

        $f = @fopen("$file", "w");
        if (fwrite($f, $str) === false) {
            $this->log("Не могу сохранить файл.", true);
        } else {
            $this->count++;
            $this->log("\e[34m Добавленно {$this->count} - $str \e[0m", false);
            $this->dbLog("\e[34m Добавленно {$this->count} - $str \e[0m", $idCity, $business->id, $fullLink, false);
        }
    }

    public function cleanValue($value)
    {
        $value = strip_tags($value);
        $value = trim($value);
        $arr = ["\r\n", "\\"];
        $value = str_replace($arr, ' ', $value);
        return $value;
    }

    public function log($string, $fail = false)
    {
        if ($fail) {
            echo "\033[31m{$string}\033[0m", PHP_EOL;
        } else {
            echo $string, PHP_EOL;
        }

        Yii::info($string, 'parse');
    }

    private function dbLog($string, $city_id, $business_id, $url, $fail = false)
    {
        $log = new LogParse([
            'title' => $this->title,
            'url' => $this->url,
            'message' => $string,
            'isFail' => $fail ? 1 : 0,
            'business_id' => $business_id,
            'city_id' => $city_id,
            'full_url' => $url,
        ]);

        $log->save();
    }

    private function readConsole($msg)
    {
        $this->log("\e[32m{$msg}\033[0m");
        $fp = @fopen('php://stdin', 'r');
        return rtrim(fgets($fp, 1024));
    }

    public function actionRatio()
    {
        $fields = ['description' => 1, 'site' => 1, 'phone' => 1, 'urlVK' => 1, 'urlFB' => 1, 'urlTwitter' => 1, 'email' => 1, 'shortDescription' => 1, 'skype' => 1, 'image' => 5];

        $cities = City::find()->select('id')->where(['main' => City::PENDING]);
        $query = Business::find()->orderBy(['id' => SORT_ASC])->where(['idCity' => $cities]);
        $gallery = Gallery::find()->distinct()->select('pid')->where(['type' => File::TYPE_BUSINESS])->column();
        $action = Action::find()->distinct()->select('idCompany')->column();
        $address = BusinessAddress::find()->distinct()->select('idBusiness')->column();
        $schedule = ScheduleKino::find()->distinct()->select('idCompany')->column();
        $afisha = Afisha::find()->select('idsCompany')->column();

        $afisha_ids = [];
        foreach ($afisha as $item) {
            $str = str_replace(['{', '}'], '', $item);
            if ($str !== '') {
                $arr = explode(',', $str);
                foreach ($arr as $id) {
                    $id = (int)$id;
                    if ($id and !in_array($id, $afisha_ids)) {
                        $afisha_ids[] = $id;
                    }
                }
            }
        }
        unset($afisha, $cities);

        $counts = ['gallery' => 1, 'afisha_ids' => 3, 'action' => 3, 'address' => 3, 'schedule' => 3];
        $check = function ($id, $array, $value) {
            return in_array($id, $array) ? $value : 0;
        };

        /** @var Business[] $models */
        foreach ($query->batch(250) as $models) {
            foreach ($models as $model) {
                $ratio = 0;

                foreach ($fields as $attr => $val) {
                    if (!empty($model->$attr)) {
                        $ratio += $val;
                    }
                }
                foreach ($counts as $var => $val) {
                    $ratio += $check($model->id, $$var, $val);
                }

                if (($ratio > 1) and ($ratio !== $model->ratio)) {
                    $model->updateAttributes(['ratio' => $ratio]);
                    $this->log("$model->id - $model->title - $ratio");
                }
            }
        }
        $this->log('Пересчет рейтинга - OK', false);
    }

    public function actionFixCat()
    {
        $catQuery = BusinessCategory::find()->select('id')->where(['sitemap_en' => 0]);
        $query = Business::find()->leftJoin('business_category', 'business_category.id = ANY(business."idCategories")')
            ->where(['business_category.id' => $catQuery]);

        $delCate = $catQuery->column();

        echo "All: {$query->count()}", PHP_EOL;

        /** @var Business[] $models */
        foreach ($query->batch() as $models) {
            foreach ($models as $model) {
                $new_cats = [];
                foreach ($model->idCategories as $idCategory) {
                    if (!in_array((int)$idCategory, $delCate)) {
                        $new_cats[] = $idCategory;
                    }
                }
                if ($new_cats) {
                    $new_cats = '{' . implode(',', array_filter($new_cats)) . '}';
                    echo $model->updateAttributes(['idCategories' => $new_cats]) ? "\e[32mo\e[0m" : "\e[31me\e[0m";
                } else {
                    echo "x";
                }
            }
            echo PHP_EOL;
        }
        $this->log('Фикс категории "Без категории" - OK', false);
    }

    public function actionClearPendingCities()
    {
        $cities = City::find()->select('id')->where(['main' => City::PENDING])->orderBy('id')->column();

        foreach ($cities as $city) {
            $this->actionClear($city, true);
        }
        $max_id = Business::find()->max('id') + 1;
        Yii::$app->db->createCommand("SELECT setval('business_id_seq', $max_id)")->query();
    }

    public function actionClearLog()
    {
        LogParse::deleteAll();
        Yii::$app->db->createCommand("SELECT setval('\"logParseBusiness_id_seq\"', 1)")->query();
    }

    public function actionResetCounter()
    {
        $max_id = Business::find()->max('id') + 1;
        $current = Yii::$app->db->createCommand("SELECT last_value FROM business_id_seq")->queryScalar();
        Yii::$app->db->createCommand("SELECT setval('business_id_seq', $max_id)")->query();
        $this->log("Счётчик был {$current} установлен в {$max_id}", false);
    }
}

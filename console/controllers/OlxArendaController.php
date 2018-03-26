<?php
namespace console\controllers;

use common\models\Ads;
use common\models\ParseHistory;
use console\models\Arenda;
use Exception;
use InvalidArgumentException;
use phpQuery;
use phpQueryObject;
use yii;
use yii\console\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class OlxArendaController extends Controller
{
    const START_SEARCH_CHAR = '?';
    const AND_SEARCH_CHAR = '&';
    const TIME_OUT = 5;

    const PARSER_ID = 100;

    private $showDetailLog = false;

    private $filters;
    private $selectors;
    private $response;

    public function init()
    {
        $this->filters = [
            'base-index-url' => 'http://olx.ua/nedvizhimost/arenda-kvartir/',
            'base-phone-url' => 'http://olx.ua/ajax/misc/contact/phone/',

            'search-q' => 'q-',

            'page' => 'page=',
            'search' => 'search%5B',

            'rooms' => [
                'filter' => 'filter_float_number_of_rooms',
                'from' => '%3Afrom%5D=',
                'to' => '%3Ato%5D=',
            ],
        ];
        $this->selectors = [
            'total-pages' => '/totalPages: \d*/',
            'id-in-url' => '/(?=ID)\w*/',
            'phones-separator' => ' <span class=\'\"block\"\'>',
            'pages' => '.pager.rel.clr > .item.fleft:last > a > span',
            'offers' => '#offers_table',
            'items' => 'table > tbody > tr',
            'link' => '.thumb.vtop.inlblk.rel.tdnone.linkWithHash.scale4.detailsLink',
            'view' => [
                'title' => '#offer_active .offercontentinner .brkword.lheight28',
                'city' => '#offer_active .offercontentinner strong.c2b.small',
                'date' => '#offer_active .clr.offerheadinner.pding15.pdingright20 .pdingleft10.brlefte5',
                'price' => '#offeractions div.pricelabel.tcenter strong',
                'name' => '#offeractions .userbox.clr.rel.zi2.margintop10 .block.color-5.brkword.xx-large',
                'description' => '#textContent .pding10.lheight20.large',
            ],
            'table-under-img' => '#offerdescription .details.fixed.marginbott20.margintop5.full',
            'image' => '#offerdescription .clr .photo-glow .photo-handler.rel.inlblk img',
            'images' => '#offerdescription .tcenter.img-item .photo-glow img',
        ];
        $this->response = [
            'success' => [
                'HTTP/1.1 200 OK',
                'HTTP/1.0 200 OK',
                'HTTP/1.1 301 Moved Permanently',
            ],
        ];
        parent::init();
    }

    /**
     * @param null|string $input
     * @throws Exception
     */
    public function actionIndex($input = null)
    {
        $this->sendEmailAdmin('3dmaxpayne@gmail.com');
        die();
        $this->log('Parser start', null, false);
        if ($input and $decode = json_decode($input)) {
            $input = $input ? $decode : null;
        } elseif ($input) {
            throw new InvalidArgumentException('Параметр должен быть json строкой.');
        }

        $city = isset($input->city) ? (string)$input->city : null;
        $search = isset($input->search) ? (string)$input->search : null;
        $idCity = isset($input->city_id) ? (int)$input->city_id : null;
        $rooms = isset($input->rooms) ? (int)$input->rooms : null;
        $imgs = isset($input->imgs) ? (bool)$input->imgs : null;

        $this->checkIndexActionInput($city, $rooms, $idCity, $search);

        if (!$idCity and $city) {
            $cities = Arenda::$cities;
            $idCity = isset($cities[$city]) ? $cities[$city] : null;
        }

        $url = $this->getFirstPageIndexUrl($city, $rooms, $search);
        $doc = $this->getQueryObject($url);
        $totalPages = (int)$doc->find($this->selectors['pages'])->html();
        if (!count($totalPages)) {
            throw new Exception('Invalid URL. I can\'t count pages');
        } elseif ($totalPages) {
            $this->log("Собираем ссылки с $totalPages страниц", null, false);
        } else {
            $this->log('По запросу ничего не найдено!', 'red', false);
            die();
        }
        $links = $this->parseLinksLoop($url, $imgs, $totalPages);
        $this->echoIndexWorkMsg(count($links));
        $this->parseArrayWithLinks($links, $idCity);
    }

    /**
     * @param null|string $input
     */
    public function actionView($input = null)
    {
        $this->sendEmailAdmin('3dmaxpayne@gmail.com');
        die();
        $this->log('Parser start', null, false);
        if ($input and $decode = json_decode($input)) {
            $input = $input ? $decode : null;
        } elseif ($input) {
            throw new InvalidArgumentException('Параметр должен быть json строкой.');
        }
        $link = isset($input->link) ? (string)$input->link : null;
        $idCity = isset($input->city_id) ? (int)$input->city_id : null;

        $this->checkViewActionInput($link, $idCity);
        if ($this->checkLink($link)) {
            $model = new Ads();
            $object = new Arenda();
            $document = $this->getQueryObject($link);
            $this->log('Уже парсим', 'blue', false);
            $this->parseLink($link, $model, $document, $object);
            if ($idCity) {
                $object->idCity = $idCity;
            }
            $object->setAdsFromArenda($model);
            VarDumper::dump($object);
        } else {
            $this->log('Уже парсили', 'red', false);
        }
    }

    public function actionInit()
    {
        $this->log('---------->', 'red', false);
        $this->echoWarningWhenInit();
        Arenda::addCustomFieldCategory();
        Arenda::insertCustomFields();
        $this->log('<----------', 'red', false);
    }

    private function echoWarningWhenInit()
    {
        sleep(1);
        $this->log('Этот метод пересоздаст кастомфилды разработаные при создании парсера', 'blue', false);
        sleep(3);
        $this->log('Это действие заменит существующие данные, возможны ошибки!', 'red', false);
        sleep(3);
        $this->log('Вы уверены? Операция начнется через 10 секунд!', 'red', false);
        sleep(1);
        $this->log('Для отмены нажмите Ctrl+Z', 'yellow', false);
        for ($i = 10; $i >= 0; $i--) {
            echo $i, '..';
            sleep(1);
        }
        $this->log('Start', 'red', false);
        sleep(2);
    }
    
    /**
     * @param string $city
     * @param int $rooms
     * @param int $idCity
     * @param string $search
     */
    private function checkIndexActionInput($city, $rooms, $idCity, $search)
    {
        if (!is_null($city) and !is_string($city)) {
            throw new InvalidArgumentException('\'city\' должен быть строкой. Вы ввели: ' . gettype($city));
        } elseif (!is_null($search) and !is_string($search)) {
            throw new InvalidArgumentException('\'search\' должен быть строкой. Вы ввели: ' . gettype($search));
        } elseif (!is_null($idCity) and !is_int($idCity)) {
            throw new InvalidArgumentException('\'city\' должен быть числом. Вы ввели: ' . gettype($idCity));
        } elseif (!is_null($rooms) and !is_int($rooms)) {
            throw new InvalidArgumentException('\'rooms\' должен быть числом. Вы ввели: ' . gettype($rooms));
        }
    }

    /**
     * @param string $link
     * @param null|int $idCity
     */
    private function checkViewActionInput($link, $idCity)
    {
        if (!is_string($link)) {
            throw new InvalidArgumentException('\'link\' должен быть строкой. Вы ввели: ' . gettype($link));
        } elseif (!is_null($idCity) and !is_int($idCity)) {
            throw new InvalidArgumentException('\'id-city\' должен быть числом. Вы ввели: ' . gettype($idCity));
        }
    }

    /**
     * @param int $rooms
     * @return string
     */
    private function getRoomsFilter($rooms)
    {
        $url[] = $this->filters['search'];
        $url[] = $this->filters['rooms']['filter'];
        $url[] = $this->filters['rooms']['from'] . $rooms;
        $url[] = $this::AND_SEARCH_CHAR;
        $url[] = $this->filters['search'];
        $url[] = $this->filters['rooms']['filter'];
        $url[] = $this->filters['rooms']['to'] . $rooms;
        return implode($url);
    }

    /**
     * @param string $city
     * @param int $rooms
     * @param string $search
     * @return string
     */
    private function getFirstPageIndexUrl($city, $rooms, $search)
    {
        $url[] = $this->filters['base-index-url'];
        if ($city) {
            $url[] = $city . '/';
        }
        if ($search) {
            $url[] = 'q-' . urlencode($search) . '/';
        }
        $url[] = $this::START_SEARCH_CHAR;
        if ($rooms) {
            $url[] = $this->getRoomsFilter($rooms);
        }
        return implode($url);
    }

    /**
     * @param string $link
     * @return null|string
     */
    private function getPhoneNumber($link)
    {
        $phone = null;
        preg_match($this->selectors['id-in-url'], $link, $id);
        $id = isset($id[0]) ? substr($id[0], 2) : null;
        if ($id) {
            $phoneUrl = $this->filters['base-phone-url'] . $id;
            if ($phonePage = $this->getQueryObject($phoneUrl)) {
                $phonePage = str_replace($this->selectors['phones-separator'], ', ', $phonePage->html());
                $phonePage = strip_tags($phonePage);
                if ($phonePage and ($phone = json_decode($phonePage))) {
                    $phone = isset($phone->value) ? trim($phone->value) : null;
                } else {
                    $this->log('Ошибка при запросе телефона. '
                        . 'Valid url: ' . ((bool)$phonePage ? 'true' : 'false')
                        . '. Valid json:' . ((bool)$phone ? 'true' : 'false'), 'red', true);
                }
            } else {
                $this->log('Ошибка на странице: ' . $link, 'gray', true);
                $this->log('', null, true);
            }
        } else {
            $this->log('Error with parse \'id\' from url', 'red');
        }
        return $phone;
    }

    /**
     * @param string $url
     * @param bool $imgs
     * @param int $totalPages
     * @return array
     */
    private function parseLinksLoop($url, $imgs, $totalPages)
    {
        $links = [];
        $curUrl = $url;
        $i = 1;
        $progress = 0;
        do {
            if ($linkFromThisPage = $this->parseIndex($curUrl, $imgs)) {
                $links = ArrayHelper::merge($links, $linkFromThisPage);
            }
            $progress = $this->echoProgress($i, $totalPages, $progress);
            sleep($this::TIME_OUT);
            $i++;
            $curUrl = $url . self::AND_SEARCH_CHAR . $this->filters['page'] . $i;
        } while ($i <= $totalPages);
        return $links;
    }

    /**
     * @param string $link
     * @param bool $imgs
     * @return array
     */
    private function parseIndex($link, $imgs)
    {
        $links = [];
        $document = $this->getQueryObject($link);
        $adsTables = $document->find($this->selectors['offers']);
        $table = pq($adsTables[0]);
        /* @var $table phpQueryObject */
        $allTrFromTable = $table->find($this->selectors['items']);
        foreach ($allTrFromTable as $key => $element) {
            $tr = pq($element);
            /* @var $tr phpQueryObject */
            $a = $tr->find($this->selectors['link']);
            if (count($a)) {
                $a = pq($a[0]);
                /* @var $a phpQueryObject */
                $img = $a->find('img');
                if (!$imgs or ($imgs and count($img))) {
                    $a = pq($a);
                    /* @var $a phpQueryObject */
                    $url = $a->attr('href');
                    $links[] = ($this->checkLink($url)) ? $url : null;
                }
            }
        }
        return $links;
    }

    /**
     * @param array $array
     * @param int $idCity
     */
    private function parseArrayWithLinks($array, $idCity)
    {
        $i = 1;
        $all = count($array);
        $progress = 0;
        $array = array_unique(array_filter($array));
        foreach ($array as $link) {
            $model = new Ads();
            $object = new Arenda();
            $document = $this->getQueryObject($link);
            $this->parseLink($link, $model, $document, $object);
            $object->idCity = $idCity;
            $object->setAdsFromArenda($model);
            $progress = $this->echoProgress($i, $all, $progress);
            $i++;
        }
    }

    private function saveLink($link)
    {
        $link = explode('#', $link, 2)[0];
        $history = new ParseHistory([
            'element' => $link,
            'parser_id' => self::PARSER_ID,
        ]);
        $history->save();
    }

    private function checkLink($link)
    {
        $link = explode('#', $link, 2)[0];
        $history = (int)ParseHistory::find()->where(['parser_id' => self::PARSER_ID, 'element' => $link])->count();
        return ($history > 0) ? false : true;
    }

    /**
     * @param string $link
     * @param Ads $model
     * @param phpQueryObject $document
     * @param Arenda $object
     */
    private function parseLink($link, Ads $model, phpQueryObject $document, Arenda $object)
    {
        foreach ($this->selectors['view'] as $field => $value) {
            $object->{$field} = $this->selectFromPage($field, $value, $document);
            if ($object->{$field}) {
                switch ($field) {
                    case 'date':
                        $object->{$field} = trim(strip_tags((string)$object->{$field}));
                        $pos = strrpos($object->{$field}, ', Номер объявления:');
                        $object->{$field} = substr($object->{$field}, 0, $pos);
                        $object->{$field} = str_replace(["\t", '  '], '', $object->{$field});
                        break;
                    case 'price':
                        $object->{$field} = trim(strip_tags((string)$object->{$field}));
                        $object->{$field} = (int)str_replace([' ', 'грн.'], '', $object->{$field});
                        break;
                    default:
                        $object->{$field} = (string)trim(strip_tags($object->{$field}));
                }
            } else {
                $this->log("Ссылка: $link", 'gray', true);
            }
        }

        $object->url = $link;
        $object->phone = $this->getPhoneNumber($link);
        $this->setCustomFields($document, $object);

        $object->image = $this->getImage($this->selectors['image'], $document, $model);
        $object->images = $this->getImages($this->selectors['images'], $document, $model);
        $this->saveLink($link);
        sleep($this::TIME_OUT);
    }

    /**
     * @param int $i
     * @param int $all
     * @param int $progress
     * @return int
     */
    private function echoProgress($i, $all, $progress)
    {
        $step = ceil(200 / $all);
        if (!(($p = ceil($i * 100 / $all)) % $step) and ($p !== $progress)) {
            $this->log("Завершено: {$p}%", 'green', false);
        }
        return $p;
    }

    /**
     * @param int $count
     */
    private function echoIndexWorkMsg($count)
    {
        $this->log("Собрано $count ссылок. Начинаю парсинг!", 'green', false);
        $time = ceil(($count * (self::TIME_OUT + 5)) / 60);
        $this->log("Ожидаемое время выполнения: $time мин", 'blue', false);
    }

    /**
     * @param string $link
     * @return null|phpQueryObject
     */
    private function getQueryObject($link)
    {
        $return = null;
        $client = new Client();
        try {
            $res = $client->request('GET', $link);
            $body = $res->getBody();
            /** @noinspection PhpUndefinedClassInspection */
            $return = phpQuery::newDocumentHTML($body);
        } catch (RequestException $e) {
            $this->log("Ссылка: $link", 'gray', true);
            $this->log('Ответ: ' . $e->getResponse()->getStatusCode(), 'red', true);
        }
        return $return;
    }

    /**
     * @param string $field
     * @param string $value
     * @param phpQueryObject $document
     * @return null|string
     */
    private function selectFromPage($field, $value, phpQueryObject $document)
    {
        $return = $document->find($value)->html();
        if (!$return) {
            $return = null;
            $this->log("Не могу получить '$field'", 'red', true);
        }
        return $return;
    }

    /**
     * @param string $selector
     * @param phpQueryObject $document
     * @param Ads $model
     * @return null|string
     */
    private function getImage($selector, phpQueryObject $document, Ads $model)
    {
        $return = null;
        $url = $document->find($selector)->attr('src');
        if (!$url) {
            $this->log('Error: image url is undefined', 'red', true);
            return null;
        }
        $img = $this->getImageInVar($url);
        if ($img['mimeType'] === 'image/jpeg') {
            /** @noinspection PhpUndefinedFieldInspection */
            $return = Yii::$app->files->uploadFromUrl($model, 'image', $img);
            unlink($img['file']);
        }
        return $return;
    }

    /**
     * @param string $selector
     * @param phpQueryObject $document
     * @param Ads $model
     * @return array|null
     */
    private function getImages($selector, phpQueryObject $document, Ads $model)
    {
        $return = null;
        $urls = $document->find($selector);
        if (!$urls) {
            $this->log('Error: images urls is undefined', 'red', true);
        } else {
            foreach ($urls as $url) {
                $url = pq($url);
                /* @var $url phpQueryObject */
                $url = $url->attr('src');
                $img = $this->getImageInVar($url);
                if ($img['mimeType'] === 'image/jpeg') {
                    /** @noinspection PhpUndefinedFieldInspection */
                    $return[] = Yii::$app->files->uploadFromUrl($model, 'images', $img);
                    unlink($img['file']);
                } else {
                    $this->log('Error: images is incorrect', 'red', true);
                }
            }
        }
        return $return;
    }

    /**
     * @param string $url
     * @return array|null
     */
    private function getImageInVar($url)
    {
        $return = null;
        $file = basename($url);
        $head = get_headers($url, true);
        $doesExist = in_array($head[0], $this->response['success'], true);
        if ($doesExist and ($content = file_get_contents($url))) {
            $f = fopen("$file", "w");
            if (fwrite($f, $content) === false) {
                $this->log('Не могу произвести запись в файл.', 'red');
            } else {
                $fileSize = filesize($file);
                $mimeType = mime_content_type($file);
                fclose($f);
                $return = ['file' => $file, 'filesize' => $fileSize, 'mimeType' => $mimeType];
            }
        } else {
            $this->log('Не скачать картинку. Код ответа: ' . $head[0], 'red');
        }
        return $return;
    }

    /**
     * @param phpQueryObject $document
     * @return null|array
     */
    private function getInfoTableUnderImage(phpQueryObject $document)
    {
        $table = $this->selectFromPage('table', $this->selectors['table-under-img'], $document);
        $search = [
            0 => 'Объявление от',
            1 => 'Тип аренды',
            2 => 'Тип',
            3 => 'Количество комнат',
            4 => 'Общая площадь',
            5 => 'Жилая площадь',
            6 => 'Этажность дома',
            7 => 'Этаж',
            8 => PHP_EOL,
            9 => "\t",
        ];
        $replace = [
            0 => '", "from": "',
            1 => '", "type": "',
            2 => '", "building": "',
            3 => '", "rooms": "',
            4 => '", "area": "',
            5 => '", "area_in_use": "',
            6 => '", "flows": "',
            7 => '", "flow": "',
            8 => '',
            9 => '',
        ];
        $table = (string)trim(strip_tags($table));
        $json = '{"start": "' . str_replace($search, $replace, $table) . '", "stop": ""}';
        $table = json_decode($json, true);
        if (!$table) {
            $this->log('!!Ошибка с доп. полями: ' . $json, 'red', true);
        }
        return $table;
    }

    /**
     * @param phpQueryObject $document
     * @param Arenda $object
     */
    private function setCustomFields(phpQueryObject $document, Arenda $object)
    {
        $table = $this->getInfoTableUnderImage($document);
        
        $object->rooms = isset($table['rooms']) ? (int)$table['rooms'] : null;
        $object->flow = isset($table['flow']) ? (int)$table['flow'] : null;
        $object->flows = isset($table['flows']) ? (int)$table['flows'] : null;
        $object->type = isset($table['type']) ? (string)$table['type'] : null;
        $object->ads_type = isset($table['from']) ? (string)$table['from'] : null;
        $object->area_in_use = isset($table['area_in_use']) ? (string)$table['area_in_use'] : null;
        $object->area = isset($table['area']) ? (string)$table['area'] : null;
        $object->building = isset($table['building']) ? (string)$table['building'] : null;
    }

    /**
     * @param string $string
     * @param null|string $color
     * @param bool $detail
     * @return null
     */
    private function log($string, $color = null, $detail = false)
    {
        if ($detail and !$this->showDetailLog) {
            return null;
        }
        $colors = [
            'red' => "\e[31m",
            'blue' => "\e[34m",
            'yellow' => "\e[1;33m",
            'normal' => "\e[0m",
            'green' => "\e[32m",
            'gray' => "\e[1;30m",
        ];
        $color = ($color and isset($colors[$color])) ? $colors[$color] : $colors['normal'];
        echo $color, $string, $colors['normal'], PHP_EOL;
        return null;
    }

    public function sendEmailAdmin($email)
    {
        $text = 'OlxArendaController started';
        Yii::$app->mailer->compose()
            ->setFrom([Yii::$app->modules['user']->mailer['sender'] => 'CityLife'])->setTo($email)
            ->setSubject('OlxParserStart')
            ->setTextBody($text)
            ->send();
    }
}

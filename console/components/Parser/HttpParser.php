<?php
namespace console\components\Parser;

use Exception;
use phpQuery;
use yii;

abstract class HttpParser
{
    const DEFAULT_TIMEOUT = 10;
    
    const DOWNLOAD_STATUS_OK = 1;
    const DOWNLOAD_STATUS_ERROR = 2;
    
    const COOKIES_CACHE = 'http_cookies_cache';

    /** @var integer $download_status */
    public $download_status;
    
    /** @var string $link */
    protected $link;

    /** @var \phpQueryObject[] $content */
    protected $contents;

    /** @var mixed[] $data */
    protected $data;

    /** @var string[] $langs */
    protected $langs = ['ru', 'uk'];

    /** @var int $timeout */
    protected $timeout;

    abstract public function parseData();

    /**
     * HttpParser constructor.
     * @param $link string
     * @param int $timeout
     */
    public function __construct($link, $timeout = self::DEFAULT_TIMEOUT)
    {
        $this->link = $link;
        $this->timeout = $timeout;

        return $this;
    }

    public function downloadContent()
    {
        $cookies = [];
        if (Yii::$app->cache->exists(self::COOKIES_CACHE)) {
            $cookies = Yii::$app->cache->get(self::COOKIES_CACHE);

            if (isset($cookies['language'])) {
                unset($cookies['language']);
            }
        }
        
        $header = 'Cookie: ';
        foreach ($cookies as $cookie => $value) {
            $header .= "$cookie=$value; ";
        }

        foreach ($this->langs as $lang) {
            $default_opts = ['http' => ['method' => 'GET', 'header' => "{$header}language={$lang}"]];
            $context = stream_context_get_default($default_opts);
            $this->contents[$lang] = $this->download($this->link, $context);
        }

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function saveDataToCache($key)
    {
        $cache = Yii::$app->cache;

        if ($cache->exists($key)) {
            $cache->delete($key);
        }

        $cache->add($key, $this->data);

        return $this;
    }

    public function initDataFromCache($key)
    {
        $this->data = Yii::$app->cache->get($key);
        
        return $this;
    }

    protected function log($str, $eol = true)
    {
        if ($eol) {
            echo date('d.m.Y H:i:s : '), $str, PHP_EOL;
        } else {
            echo $str;
        }
    }

    protected function download($url, $context = null)
    {
        $content = null;

        while (!$content) {
            try {
                $content = file_get_contents($url, null, $context);
                $content = phpQuery::newDocumentHTML($content, 'utf-8');
            } catch (Exception $e) {
                $this->download_status = self::DOWNLOAD_STATUS_ERROR;
                $this->log("Error when download $url: {$e->getMessage()}");
                break;
            }

            if ($content) {
                $this->updateCookies();

                if (count($content->find('div#g-recaptcha')) > 0) {
                    $this->log('Captcha needed');
                    sleep($this->getCaptchaTimeOut());
                    $content = null;
                } else {
                    $this->download_status = self::DOWNLOAD_STATUS_OK;
                }
            }
        }

        if ($this->download_status = self::DOWNLOAD_STATUS_OK) {
            sleep($this->timeout);
        }

        return $content;
    }

    protected function getCaptchaTimeOut()
    {
        $hour = (int)date('H');

        if (($hour < 6)) {
            return 3600 * (6 - $hour);
        } elseif ($hour < 18) {
            return 600;
        } elseif ($hour < 22) {
            return 1800;
        } else {
            return 3600;
        }
    }

    protected function updateCookies()
    {
        if (empty($http_response_header)) {
            return false;
        }

        $cookies = [];
        if (Yii::$app->cache->exists(self::COOKIES_CACHE)) {
            $cookies = Yii::$app->cache->get(self::COOKIES_CACHE);
        }

        //Парсим заголовоки ответа, $http_response_header - magic variable
        foreach ($http_response_header as $hdr) {
            if (preg_match('/^Set-Cookie:\s*([^;]+)/', $hdr, $matches)) {
                parse_str($matches[1], $cookies_array);

                foreach ($cookies_array as $cookie => $value) {
                    if ($value === 'deleted') {
                        if (isset($cookies[$cookie])) {
                            unset($cookies[$cookie]);
                        }
                    } else {
                        $cookies[$cookie] = $value;
                    }
                }
            }
        }
        Yii::$app->cache->set(self::COOKIES_CACHE, $cookies);

        return true;
    }
}

<?php

namespace console\controllers;

use common\extensions\ApiVk\MyJsonClass;
use common\extensions\ApiVk\objects\PhotoResponse;
use common\models\City;
use common\models\File;
use common\models\Lang;
use common\models\Wall;
use GuzzleHttp\Client;
use yii;
use yii\console\Controller;
use common\extensions\ApiVk\ApiVk;

/**
 * Class VkWallPostController
 * @package console\controllers
 */
class VkWallPostController extends Controller
{
    const TIMEOUT = 180;
    const IMAGE_OPEN_REPEAT = 5;
    const MAX_EXECUTE_TIME = 7200;

    private $admins_id;
    /** @var string $admin_id */
    private $admin_id;
    /** @var $groups array */
    private $groups;
    /** @var $curGroup integer */
    private $curGroup;
    /** @var $admins array */
    private $admins;
    /** @var $curAdmin integer */
    private $curAdmin;
    /** @var string $lock_file */
    private $lock_file = 'vk_wall_post.lock';

    const DEF_TXT = "\e[0m";
    const RED_TXT = "\e[31m";
    const YEL_TXT = "\e[1;33m";
    const GRE_TXT = "\e[32m";

    public function init()
    {
        parent::init();
        chdir(Yii::$aliases['@console']);
        $this->groups = City::find()->select(['vk_public_id', 'id'])
            ->where(['not', ['vk_public_id' => null]])
            ->andWhere(['not', ['vk_public_admin_token' => null]])
            ->andWhere(['not', ['vk_public_admin_id' => null]])
            ->indexBy('id')->column();

        $this->admins = City::find()->select(['vk_public_admin_token', 'vk_public_id'])
            ->where(['not', ['vk_public_id' => null]])
            ->andWhere(['not', ['vk_public_admin_token' => null]])
            ->andWhere(['not', ['vk_public_admin_id' => null]])
            ->indexBy('vk_public_id')->column();

        $this->admins_id = City::find()->select(['vk_public_admin_id', 'vk_public_id'])
            ->where(['not', ['vk_public_id' => null]])
            ->andWhere(['not', ['vk_public_admin_token' => null]])
            ->andWhere(['not', ['vk_public_admin_id' => null]])
            ->indexBy('vk_public_id')->column();

        if (empty($this->groups) or empty($this->admins) or empty($this->admins_id)) {
            echo self::RED_TXT, 'Недостаточно данных для выполнения скрипта.', self::DEF_TXT, PHP_EOL;
            die();
        }
    }

    public function actionIndex()
    {
        Lang::setCurrent('ru-RU');
        $this->actionMarkOldAfisha();
        foreach ($this->groups as $idCity => $this->curGroup) {
            $this->curAdmin = $this->admins[$this->curGroup];
            $this->admin_id = $this->admins_id[$this->curGroup];

            $city = City::findOne($idCity);
            echo 'Для города ', self::GRE_TXT, $city->title, self::DEF_TXT,
            ' новых элементов: ', self::YEL_TXT, Wall::getCountUnpublished($idCity), self::DEF_TXT;

            if ($model = Wall::getUnpublishedOne($idCity)) {
                $p = $this->echoPost($model, $city);
                if ($p === true) {
                    $this->saveSuccessModel($model);
                } elseif (is_int($p)) {
                    if ($p === 9) {
                        //Flood control - скорее всего без картинки
                        $this->saveSuccessModel($model);
                    }
                }
            }
            echo PHP_EOL;
        }
    }

    /**
     * Запускаем 1 раз после миграции,
     * что бы не публиковались уже существующие новости.
     * @param null $idCity
     */
    public function actionInit($idCity = null)
    {
        if ($idCity) {
            Wall::updateAll(['published' => 1], ['published' => null, 'idCity' => (int)$idCity]);
        } else {
            Wall::updateAll(['published' => 1], ['published' => null]);
        }

    }

    /**
     * @param $url string
     * @param $vk ApiVk
     * @return PhotoResponse[]
     */
    private function uploadImageToVk($url, $vk)
    {
        $return = null;
        if ($url and ($image = $this->getFile($url))) {
            $server = $vk->photosGetWallUploadServer($this->curGroup);
            if ($server) {
                $client = new Client();
                $response = $client->request(
                    'POST',
                    $server,
                    [
                        'multipart' => [['name' => 'photo', 'contents' => $image]],
                        'headers' => ['Content-Length' => $this->getHeaders($url)],
                    ]
                );
                $answer = $response->getBody();
                $photos = MyJsonClass::decodeJson($answer);
                $return = !empty($photos) ? $vk->photosSaveWallPhoto($photos, null, $this->curGroup) : null;
            }
        }
        return $return;
    }

    /**
     * @param $model Wall
     * @param $vk ApiVk
     * @return null
     */
    private function uploadVideoToVk($model, $vk)
    {
        $return = null;
        $video = isset($model->mod->video) ? (is_array($model->mod->video) ? array_values($model->mod->video)[0] : $model->mod->video) : null;
        if (!empty($video)) {
            $video = "http://youtube.com/watch?v=$video";
            $desc = $this->crop($model->description, 500);
            $server = $vk->videoSave($model->title, $desc, $video, 1);

            if ($server) {
                $client = new Client();
                $response = $client->request('POST', $server);
                $answer = $response->getBody();
                $result = MyJsonClass::decodeJson($answer);
            }
            if (isset($result) and !empty($result->response)) {
                $return = $vk->videoGet($this->admin_id);
            }
        }

        return empty($return->link) ? null : $return->link;
    }

    /**
     * @param Wall $model
     * @param City $city
     * @return bool
     * @internal param string $text
     * @internal param string $file
     * @internal param string $link
     */
    private function echoPost($model, City $city)
    {
        $status = false;
        $vk = new ApiVk($this->curAdmin);
        $photos = $this->uploadImageToVk($model->image, $vk);
        $video = $this->uploadVideoToVk($model, $vk);
        $attach = $this->getAttach($photos, null, $video);

        $text = $this->getPostText($model, $city);

        if ($vk->wallPost($text, "-{$this->curGroup}", $attach)) {
            $status = true;
        } elseif ($vk->errorCode) {
            echo $model->id, PHP_EOL;
            $status = (int)$vk->errorCode;
        } else {
            echo $model->id, PHP_EOL;
        }

        $vk->logErrors();

        return $status;
    }

    /**
     * @param $url string
     * @return false|resource
     */
    private function getFile($url)
    {
        $i = self::IMAGE_OPEN_REPEAT;
        $file = false;
        while (!$file and ($i-- > 0)) {
            $file = @fopen($url, 'r');
        }
        return $file;
    }

    /**
     * @param null|PhotoResponse[] $photos
     * @param null|string $link
     * @param \stdClass $video
     * @return array
     */
    private function getAttach($photos = null, $link = null, $video = null)
    {
        $attach = [];
        if (!empty($photos) and is_array($photos)) {
            foreach ($photos as $photo) {
                $attach[] = isset($photo->id) ? "photo{$photo->owner_id}_{$photo->id}" : null;
            }
        }
        $attach[] = $link ? $link : null;
        $attach[] = $video;

        return $attach;
    }

    /**
     * @param $model Wall
     * @return string
     */
    private function createUrl($model)
    {
        $subDomain = ($model->city) ? $model->city->subdomain : '';
        $front = Yii::$app->params['appFrontend'];
        $type = Wall::$typesAlias[$model->type];

        return "http://{$subDomain}.{$front}/{$type}/{$model->pid}-{$model->url}";
    }

    public function actionMarkOldAfisha()
    {
        $afisha = Wall::getOldUnpublished();
        if ($afisha) {
            echo 'Найдено архивных афиш: ', count($afisha), PHP_EOL;
            foreach ($afisha as $item) {
                $this->saveSuccessModel($item);
            }
            echo 'Афиши убраны!', PHP_EOL;
        }
    }

    /**
     * @param Wall $model
     * @throws \Exception
     */
    private function saveSuccessModel(Wall &$model)
    {
        $model->published = 1;
        $model->update(false, ['published']);
    }

    public function checkExecute()
    {
        $return = true;

        $lock_file = @fopen($this->lock_file, 'rt');
        if ($lock_file) {
            $date = @fread($lock_file, @filesize($this->lock_file));
            @fclose($lock_file);
            if ((strtotime($date) + self::MAX_EXECUTE_TIME) > time()) {
                echo self::RED_TXT, 'Идет выполнение', self::DEF_TXT, PHP_EOL;
                $return = false;
            }
        }
        if ($return) {
            $lock_file = @fopen($this->lock_file, 'wt');
            if ($lock_file) {
                @fwrite($lock_file, date('Y-m-d H:i:s'));
                @fclose($lock_file);
            } else {
                echo 'Не удалось создать файл', PHP_EOL;
                $return = false;
            }
        }
        return $return;
    }

    public function deleteLockFile()
    {
        $lock_file = @fopen($this->lock_file, 'rt');
        if ($lock_file) {
            @fclose($lock_file);
            @unlink($this->lock_file);
        }
    }

    /**
     * @param $url string
     * @return string
     */
    public function getHeaders($url)
    {
        $h = @get_headers($url, 1);
        return empty($h['Content-Length']) ? '0' : $h['Content-Length'];
    }

    private function getPostText(Wall $model,City $city)
    {
        $city_title = [];
        foreach (['ru', 'uk'] as $l) {
            Lang::setCurrent($l);
            $city_title[$l] = $city->title;
        }
        Lang::setCurrent('ru');

        //Декодируем из html encode
        $desc = html_entity_decode($model->description);
        //Обрезаем и тримим
        $desc = trim($this->crop($desc, 500));
        $title = trim($model->title);
        $linker = function ($url) use ($city) {
            $frontend = Yii::$app->params['appFrontend'];

            return "http://{$city->subdomain}.{$frontend}/{$url}";
        };

        switch ($model->type) {
            case (File::TYPE_AFISHA):
                $text = [
                    $title, PHP_EOL,
                    $this->createUrl($model),
                    "Все события {$city->title_ge} {$linker('afisha')}", PHP_EOL,
                    $desc,
                    "#афиша #афіша #{$city_title['ru']} #{$city_title['uk']} #{$city->subdomain} #citylife",
                ];
                break;
            case (File::TYPE_AFISHA_WITH_SCHEDULE):
                $text = [
                    $title, PHP_EOL,
                    "Расписание: {$this->createUrl($model)}",
                    "Все события {$city->title_ge} {$linker('afisha')}", PHP_EOL,
                    $desc,
                    "#афиша #афіша #кино #кіно #премьера #{$city_title['ru']} #{$city_title['uk']} #{$city->subdomain} #citylife",
                ];
                break;
            case (File::TYPE_AFISHA_WITHOUT_SCHEDULE):
                $text = [
                    "«{$title}» cкоро в кинотеатрах {$city->title_ge}", PHP_EOL,
                    $desc,
                    "#афиша #афіша #кино #кіно #премьера #{$city_title['ru']} #{$city_title['uk']} #{$city->subdomain} #citylife",
                ];
                break;
            case (File::TYPE_ACTION):
                $text = [
                    $title, PHP_EOL,
                    $this->createUrl($model),
                    "Все акции {$city->title_ge} {$linker('action')}", PHP_EOL,
                    $desc,
                    "#акции #акції #скидки #знижки #распродажи #розпродаж #{$city_title['ru']} #{$city_title['uk']} #{$city->subdomain} #citylife",
                ];
                break;
            case (File::TYPE_POST):
                $text = [
                    $title, PHP_EOL,
                    $this->createUrl($model),
                    "Все новости {$city->title_ge} {$linker('post')}", PHP_EOL,
                    $desc,
                    "#новости #новини #події #события #{$city_title['ru']} #{$city_title['uk']} #{$city->subdomain} #citylife",
                ];
                break;
            default:
                $text = [
                    $title, PHP_EOL,
                    $this->createUrl($model),
                    $desc,
                ];
        }
        return implode(PHP_EOL, $text);
    }

    private function crop($string, $limit)
    {
        if (mb_strlen($string, 'utf-8') > $limit) {
            $title = mb_substr($string, 0, $limit, 'utf-8');
            $lastSpace = mb_strrpos($title, ' ', 'utf-8');
            $string = mb_substr($title, 0, $lastSpace, 'utf-8');
            $string .= '...';
        }

        return $string;
    }
}

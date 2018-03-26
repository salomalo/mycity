<?php

namespace console\controllers;

use common\models\Afisha;
use common\models\AfishaCategory;
use common\models\City;
use common\models\KinoGenre;
use common\models\ParseKino;
use common\models\ScheduleKino;
use common\models\SystemLog;
use console\components\Parser\kino\DateLinkParser;
use console\components\Parser\kino\FilmParser;
use console\components\Parser\kino\LinkParser;
use console\components\Parser\kino\SeanseParser;
use DateTimeZone;
use Yii;
use yii\console\Controller;

class AfishaParserController extends Controller
{
    const TIMEOUT = 60;
    private $response;

    public function init()
    {
        $this->response = [
            'success' => [
                'HTTP/1.1 200 OK',
                'HTTP/1.0 200 OK',
                'HTTP/1.1 301 Moved Permanently',
            ],
        ];
    }


    /**
     * Метод ищет и возвращает $model из базы данных
     *
     * @param $model ScheduleKino
     * @return array|null|\yii\db\ActiveRecord
     */
    private function findScheduleKino($model)
    {
        $model = ScheduleKino::find()
            ->where(['idAfisha' => $model->idAfisha])
            ->andWhere(['idCity' => $model->idCity])
            ->andWhere(['idCompany' => $model->idCompany])
            ->andWhere(['<=', 'dateStart', $model->dateStart])
            ->andWhere(['>=', 'dateEnd', $model->dateEnd])
            ->one();

        return $model;
    }

    /**
     * Получает id кинотеатра с ссылки
     *
     * @param $link
     * @return mixed
     */
    private function getIdCinemaFromLink($link)
    {
        $matchCount = preg_match("/cinema\/(.*?)\//", $link, $matches);

        if ($matchCount == 1) {
            return $matches[1];
        } else {
            return false;
        }
    }

    /**
     * Метод добавляет сеансы с в бд
     *
     * @param $value array с информацией о сеансе
     * @param $afisha Afisha
     * @param $city_in_db City
     * @param $link_with_data_item string страницы со списком сеансов
     * @return int к-во добавленых сеансов
     */
    private function addScheduleKino($value, $afisha, $city_in_db, $link_with_data_item, $company_id){
        $count = 0;

        $model = new ScheduleKino();
        $model->idAfisha = $afisha->id;
        $model->price = $value['price'];
        $model->times2D = $value['time'];
        $model->times3D = $value['time_3D'];
        $model->idCity = $city_in_db->id;

        preg_match("/[\?|&]date=([^&]+)/", $link_with_data_item['link'], $matches);
        $date = new \DateTime($matches[1]);
        $model->dateStart = $date->format('Y-m-d');
        $model->dateEnd = $date->format('Y-m-d');
        $model->idCompany = $company_id;

        $find_model = $this->findScheduleKino($model);
        if (!$find_model) {
            if ($model->save()) {
                $count++;
            }
        } else {
            $find_model->times2D = $value['time'];
            $find_model->times3D = $value['time_3D'];
            $find_model->dateStart = $date->format('Y-m-d');
            $find_model->dateEnd = $date->format('Y-m-d');

            $find_model->update();
        }

        return $count;
    }



    /**
     * Метод загружает картинку с сайта
     *
     * @param $url string на картинку на другом сервере
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
            } else {
                $fileSize = filesize($file);
                $mimeType = mime_content_type($file);
                fclose($f);
                $return = ['file' => $file, 'filesize' => $fileSize, 'mimeType' => $mimeType];
            }
        }

        return $return;
    }

    /**
     * Метод добавляет фильм в бд.
     *
     * @param $link string ссылка на описание фильма
     * @param $film_title string название фильма
     * @return bool|Afisha
     */
    private function addAfish($link, $film_title)
    {
        $film = new FilmParser($link);
        $film->downloadContent()->parseData();

        if ($film) {
            $kino_genre = KinoGenre::find()->all();
            $array_genre = array();
            //проверяем есть пропаршенные жанры в бд
            foreach ($kino_genre as $kino) {
                foreach ($film->genre as $value) {
                    if ($kino->title == $value) {
                        $array_genre[] = $kino->id;
                    }
                }
            }

            if (empty($array_genre)){
                $array_genre[] = KinoGenre::find()->one()->id;
            }

            $model = new Afisha();
            $model->genre = $array_genre;
            $model->scenario = 'isFilm';
            $model->isFilm = 1;
            $model->title = $film_title;
            $model->idCategory = '9';
            $model->actors = $film->actors;
            $model->country = $film->country;
            $model->director = $film->director;
            $model->year = $film->year;
            $model->description = $film->description;
            $model->fullText = $film->description;
            $model->isChecked = 0;

            //загружаем картинку для афиши
            //пока закоментил, так как расширение картинки маленькое
//            $img = $this->getImageInVar($film->image_link);
//            if ($img['mimeType'] === 'image/jpeg') {
//                Yii::$app->files->uploadFromUrl($model, 'image', $img);
//            }
//            unlink($img['file']);

            if ($model->validate()) {
                if ($model->save()) {
                    return $model;
                }
            }
            return false;
        } else {
            return false;
        }
    }

    /**
     * Метод парсит все сеансы фильмов с http://kino.i.ua/afisha за неделю
     * и добавляет в бд. Если фильма нету в бд, то фильм туда добавляется.
     */
    public function actionParseKino()
    {
        $current_language = Yii::$app->language;
        Yii::$app->language = 'ru-RU';

        $cities_db = City::find()->where(['main' => '2'])->all();

        $link = new LinkParser('http://kino.i.ua/cinema', self::TIMEOUT);
        $link->downloadContent()->parseData();
        //получаем список городов
        $cities = $link->getData();

        $count_afisha = 0;
        foreach ($cities as $city) {
            foreach ($cities_db as $city_in_db) {
                //если город совпал с активным
                if ($city_in_db->title == $city['title']['ru']) {
                    $count_shedule_kino = 0;
                    echo 'Парсим фильмы в городе: ' . $city_in_db->title, PHP_EOL;

                    //получаем  список ссылок с датой
                    $link_with_data = new DateLinkParser($city['link'], self::TIMEOUT);
                    $link_with_data->downloadContent()->parseData();
                    $cities_data_link = $link_with_data->getData();

                    //Проходим циклом по этому списку
                    foreach ($cities_data_link as $link_with_data_item) {
                        //получаем список ссылок с кинотеатрами
                        $kino_parser = new SeanseParser($link_with_data_item['link']);
                        $kino_parser->downloadContent()->parseData();
                        $cinema_data = $kino_parser->getData();

                        foreach ($cinema_data as $key => $value) {
                            //если нету сеансов, то пропускаем
                            if (!isset($value['cinema'])){
                                continue;
                            }

                            //получаем ссылку кинотеатра
                            $cinema_id = $this->getIdCinemaFromLink($value['link']);
                            $company_id = $cinema_id ? ParseKino::find()->where(['remote_cinema_id' => $cinema_id])->one() : null;

                            if (isset($company_id->local_cinema_id)) {
                                //цикл по сеансам
                                if (isset($value['cinema'])) {
                                    foreach ($value['cinema'] as $seanse) {
                                        $afisha =  $this->findAfisha($seanse['title']['ru']);

                                        if ($afisha == null) {
                                            //добавляем афишу в бд
                                            $afisha = $this->addAfish($seanse['link'], $seanse['title']['ru']);
                                            //если есть сеансы и добавилась афиша
                                            if (isset($seanse) && $afisha) {
                                                $count_afisha++;
                                                $count_shedule_kino += $this->addScheduleKino($seanse, $afisha, $city_in_db, $link_with_data_item, $company_id->local_cinema_id);
                                            }
                                        } else {
                                            foreach ($afisha as $item) {
                                                //переводим названия в латиницу
                                                $afisha_title = transliterator_transliterate("Any-Latin; Latin-ASCII", $item->title);
                                                $cinema_title = transliterator_transliterate("Any-Latin; Latin-ASCII", $seanse['title']['ru']);

                                                //убераем лишние символы для сравнения названий
                                                $afisha_title = preg_replace("/[^a-zA-Z0-9\s]/", "", $afisha_title);
                                                $cinema_title = preg_replace("/[^a-zA-Z0-9\s]/", "", $cinema_title);

                                                //если совпало название( с учетом знаков препинания или без)
                                                if (mb_strtolower($afisha_title) == mb_strtolower(str_replace(".", "", $cinema_title))
                                                    || mb_strtolower($afisha_title) == mb_strtolower($cinema_title)
                                                ) {
                                                    $count_shedule_kino += $this->addScheduleKino($seanse, $item, $city_in_db, $link_with_data_item, $company_id->local_cinema_id);
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                $this->addWarningToLog($value['link']);
                            }
                        }
                    }

                    $systemLog = new SystemLog(['description' => [
                        'Сообщение' => 'Парсер отработал по ' . $city_in_db->title . ': добавил ' . $count_shedule_kino . ' сеансов',
                    ]]);
                    $systemLog->status = SystemLog::STATUS_INFO;
                    $systemLog->save();

                    echo 'Добавлено ' . $count_shedule_kino . ' расписаний', PHP_EOL;
                }
            }
        }

        $systemLog = new SystemLog(['description' => [
            'Сообщение' => 'Парсер добавил всего ' . $count_afisha . ' фильмов',
        ]]);
        $systemLog->status = SystemLog::STATUS_INFO;
        $systemLog->save();

        echo 'Добавлено ' . $count_afisha . ' фильмов', PHP_EOL;
        Yii::$app->language = $current_language;
    }

    /**
     * @param $title string название кино
     * @return array|\yii\db\ActiveRecord[]
     */
    private function findAfisha($title){
        $afisha = Afisha::find()
            ->where(['ilike', 'title', $title])
            ->andWhere(['isFilm' => '1'])
            ->all();

        //если название фильма не нашлось в  бд
        if ($afisha == null) {
            $afisha = Afisha::find()
                ->where(['ilike', 'title', str_replace(".", "", $title)])
                ->andWhere(['isFilm' => '1'])
                ->all();
        }

        if ($afisha == null) {
            $afisha = Afisha::find()
                ->where(['ilike', 'title', str_replace('"', '\"', $title)])
                ->andWhere(['isFilm' => '1'])
                ->all();
        }

        if ($title == 'I.T.'){
            $afisha = Afisha::find()
                ->where(['like', 'title',$title])
                ->andWhere(['isFilm' => '1'])
                ->all();
        }

        return $afisha;
    }

    /**
     * Метод добавляет warning в лог, про отсутствие ассоциации
     *
     * @param $link string ссылка на кинотеатр, которого нету в бд
     */
    private function addWarningToLog($link){
        $date = new \DateTime('now');

        $systemLog = SystemLog::find()
            ->where(['like', 'description', $link])
            ->andWhere(['date("dateCreate")' => $date->format('Y-m-d')])
            ->one();

        if (!$systemLog) {
            $systemLog = new SystemLog(['description' => [
                'Сообщение' => 'Нету ассоциации в базе данных с кинотеатром',
                'Ссылка' => $link,
            ]]);
            $systemLog->status = SystemLog::STATUS_WARNING;
            $systemLog->save();
        }
    }
}
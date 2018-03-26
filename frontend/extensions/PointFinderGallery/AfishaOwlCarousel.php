<?php
namespace frontend\extensions\PointFinderGallery;

use common\models\ScheduleKino;
use yii;
use common\models\Afisha;
use yii\base\Widget;
use yii\helpers\Url;

class AfishaOwlCarousel extends Widget
{
    public $limit = 6;
    public $title;

    public function init()
    {
        $this->title = 'Афиша';
        parent::init();
    }

    public function run()
    {
        if (!Yii::$app->request->city) {
            return null;
        }
        $end = date('Y-m-d 00:00:00');
        $date = date('Y-m-d 23:59:59');
        $day = mb_strtolower(date('D'));

        /** @var Afisha[] $afishas */
        $afishas = Afisha::find()
            ->joinWith(['afishaWeekRepeat', 'category'])
            ->innerJoin('business',
                'business.id = ANY(afisha."idsCompany") AND business."idCity" = :city AND afisha."isFilm" = 0',
                ['city' => Yii::$app->request->city->id]
            )
            ->where(['and', ['<=', 'afisha."dateStart"', $date], ['>=', 'afisha."dateEnd"', $end]])
            ->orWhere(['afisha.repeat' => Afisha::REPEAT_DAY])
            ->orWhere(["afisha_week_repeat.{$day}" => true])
            ->limit($this->limit)
            ->orderBy(['afisha.repeat' => SORT_ASC, 'afisha.id' => SORT_DESC])
            ->all();

        /** @var ScheduleKino[] $schedules */
        $schedules = ScheduleKino::find()
            ->select('idAfisha')->distinct()
            ->with(['afisha', 'afisha.category'])
            ->where(['<=', 'dateStart', $date])
            ->andWhere(['>=', 'dateEnd', $end])
            ->andWhere(['idCity' => Yii::$app->request->city->id])
            ->limit($this->limit)
            ->orderBy(['idAfisha' => SORT_DESC])
            ->all();

        foreach ($schedules as $schedule) {
            if ($schedule->afisha) {
                $afishas[] = $schedule->afisha;
            }
        }

        /**
         * @param $string string
         * @param $limit integer
         * @return string
         */
        $crop = function ($string, $limit) {
            if (mb_strlen($string, 'utf-8') > $limit) {
                $string = mb_substr($string, 0, $limit, 'utf-8');
                $lastSpace = mb_strrpos($string, ' ', 'utf-8');
                $string = mb_substr($string, 0, $lastSpace, 'utf-8');
                $string .= '...';
            }
            return $string;
        };

        /**
         * @param $models Afisha[]
         * @return array
         */
        $convertAfishaToData = function ($models) use ($crop) {
            $data = [];
            foreach ($models as $model) {
                $item['title'] = $crop($model->title, 20);
                $item['url'] = Url::to(['/afisha/view', 'alias' => "{$model->id}-{$model->url}"]);

                $item['image'] = [];
                $item['image']['src'] = Yii::$app->files->getUrl($model, 'image');

                $item['image']['bottom'] = [];
                $item['image']['bottom']['left'] = Yii::t('afisha', 'Date');
                if (isset($model->dateStart)){
                    $item['image']['bottom']['right'] = date('d.m.Y', strtotime($model->dateStart));
                } else {
                    $item['image']['bottom']['right'] = date('d.m.Y');
                }

                $item['detail'] = [];
                $item['detail']['second'] = $model->companyNames($model->idsCompany);
                $item['detail']['third'] = $crop(strip_tags($model->description), 60);

                $data[] = $item;
            }

            return $data;
        };

        $data = $convertAfishaToData($afishas);

        return $data ? $this->render('home',['data' => $data]) : null;
    }
}

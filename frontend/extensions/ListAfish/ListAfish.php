<?php

namespace frontend\extensions\ListAfish;

use common\models\Afisha;
use common\models\ScheduleKino;
use DateTime;
use DateTimeZone;
use Yii;
use yii\base\Widget;

class ListAfish extends Widget
{
    public $title = '';

    public function run()
    {
        $datetime = new DateTime();
        $datetime->setTimezone(new DateTimeZone(Yii::$app->params['timezone']));
        $end = $datetime->format('Y-m-d 00:00:00');
        $date = $datetime->format('Y-m-d 23:59:59');
        $day = mb_strtolower(date('D', strtotime($end)));

        $models = Afisha::find()
            ->joinWith(['afishaWeekRepeat', 'category'])
            ->innerJoin(
                'business',
                'business.id = ANY(afisha."idsCompany") AND afisha."isFilm" = 0'
            )
            ->where(['and', ['<=', 'afisha."dateStart"', $date], ['>=', 'afisha."dateEnd"', $end]])
            ->orWhere(['afisha.repeat' => Afisha::REPEAT_DAY])
            ->orWhere(["afisha_week_repeat.{$day}" => true])
            ->limit(12)
            ->orderBy(['afisha.repeat' => SORT_ASC, 'afisha.id' => SORT_DESC])->all();

//        $modelsSchedule = ScheduleKino::find()->select(['idAfisha'])->distinct()
//            ->with(['afisha', 'afisha.category'])
//            ->where(['<=', 'dateStart', $date])->andWhere(['>=', 'dateEnd', $end])
//            ->limit(6)->orderBy(['idAfisha' => SORT_DESC])->all();

        if (!$models) {
            return false;
        }

        return $this->render('index', [
            'models' => $models,
            'title' => $this->title,
        ]);
    }
}
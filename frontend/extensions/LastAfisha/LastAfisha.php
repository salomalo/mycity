<?php

namespace frontend\extensions\LastAfisha;

use DateTime;
use DateTimeZone;
use yii;
use common\models\Afisha;
use common\models\ScheduleKino;
use yii\base\Widget;

/**
 * Description of LastNews
 *
 * @author dima
 */
class LastAfisha extends Widget
{
    public $title = '';
    public $city = '';
    public $idCity;
    
    public function run()
    {
        $end = date('Y-m-d 00:00:00');
        $date = date('Y-m-d 23:59:59');
        $day = mb_strtolower(date('D'));

        $models = Afisha::find()
            ->joinWith(['afishaWeekRepeat', 'category'])
            ->innerJoin('business',
                'business.id = ANY(afisha."idsCompany") AND business."idCity" = :city AND afisha."isFilm" = 0',
                ['city' => $this->idCity]
            )
            ->where(['and', ['<=', 'afisha."dateStart"', $date], ['>=', 'afisha."dateEnd"', $end]])
            ->orWhere(['afisha.repeat' => Afisha::REPEAT_DAY])
            ->orWhere(["afisha_week_repeat.{$day}" => true])
            ->limit(6)
            ->orderBy(['afisha.repeat' => SORT_ASC, 'afisha.id' => SORT_DESC])->all();

        $modelsSchedule = ScheduleKino::find()->select(['idAfisha'])->distinct()
            ->with(['afisha', 'afisha.category'])
            ->where(['<=', 'dateStart', $date])->andWhere(['>=', 'dateEnd', $end])
            ->andWhere(['idCity' => $this->idCity])
            ->limit(6)->orderBy(['idAfisha' => SORT_DESC])->all();

        if (!$models and !$modelsSchedule) {
            return false;
        }

        return $this->render('index', [
            'models' => $models,
            'modelsSchedule' => $modelsSchedule,
            'title' => $this->title,
            'city' => $this->city,
        ]);
    }
}

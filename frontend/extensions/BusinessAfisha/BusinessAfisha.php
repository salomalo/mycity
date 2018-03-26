<?php

namespace frontend\extensions\BusinessAfisha;

use common\models\Afisha;
use common\models\Business;
use common\models\ScheduleKino;
use Yii;
use yii\base\Widget;
use yii\helpers\VarDumper;

/**
 * @property integer|null $id
 * @property Afisha[] $models
 */
class BusinessAfisha extends Widget
{
    public $id = null;

    private $models;

    public function init()
    {
        parent::init();
        if ($this->id) {
            $type = Business::find()->select('type')->where(['id' => $this->id])->asArray()->one();
            //кинотеатр ? true : false;
            $type = ((int)$type['type'] === 1) ? true : false;

            $this->models = Afisha::find();
            if ($type) {
                //Фильмы
                $this->models = Afisha::find()
                    ->leftJoin('schedule_kino', '"afisha"."id" = "schedule_kino"."idAfisha"')
                    ->where(['"schedule_kino"."idCompany"' => $this->id])
                    ->andWhere(['>=', '"schedule_kino"."dateEnd"', date('Y-m-d').' 00:00:00'])
                    ->orderBy('"schedule_kino"."dateStart"')
                    ->all();
            } else {
                //Не фильмы
                $end = date('Y-m-d 00:00:00');
                $date = date('Y-m-d 23:59:59');
                $day = mb_strtolower(date('D', strtotime($end)));

                $this->models = Afisha::find()
                    ->joinWith(['afishaWeekRepeat'])
                    ->where(['&&', 'idsCompany', "{{$this->id}}"])
                    ->andWhere(['or',
                        ['and',
                            ['<=', 'afisha."dateStart"', $date],
                            ['>=', 'afisha."dateEnd"', $end],
                        ],
                        ['afisha.repeat' => Afisha::REPEAT_DAY],
                        ["afisha_week_repeat.{$day}" => true],
                    ])
                    ->orderBy('dateStart')
                    ->all();
            }
        }
    }

    public function run()
    {
        foreach ($this->models as $model) {
            echo $this->render(
                ($model->isFilm) ? '_afisha_short_film' : '_afisha_short',
                ['model' => $model]
            );
        }
    }
}

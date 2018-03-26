<?php

namespace console\controllers;

use common\models\Afisha;
use common\models\File;
use common\models\ScheduleKino;
use common\models\Wall;
use Yii;
use yii\console\Controller;

class AfishaController extends Controller
{
    public function actionInitRepeat()
    {
        $where = ['repeat' => null, 'isFilm' => 0];

        $i = Afisha::find()->where($where)->count();
        echo 'Всего:', PHP_EOL, $i, "\t";
        Afisha::updateAll(['repeat' => 0], $where);
        echo PHP_EOL, 'Готово!', PHP_EOL;
    }
    
    public function actionInitMultiSchedule()
    {
        $i = ScheduleKino::find()->count();
        echo 'Всего:', PHP_EOL, $i, "\t";
        foreach (ScheduleKino::find()->batch() as $models) {
            /** @var $models ScheduleKino[] */
            foreach ($models as $model) {
                $model->save();
                echo !(--$i % 50) ? PHP_EOL . $i . "\t" : '.';
            }
        }
        echo PHP_EOL, 'Готово!', PHP_EOL;
    }

    public function actionFilmToWall()
    {
        $query = Afisha::find()->where(['>=', 'dateUpdate', date('Y-m-d 00:00:01')])->andWhere(['isFilm' => 1]);

        echo $query->count(), PHP_EOL;
        /** @var Afisha[] $models */
        foreach ($query->batch() as $models) {
            foreach ($models as $model) {
                $wall = Wall::find()->where(['published' => null, 'pid' => $model->id, 'type' => [File::TYPE_AFISHA, File::TYPE_AFISHA_WITH_SCHEDULE]])->all();

                echo 'wall exist: '. count($wall);
                foreach ($wall as $item) {
                    $item->delete();
                }

                if (!empty($model->schedule_kino)) {
                    $wall = new Wall([
                        'type' => File::TYPE_AFISHA_WITH_SCHEDULE,
                        'pid' => $model->id,
                        'url' => $model->url,
                        'image' => ($model->image) ? Yii::$app->files->getUrl($model, 'image') : '',
                        'title' => $model->title,
                        'idCity' => $model->schedule_kino[0]->idCity,
                        'description' => ($model->description) ? strip_tags($model->description) : strip_tags($model->fullText)
                    ]);
                    echo $wall->save() ? '+' : '-';
                }
            }
        }
        echo PHP_EOL;
    }
}
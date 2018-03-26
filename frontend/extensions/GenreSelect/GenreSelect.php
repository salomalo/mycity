<?php

namespace frontend\extensions\GenreSelect;

use common\models\Afisha;
use common\models\AfishaCategory;
use common\models\City;
use common\models\KinoGenre;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * @property array $filterarr
 */
class GenreSelect extends \yii\base\Widget
{
    public $active;
    public $pid;
    public $title;
    public $filter = false;
    public $forWeek;
    public $date;
    public $template = 'index';
    public $archive;
    public $forDay;

    public function run()
    {
        $this->title = Yii::t('afisha', 'Kino genre');
        $models = KinoGenre::find()->all();
        if ($this->filter) {
            $filter = $this->filterarr;
            $count = count($models);
            /* @var $models KinoGenre[]*/
            for ($i = 0; $i < $count; $i++) {
                if (!ArrayHelper::isIn($models[$i]->id, $filter)) {
                    unset($models[$i]);
                }
            }
        }
        ArrayHelper::multisort($models, ['title'], [SORT_ASC]);

        $this->active = KinoGenre::find()->where(['url' => $this->active])->select('id')->one()['id'];
        $this->pid = AfishaCategory::find()->where(['id' => $this->pid])->select('url')->one()['url'];

        return $this->render($this->template, [
            'active' => $this->active,
            'models' =>$models,
        ]);
    }

    public function createUrl($param)
    {
        $action = ((Yii::$app->controller->action->id === 'view') ? 'index' : Yii::$app->controller->action->id);
        $options = ['afisha/' . $action,'pid' => $this->pid, 'genre' => $param];
        if($this->archive){
            $options['archive'] = $this->archive;
        } elseif(Yii::$app->request->get('time')) {
            $options['time'] = Yii::$app->request->get('time');
        }
        return Url::to($options);
    }

    public function getFilterArr()
    {
        $end = $this->date . ' 00:05:00';
        $date = $this->date . ' 23:55:00';

        $cities = array();
        if (Yii::$app->params['SUBDOMAINID'] == 0){
            $cities_db = City::find()->where(['main' => '2'])->all();
            foreach ($cities_db as $city_in_db) {
                $cities[] = $city_in_db->id;
            }

        } else {
            $cities[] = Yii::$app->params['SUBDOMAINID'];
        }

        $query = Afisha::find()
            ->distinct()
            ->select('genre')
            ->leftJoin(
                'schedule_kino',
                'afisha.id = schedule_kino."idAfisha" AND schedule_kino."idCity" = ANY(:city) AND afisha."isFilm" = 1',
                ['city' => $this->php_to_postgres_array($cities)]
            );

        if (!$this->archive) {
            $query->joinWith(['afishaWeekRepeat', 'afisha_category'])
                ->where(['schedule_kino."idCity"' => $cities, 'afisha."isFilm"' => 1])
                ->andWhere(['or',
                    ['and',
                        ['<=', 'schedule_kino."dateStart"', $date],
                        ['>=', 'schedule_kino."dateEnd"', $end],
                    ],
                ]);
        } else {
            $query->joinWith(['afisha_category'])
                ->where(['and',
                    ['schedule_kino."idCity"' => $cities],
                    ['<', 'schedule_kino."dateEnd"', $end],
                ]);
        }
        $query = $query->all();
        $filer = [];
        $genres = ArrayHelper::getColumn($query,'genre');
        foreach ($genres as $item) {
            $items = array_unique($item);
            foreach ($items as $value){
                $filer[] = $value;
            }
        }
        return array_unique($filer);
    }

    public function php_to_postgres_array($phpArray)
    {
        return '{' . join(',', $phpArray) . '}';
    }
}
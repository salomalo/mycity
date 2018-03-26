<?php

namespace frontend\extensions\Calendar;

use yii\base\Widget;
use yii\helpers\Url;
use yii\helpers\Json;
use common\models\ScheduleKino;
use frontend\extensions\Calendar\Assets;

/**
 * Description of Calendar
 *
 * @author dima
 */
class Calendar extends Widget {

    public $title = '';
    public $model;
    public $path;
    public $attribute = 'pid';
    public $idCity;
    public $idCategory;
    public $arr;
    public $idCompanyIsArray = false;
    public $isFilm = false;
    public $businesList = [];
    public $selectDate;
    public $sortTime = null;
    public $isSelectedDay;
    
    private $month;
    private $year;
    private $monthList = [
        'Январь',
        'Февраль',
        'Март',
        'Апрель',
        'Май',
        'Июнь',
        'Июль',
        'Август',
        'Сентябрь',
        'Октябрь',
        'Ноябрь',
        'Декабрь',
    ];


    public function run()
    {
        $view = $this->getView();
        Assets::register($view);
        if($this->selectDate){
            $arrDate = explode('-', $this->selectDate);
        }

        return $this->render('index', [
            'title' => $this->title,
            'month' => $arrDate[1],
            'year' => $arrDate[0],
            'day' => $this->isSelectedDay ? $arrDate[2] : 0,
            'sortTime' => $this->sortTime,
            'action' => Url::to([$this->path]),
        ]);
    }
      
    public function getPost($list_day, $isFilm)
    {
//        $time = mktime(0, 0, 0, $this->month, $list_day, $this->year);
//        $timeNext = mktime(0, 0, 0, $this->month, $list_day+1, $this->year);
//        $date = date('Y-m-d', $time);
//        $date2 = date('Y-m-d', $timeNext);
//        
//        $mod = new $this->model;
//        $model = $mod::find();
//        
//        if(!$isFilm){
//            $model = $model->andWhere('"dateStart" <= ' . "'".$date2."'");
//            $model = $model->andWhere('"dateEnd" >= ' . "'".$date."'");
//
//            if($this->idCity){
//                if(!$this->idCompanyIsArray){   // акции
//                    $model = $model->andWhere(['idCompany' => $this->businesList]);
//                }else{
//                    //$cl = '{'.$this->idsCompany.'}';
//                    //$model = $model->andWhere(['idCompany' => $businesList]);
//                    $list = '{'.join(',',$this->businesList).'}';
//                    $model = $model->andWhere("\"idsCompany\" && '".$list."'");
//                }
//            }
//            
//            $model = $model->one();
//            if(!$model){
//                return false;
//            }
//        }
//        else{
//            $schedule = ScheduleKino::find()->select(['idAfisha'])
//                                            ->where('"dateStart" <= ' . "'".$date."'")
//                                            ->andWhere('"dateEnd" >= ' . "'".$date."'");
//            
//            if($this->idCity){
//                $schedule = $schedule->andWhere(['idCompany' => $this->businesList]);
//            }
//            $schedule = $schedule->asArray()->one();
//            
//            if(!$schedule){
//                return false;
//            }
//        }
//        
//        return $time;
    }
    
    public function createUrl($param, $time)
    {
        return Url::to([$this->path, $this->attribute => $param, 'time' => $time]);
    }
}

<?php

namespace api\actions;

use Yii;
use yii\rest\Action;
use yii\base\Model;

/**
 * Description of CalendarAction
 *
 * @author dima
 */
class CalendarAction extends Action
{
    /**
     * @var string the scenario to be assigned to the model before it is validated and updated.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

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

    /**
     * Updates an existing model.
     * @param string $id the primary key of the model.
     * @return \yii\db\ActiveRecordInterface the model being updated
     * @throws \Exception if there is any error when updating the model
     */
    public function run($month, $do, $year, $arr)
    {  
        //return $month;
        switch ($do){
            case 'next' : 
                $month += 1; 
                if($month > 12){
                    $month = 1;
                    $year += 1;
                }
                break;
            case 'prev' : 
                $month -= 1; 
                if($month < 1){
                    $month = 12;
                    $year -= 1;
                }
                break;
        }
        
        $arg = explode(',', $arr);
            
//        return \yii\helpers\BaseVarDumper::dump($arg, 10, true);
//                die();
       
        return $this->controller->renderPartial('index', [
                'month' => $month,
                'monthTitle' => $this->monthList[$month-1],
                'year' => $year,
                //'isFilm' => $arr[0],
                'path' => $arg[4],
                'attribute' => $arg[5],
                'idCategory' => ($arg[3] > 0)? $arg[3] : null,
                'arg' => $arg,
            ]);
    }
    
}

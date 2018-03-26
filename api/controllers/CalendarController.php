<?php

namespace api\controllers;

use yii\rest\ActiveController;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\helpers\Url;
use common\models\AfishaCategory;
use common\models\ScheduleKino;

/**
 * Description of CalendarController
 *
 * @author dima
 */
class CalendarController extends ActiveController
{
    public $modelClass = 'common\models\Rating';
    public $businesList = [];
    
    public function actions()
    {
        return [
            'calendar' => [
                'class' => 'api\actions\CalendarAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->updateScenario,
            ],
        ];
    }
    
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/html' => Response::FORMAT_HTML,
                    //'application/json' => Response::FORMAT_JSON,
                    //'application/xml' => Response::FORMAT_XML,
                ],
            ],
        ];
    }
    
    public function getPost($month, $year, $list_day, $arg)
    {
        $time = mktime(0, 0, 0, $month, $list_day, $year);
        $timeNext = mktime(0, 0, 0, $month, $list_day+1, $year);
        $date = date('Y-m-d', $time);
        $date2 = date('Y-m-d', $timeNext);
        
        $mod = new $arg[1]; // model
        $model = $mod::find();
        
        if(empty($this->businesList)){
            if($arg[7] != 'false'){
                $this->businesList = array_slice($arg, 7);
            }
        }
        
        if($arg[3]){ // idCategory
            $arr = [$arg[3]];
            $cat = AfishaCategory::find()->where(['id' => $arg[3]])->one();
            if($cat){
                foreach($cat->children as $item){
                    $arr[] = $item['id'];
                }
            }
            $model = $model->andWhere(['idCategory' => $arr]);
        }
        
        if($arg[0] == 'false'){   // isFilm
            $model = $model->andWhere('"dateStart" <= ' . "'".$date2."'");
            $model = $model->andWhere('"dateEnd" >= ' . "'".$date."'");

            if($arg[6]){    // idCity
                if($arg[2] == 'false'){   // idCompanyIsArray
                    $model = $model->andWhere(['idCompany' => $this->businesList]);
                }else{
                    //$cl = '{'.$this->idsCompany.'}';
                    //$model = $model->andWhere(['idCompany' => $businesList]);
                    $list = '{'.join(',',$this->businesList).'}';
                    $model = $model->andWhere("\"idsCompany\" && '".$list."'");
                }
            }
            
            $model = $model->one();
            if(!$model){
                return false;
            }
        }
        else{
            $schedule = ScheduleKino::find()->select(['idAfisha'])
                                            ->andWhere('"dateStart" <= ' . "'".$date."'")
                                            ->andWhere('"dateEnd" >= ' . "'".$date."'");
            
            if($arg[6]){
                $schedule = $schedule->andWhere(['idCompany' => $this->businesList]);
            }
            $schedule = $schedule->asArray()->one();
            
            if(!$schedule){
                return false;
            }
        }
        
        return $time;
    }
    
    public function createUrl($path, $attribute, $param, $time)
    {
        return Url::to([$path, $attribute => $param, 'time' => $time]);
    }
    
}

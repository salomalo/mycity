<?php

namespace frontend\extensions\RelatedFilm;

use Yii;
use common\models\Business;
use common\models\ScheduleKino;
use common\models\Afisha;
/**
 * Description of RelatedFilm
 *
 * @author dima
 */
class RelatedFilm extends \yii\base\Widget
{
    public $model;
    public $idModel;
    public $title = '';
    public $limit = 4;
    public $time;
    
    public function run()
    {
        if(empty($this->idModel) || empty($this->model)){
            return false;
        }
        
//        $business = Business::find()->select('id');
//        if(defined('SUBDOMAINTITLE')){
//            $business = $business->where(['idCity' => SUBDOMAINID]);
//        }
//        $business = $business->asArray()->all();
//        
//        $arr = [];
//        foreach ($business as $item){
//            $arr[] = $item['id'];
//        }
        
        $now = ($this->time)? date('Y-m-d', strtotime($this->time)) : date('Y-m-d');
        $start = new \DateTime($now);
        $date2 = $start->modify('+1 day');
        
        $models = ScheduleKino::find()->select('idAfisha');

        if(Yii::$app->params['SUBDOMAINID']){
            $models = $models->where(['idCity' => Yii::$app->params['SUBDOMAINID']]);
        }
        
        $models = $models->andWhere('"idAfisha" <> :id', [':id' => $this->idModel]);
        $models = $models->andWhere('"dateStart" < ' . "'".$date2->format('Y-m-d')."'");
        $models = $models->andWhere('"dateEnd" >= ' . "'".$now."'");
        $models = $models->limit($this->limit)->asArray()->distinct()->all();
        
        $arr = [];
        foreach ($models as $item){
            $arr[] = Afisha::findOne($item['idAfisha']);
        }
                
        return $this->render('index', [
            'models' => $arr,
            'title' => $this->title,
        ]);
    }
}

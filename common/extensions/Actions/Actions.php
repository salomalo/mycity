<?php

namespace common\extensions\Actions;

use Yii;
use yii\base\Widget;
use common\models\search\Action;
use yii\data\ActiveDataProvider;

class Actions extends Widget {
    
     public $id;
     public $title;
     
     public function init() {
        parent::init();
    }
    
    public function run() {
        
        $now = new \DateTime(); 
        $now->setTimezone(new \DateTimeZone(Yii::$app->params['timezone']));
        
        $now->setTimestamp(strtotime(date('Y-m-d')));
        
        $query = Action::find()
                ->where(['idCompany' => $this->id])
                ->andWhere('"dateEnd" >= ' . "'" . $now->format('Y-m-d 00:00:00') . "'")
                ->andWhere('"dateStart" <= ' . "'" . $now->modify('+1 day')->format('Y-m-d 00:00:00') . "'")
                ->orderBy('dateStart ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        
        $view = $this->getView();
        
        Assets::register($view);
        
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'title' => $this->title
        ]);
    }
}
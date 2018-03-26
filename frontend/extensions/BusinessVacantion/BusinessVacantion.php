<?php

namespace frontend\extensions\BusinessVacantion;

use common\models\WorkVacantion;
/**
 * Description of BusinessVacantion
 *
 * @author dima
 */
class BusinessVacantion extends \yii\base\Widget 
{
    public $model;
    public $limit = 5;
    
    public function run() {
        
        if(!$this->model){
            return false;
        }
        
        $models = WorkVacantion::find()->where(['idCompany' => $this->model->id])->limit($this->limit)->all();
        
        return $this->render('index', [
            'model' => $this->model,
            'models' => $models
        ]);
    }
}

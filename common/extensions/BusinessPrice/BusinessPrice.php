<?php

namespace common\extensions\BusinessPrice;

use Yii;
use yii\base\Widget;
use common\models\File;

/**
 * Description of BusinessPrice
 *
 * @author dima
 */
class BusinessPrice extends Widget
{
    public $model;
    public $isFrontend = true;
    
    public function init() {
        parent::init();
    }

    public function run() {
        
        if(!$this->model || $this->model->id == null){
            return false;
        }
        
        $models= File::find()->where(['type' => File::TYPE_BUSINESS_PRICE, 'pid' => $this->model->id])->all();
        
        return $this->render('index', [
            'model' => $this->model,
            'models' => $models,
            'isFrontend' => $this->isFrontend
        ]);
    }
}

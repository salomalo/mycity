<?php
namespace frontend\extensions\TopVacantion;

use common\models\WorkVacantion;
/**
 * Description of TopVacantion
 *
 * @author dima
 */
class TopVacantion extends \yii\base\Widget
{
    public $limit = 3;
    public $title = '';
    public $city = null;
    
    public function run()
    {
        $models = WorkVacantion::find();
        
        if($this->city){
            $models = $models->where(['idCity' => $this->city]);
        }
        
        $models = $models->joinWith('countView')->orderBy('count DESC')->limit($this->limit)->all();
        
        if(!$models){
            return false;
        }
        
        return $this->render('index', [
            'models' => $models,
            'title' => $this->title,
            'city' => $this->city
        ]);
    }
}

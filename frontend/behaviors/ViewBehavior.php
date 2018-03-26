<?php

namespace frontend\behaviors;

use yii\base\Behavior;
use common\models\CountViews;
/**
 * Description of ViewBehavior
 *
 * @author dima
 */
class ViewBehavior extends Behavior
{
    public $type;
    public $id;
    public $countMonth = false;
    
    public function init()
    {
        $model = CountViews::find()->where(['type' => $this->type]);
        if (is_int($this->id)) {
            $model = $model->andWhere(['pid' => $this->id]);
        } else {
            $model = $model->andWhere(['pidMongo' => $this->id]);
        }
        
        $model = $model->one();
        
        $ip = $_SERVER['REMOTE_ADDR'];
        if (!$model) {
            $model = new CountViews();
            $model->type = $this->type;
            
            if(is_int($this->id)){
                $model->pid = $this->id;
            }
            else{
                $model->pid = null;
                $model->pidMongo = $this->id.'';
            }
            $model->count = 1;
            if($this->countMonth){
                $model->countMonth = 1;
            }
            $model->lastIp = $ip;
            
            $model->save();
        } else {
            if($model->lastIp != $ip){
                $model->lastIp = $ip;
                if ($this->countMonth) {
                    $model->count += 1;
                    $model->countMonth += 1;
                    $model->save(false, ['count', 'countMonth', 'lastIp']);
                } else {
                    $model->count += 1;
                    $model->save(false, ['count', 'lastIp']);
                }
                
            }
        }
    }
}

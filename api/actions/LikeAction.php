<?php

namespace api\actions;

use Yii;
use yii\rest\Action;
use yii\base\Model;
use common\models\Comment;

/**
 * Description of LikeAction
 *
 * @author dima
 */
class LikeAction extends Action
{
    /**
     * @var string the scenario to be assigned to the model before it is validated and updated.
     */
    public $scenario = Model::SCENARIO_DEFAULT;


    /**
     * Updates an existing model.
     * @param string $id the primary key of the model.
     * @return \yii\db\ActiveRecordInterface the model being updated
     * @throws \Exception if there is any error when updating the model
     */
    public function run($id, $do, $ip)
    {  
//        $ip = $_SERVER['REMOTE_ADDR'];
        $model = Comment::findOne($id);
        
        if ($model->lastIpLike != $ip) {

            switch ($do) {
                case 'like':
                    $model->like += 1;
                    $model->lastIpLike = $ip;
                    $model->save(false, ['like', 'lastIpLike']);
                    break;
                case 'unlike':
                    $model->unlike += 1;
                    $model->lastIpLike = $ip;
                    $model->save(false, ['unlike', 'lastIpLike']);
                    break;
            }
        }
        
        return $model->like-$model->unlike;
    }
}
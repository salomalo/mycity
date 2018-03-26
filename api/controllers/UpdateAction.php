<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace api\controllers;

use Yii;
use yii\rest\Action;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\ServerErrorHttpException;
use common\models\RatingHistory;
use common\models\Rating;

/**
 * UpdateAction implements the API endpoint for updating a model.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class UpdateAction extends Action
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
    public function run($id, $type, $do, $idUser)
    {
        /* @var $model ActiveRecord */
        //$model = $this->findModel($id);
        $model = Rating::find()->where(['type' => $type, 'pid' => $id])->one();
        if(!$model){
            $model = new Rating();
            $model->type = $type;
            $model->pid = $id;
            $model->rating = 0;
        }
        $modelHistory = new RatingHistory();

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        $model->scenario = $this->scenario;
        //$model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if($do == 'up'){
            $model->rating += 1;
            $modelHistory->ratio = '+1';
        }else{
            $model->rating -= 1;
            $modelHistory->ratio = '-1';
        }
        
        if ($model->save() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }else{
            $modelHistory->idUser = $idUser;
            $modelHistory->idRating = $model->id;
            $modelHistory->save();
        }
        
        unset($modelHistory);
        return $model->rating;
    }
}

<?php

namespace api\controllers;

use yii\rest\ActiveController;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\helpers\Json;

/**
 * Description of RatingController
 *
 * @author dima
 */
class RatingController extends ActiveController
{
    public $modelClass = 'common\models\Rating';
    
    public function actions()
    {
        return [
            'update' => [
                'class' => 'api\controllers\UpdateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->updateScenario,
            ],
        ];
    }

    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }
    
}
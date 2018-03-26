<?php

namespace api\controllers;

use yii\rest\ActiveController;
use yii\filters\ContentNegotiator;
use yii\web\Response;
/**
 * Description of CommentController
 *
 * @author dima
 */
class CommentController extends ActiveController
{
    public $modelClass = 'common\models\Comment';
    
    public function actions()
    {
        return [
            'like' => [
                'class' => 'api\actions\LikeAction',
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

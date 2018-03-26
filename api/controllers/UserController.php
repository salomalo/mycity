<?php

namespace api\controllers;

use yii\rest\ActiveController;

/**
 * Description of UserController
 *
 * @author dima
 */
class UserController extends ActiveController
{
    public $modelClass = 'common\models\Account';
    
//    public $serializer = [
//        'class' => 'yii\rest\Serializer',
//        'collectionEnvelope' => 'items',
//    ];
}

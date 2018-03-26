<?php

namespace backend\controllers;

use dektrium\user\controllers\RegistrationController as BaseRegistrationController;
use Yii;

class RegistrationController extends BaseRegistrationController
{
    public $layout = 'login';
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access']['rules'] =
            [
                ['allow' => false, 'roles' => ['?', '@']],
            ];
        return $behaviors;
    }
}
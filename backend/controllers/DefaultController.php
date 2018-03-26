<?php

namespace backend\controllers;

use yii\base\Controller;
use yii\filters\AccessControl;

class DefaultController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['login'],
                    'allow' => true,
                    'roles' => ['?']
                ],
                [
                    'allow' => false,
                    'roles' => ['?'],
                ],
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
                [
                    'actions' => ['login'],
                    'allow' => false,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
    }
}
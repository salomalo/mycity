<?php
namespace office\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;

class DefaultController extends Controller
{
    public function behaviors(){
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['login-from-frontend','login'],
                    'allow' => true,
                    'roles' => ['?'],
                ],
                [
                    'actions' => [],
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

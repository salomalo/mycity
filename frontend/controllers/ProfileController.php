<?php
namespace frontend\controllers;

use common\models\User;
use HttpException;
use yii;
use dektrium\user\controllers\ProfileController as BaseController;
use yii\filters\AccessControl;

class ProfileController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('show');
    }
}

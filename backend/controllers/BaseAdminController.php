<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\Tag;

/**
 * Description of BaseAdminController
 *
 * @author dima
 */
class BaseAdminController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'roles' => ['@']],
                    ['allow' => false, 'roles' => ['?']],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    
    protected function saveTegs($tegs)
    {
        $arr = explode(', ', $tegs);
        foreach ($arr as $item) {
            $model = Tag::find()->where(['like', 'title', $item])->one();
            if (!$model) {
                $model = new Tag();
            }
            $model->title = $item;
            $model->save();
        }
    }
}

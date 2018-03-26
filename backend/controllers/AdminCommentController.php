<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 23.01.2017
 * Time: 14:05
 */

namespace backend\controllers;


use backend\models\AdminComment;
use Yii;

class AdminCommentController extends BaseAdminController
{
    /**
     * Creates a new AdminComment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminComment();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()){

            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}
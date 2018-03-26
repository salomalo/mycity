<?php

namespace office\controllers;

use common\models\search\Orders as OrderSearch;
use common\models\search\OrdersAds as OrderSearchAds;
use Yii;

/**
 * Description of OrderController
 *
 * @author dima
 */
class MyOrderController extends DefaultController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return $behaviors;
    }
    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),true);
        
        $dataProvider->query->where(['idUser' => Yii::$app->user->id]);
        $dataProvider->query->orderBy('id DESC');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionView($id)
    {
        $searchModel = new OrderSearchAds();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),true);
        
        $dataProvider->query->where(['pid' => $id]);
        
        return $this->render('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}

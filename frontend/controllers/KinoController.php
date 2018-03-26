<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use common\models\search\Afisha as AfishaSearch;
use yii\data\ActiveDataProvider;
use yii\web\HttpException;

/**
 * Description of KinoController
 *
 * @author dima
 */
class KinoController extends Controller {
    public $layout = 'afisha';
    public $breadcrumbs = [];
    public $isFilm;
    public $listCityBusiness = [];
    public $id_category = null;
    
    public function init() {
        parent::init();
        
        $this->isFilm = false;
        $this->breadcrumbs = [
            ['label' => Yii::t('afisha', 'Poster')],
        ];
    }
    
    public function actionIndex($id = null)
    {
        if (empty(Yii::$app->request->city))
            throw new HttpException(404);

        if (Yii::$app->request->get('page') === '1')
            return $this->redirect(Url::to(['/kino']),301);

        $query = AfishaSearch::find();
        
        $query->andWhere(['isFilm' => 1]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy('id DESC'),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('/afisha/index', [
                    'dataProvider' => $dataProvider,
                    'date' => null,
                    //'searchModel' => $searchModel,
        ]);
    }
}

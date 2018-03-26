<?php

namespace backend\controllers;

use Yii;
use backend\models\Admin;
use common\models\search\Log;
use common\models\Log as LogModel;
use backend\controllers\BaseAdminController;
use yii\data\ArrayDataProvider;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;

/**
 * Description of AdminLogController
 *
 * @author dima
 */
class AdminLogController extends BaseAdminController
{
    public $list = [];
    public $listUser = [];
    
    public function actionIndex($allLogs = null)
    {
        $searchModel = new Log();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $model = Admin::find();
        
        if (Yii::$app->user->getIdentity()->level != Admin::LEVEL_SUPER_ADMIN){
            $model->where(['id' => Yii::$app->user->identity->id]);
            $dataProvider->query->where(['username' => Yii::$app->user->identity->username]);
        }

        if (!$allLogs) {
            $dataProvider->query->andWhere(['is not', 'user_id', null]);
        }

        //убираем логи регистрации и авторизации из запроса
        $dataProvider->query
            ->andWhere(['<>', 'description', LogModel::$types[LogModel::TYPE_LOGIN]])
            ->andWhere(['<>', 'description', LogModel::$types[LogModel::TYPE_REGISTER]]);
      
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model' => $model->all(),
            'allLogs' => $allLogs,
        ]);
    }

    public function actionAuth($allLogs = null)
    {
        $searchModel = new Log();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = Admin::find();

        if (Yii::$app->user->getIdentity()->level != Admin::LEVEL_SUPER_ADMIN){
            $model->where(['id' => Yii::$app->user->identity->id]);
            $dataProvider->query->where(['username' => Yii::$app->user->identity->username]);
        }

        $dataProvider->query
            ->andWhere([
                'or',
                ['description' => LogModel::$types[LogModel::TYPE_REGISTER]],
                ['description' => LogModel::$types[LogModel::TYPE_LOGIN]],
            ]);

        if (!$allLogs) {
            $dataProvider->query->andWhere(['is not', 'user_id', null]);
        }

        return $this->render('auth', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model' => $model->all(),
            'allLogs' => $allLogs,
        ]);
    }
    
    public function actionTrunc($trunc = 'day')
    {
        $model = Admin::find();
        
        if (Yii::$app->user->getIdentity()->level != Admin::LEVEL_SUPER_ADMIN){
            $model->where(['id' => Yii::$app->user->identity->id]);
        }
              
       $this->getLists($model, $trunc);
         
        $dataProvider = new ArrayDataProvider([
            'allModels' => $this->list,
//            'pagination' => [
//                'pageSize' => 20,
//            ],
        ]);
       
        return $this->render('trunc', [
            'dataProvider' => $dataProvider,
            'model' => $model->all(),
            'trunc' => $trunc,
        ]);
    }
    
    private function getLists($model, $trunc){
        foreach ($model->all() as $user){
            $this->listUser[] = $user->username;
            
            if($trunc == 'day'){
                $sql = Log::find()
                    ->select(['date_trunc' => "date_trunc('day', \"dateCreate\")", 'count(*)'])
                    ->where(['username' => $user->username])
                    ->orWhere([
                        'and',
                        ['admin_id' => $user->id],
                        ['<>', 'type', Log::TYPE_LOGIN]
                    ])
                    ->groupBy('date_trunc')->orderBy(['date_trunc' => SORT_DESC])->asArray()->all();
            } elseif($trunc == 'week') {
                $sql = Log::find()
                    ->select(['date_trunc' => "date_trunc('week', \"dateCreate\") + '6 days'", 'count(*)'])   // 1 week 
                    ->where(['username' => $user->username])
                    ->orWhere([
                        'and',
                        ['admin_id' => $user->id],
                        ['<>', 'type', Log::TYPE_LOGIN]
                    ])
                    ->groupBy('date_trunc')->orderBy(['date_trunc' => SORT_DESC])->asArray()->all();
            } elseif($trunc == 'month') {
                $sql = Log::find()
                    ->select(['date_trunc' => "date_trunc('month', \"dateCreate\") + '1 month'", 'count(*)'])
                    ->where(['username' => $user->username])
                    ->orWhere([
                        'and',
                        ['admin_id' => $user->id],
                        ['<>', 'type', Log::TYPE_LOGIN]
                    ])
                    ->groupBy('date_trunc')->orderBy(['date_trunc' => SORT_DESC])->asArray()->all();
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');

            }
          
            foreach ($sql as $item){
                $date = explode(' ', $item['date_trunc']);
                $this->list[$date[0]][$user->username] =  $item['count'];
            }
        }
        krsort($this->list);
    }

    public function actionCharts($duration = 6, $period = 'M')
    {
        $duration = (int)$duration;
        $intervals = ['D', 'M', 'W', 'Y', 'HY'];
        if (!in_array($period, $intervals)) {
            throw new \InvalidArgumentException('Incorrect input period! ' . (string)$period);
        } elseif (empty($duration)) {
            throw new \InvalidArgumentException('Incorrect input duration! ' . (string)$duration);
        }

        $int = 1;
        $betweenFunc = Log::getBetweenFunc($period);
        if ($period === 'HY') {
            $period = 'M';
            $int = 6;
        }
        $periodFunc = Log::getIntervalFunc($period);
        $andWhere = [];
//        if (($cookie = Yii::$app->request->cookies) and $cookie->has('SUBDOMAINID')) {
//            $andWhere = ['city_id' => $cookie->get('SUBDOMAINID')];
//        }

        $charts[] = Log::getLoginAndRegChart($duration, $periodFunc, $betweenFunc, $int, $andWhere);
        $charts[] = Log::getAddContentChart($duration, $periodFunc, $betweenFunc, $int, $andWhere);
        $charts[] = Log::getNewCommentChart($duration, $periodFunc, $betweenFunc, $int, $andWhere);
        $charts[] = Log::getTotalActivityChart($duration, $periodFunc, $betweenFunc, $int, $andWhere);

        return $this->render('charts', ['charts' => $charts]);
    }

    public function actionChartsForPeriod($period = 'M', $start = null, $end = null)
    {
        $intervals = ['D', 'M', 'W', 'Y', 'HY'];
        if (!in_array($period, $intervals)) {
            throw new \InvalidArgumentException('Incorrect input period! ' . (string)$period);
        }
        $time = empty($end) ? time() : strtotime($end);
        $end = date('Y-m-d', $time);
        $time = empty($start) ? strtotime(date('Y-01-01')) : strtotime($end);
        $start = date('Y-m-d', $time);

        $int = 1;
        $betweenFunc = Log::getBetweenFunc($period);
        if ($period === 'HY') {
            $period = 'M';
            $int = 6;
        }
        $periodFunc = Log::getIntervalFunc($period);
        $andWhere = [];
//        if (($cookie = Yii::$app->request->cookies) and $cookie->has('SUBDOMAINID')) {
//            $andWhere = ['city_id' => $cookie->get('SUBDOMAINID')];
//        }

        $charts[] = Log::getLoginAndRegChartForPeriod($start, $end, $periodFunc, $betweenFunc, $int, $andWhere);
        $charts[] = Log::getAddContentChartForPeriod($start, $end, $periodFunc, $betweenFunc, $int, $andWhere);
        $charts[] = Log::getNewCommentChartForPeriod($start, $end, $periodFunc, $betweenFunc, $int, $andWhere);
        $charts[] = Log::getTotalActivityChartForPeriod($start, $end, $periodFunc, $betweenFunc, $int, $andWhere);

        return $this->render('charts_for_period', ['charts' => $charts]);
    }
}

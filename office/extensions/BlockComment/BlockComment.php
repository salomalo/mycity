<?php

/**
 * Created by PhpStorm.
 * User: roma
 * Date: 16.11.2016
 * Time: 12:46
 */

namespace office\extensions\BlockComment;

use office\models\search\Ads as AdsSearch;
use office\models\search\WorkVacantion as WorkVacantionSearch;
use office\models\search\WorkResume as WorkResumeSearch;
use common\models\Comment;
use common\models\File;
use Yii;

class BlockComment extends \yii\base\Widget
{
    public function run()
    {
        if (Yii::$app->user->identity->id) {
            //ищем все
            $searchModel = new AdsSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $adsIds = array();
            foreach ($dataProvider->models as $model){
                $adsIds[] = $model->_id;
            }

            $searchModel = new WorkVacantionSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $vacontionIds = array();
            foreach ($dataProvider->models as $model){
                $vacontionIds[] = $model->id;
            }

            $searchModel = new WorkResumeSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $resumeIds = array();
            foreach ($dataProvider->models as $model){
                $resumeIds[] = $model->id;
            }

            $countComment = Comment::find()
                ->leftJoin('business', 'business."id" = comment."pid"')
                ->leftJoin('work_vacantion', 'work_vacantion."id" = comment."pid"')
                ->leftJoin('work_resume', 'work_resume."id" = comment."pid"')
                ->where(['business."idUser"' => Yii::$app->user->identity->id, 'comment."type"' => File::TYPE_BUSINESS]);

            if (count($adsIds) > 0) {
                $countComment->orWhere('comment."pidMongo" = ANY(:adsIds)',
                    ['adsIds' => $this->php_to_postgres_array($adsIds)]);
            }

            if (count($vacontionIds) > 0) {
                $countComment->orWhere('comment."pid" = ANY(:vacontionIds)  AND comment."type" =  :type_vacantion',
                    [
                        'vacontionIds' => $this->php_to_postgres_array($vacontionIds),
                        'type_vacantion' => File::TYPE_WORK_VACANTION
                    ]);
            }

            if (count($resumeIds) > 0) {
                $countComment->orWhere('comment."pid" = ANY(:resumeIds)  AND comment."type" =  :type_resume',
                    [
                        'resumeIds' => $this->php_to_postgres_array($resumeIds),
                        'type_resume' => File::TYPE_RESUME
                    ]);
            }

            $countComment = $countComment->count();
        } else {
            $countComment = 0;
        }

        return $this->render('index', [
            'countComment' =>$countComment,
        ]);
    }

    public function php_to_postgres_array($phpArray)
    {
        return '{' . join(',', $phpArray) . '}';
    }
}
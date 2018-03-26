<?php

namespace backend\controllers;

use common\models\City;
use common\models\search\Afisha as AfishaSearchCommon;
use yii;
use common\models\Afisha;
use backend\models\search\Afisha as AfishaSearch;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use common\models\Log;
use common\models\File;
use common\models\Wall;

/**
 * AfishaController implements the CRUD actions for Afisha model.
 */
class AfishaController extends BaseAdminController
{
    public function actions()
    {
        return [
            'deleteGallery' => [
                'class' => 'common\extensions\fileUploadWidget\galleryActions\DeleteGallery',
                'view' => 'update',
            ],
            'addGallery' => [
                'class' => 'common\extensions\fileUploadWidget\galleryActions\AddGallery',
                'view' => 'update',
            ],
            'uploadGallery' => [
                'class' => 'common\extensions\fileUploadWidget\galleryActions\UploadGallery',
                'view' => 'update',
            ],
        ];
    }

    public function behaviors()
    {
        return \yii\helpers\ArrayHelper::merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get'],
                ],
            ],
        ]);
    }

    /**
     * Lists all Afisha models.
     * @param bool $isFilm
     * @return mixed
     */
    public function actionIndex($isFilm = false)
    {
        $searchModel = $isFilm ? new AfishaSearchCommon() : new AfishaSearch();
        $req = Yii::$app->request;
        $dataProvider = $searchModel->search($req->queryParams);

        $dataProvider->query->andWhere(['isFilm' => ($isFilm ? 1 : 0)]);

        return $this->render(($isFilm ? 'index_film' : 'index'), [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Afisha model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render(($model->isFilm ? 'view_film' : 'view'), ['model' => $model]);
    }

    /**
     * Creates a new Afisha model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param bool $isFilm
     * @return mixed
     */
    public function actionCreate($isFilm = false)
    {
        $model = new Afisha();
        $model->scenario = ($isFilm)? 'isFilm' : 'noFilm';
        if ($model->load(Yii::$app->request->post())) {
            if (isset($model->idsCompany) && $model->idsCompany != '0' && $model->idsCompany) {
                $companies = array();
                foreach ($model->idsCompany as $key => $company) {
                    if (is_numeric($company)) {
                        $companies[] = $company;
                    }
                }
                $model->idsCompany = $companies;
            }

            $model->times = Yii::$app->request->post('times');
            $model->always = !empty($_POST['Afisha']['always']) ? true : false;
            if ($isFilm) {
                $model->checkImage('image');
            }

            if (empty($model->errors) and $model->save()) {
                $this->saveTegs($model->tags);
                Log::addAdminLog("afisha[create]  ID: {$model->id}", $model->id, Log::TYPE_AFISHA);

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'isFilm' => $isFilm,
            'checkCompany' => [],
        ]);
    }

    /**
     * Updates an existing Afisha model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $actions
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id, $actions = '')
    {
        $model = $this->findModel($id);
        if ((int)$model->repeat === Afisha::REPEAT_DAY) {
            $repeat = $model->dateEnd;
            $repeat = explode(' ', $repeat, 2);
            $repeat = date('Y-m-d') . ' ' . (isset($repeat[1]) ? $repeat[1] : date('H:i:m'));
        }
        $checkCompany = [];
        $model->tags = explode(', ', $model->tags);
        if ($model->isFilm) {
            $model->scenario = 'isFilm';
        } else {
            $model->scenario = 'noFilm';
            foreach ($model->companys as $item) {
                if ($item != null) {
                    $checkCompany[] = [
                        'id' => $item->id,
                        'class' => '',
                        'title' => $item->title,
                        'cityTitle' => $item->city->title,
                    ];
                } else {
                    $checkCompany[] = [
                        'id' => '',
                        'class' => '',
                        'title' => '',
                        'cityTitle' => 'Не найдено',
                    ];
                }
            }
        }
        if ($actions === 'deleteImg') {
            Yii::$app->files->deleteFile($model, 'image');
            $model->image = '';
            $model->update(false, ['image']);
            Log::addAdminLog("afisha[update]  ID: {$model->id}", $model->id, Log::TYPE_AFISHA);
            $model = $this->findModel($id);
            $this->redirect(['update', 'id' => $id]);
        } elseif ($model->load(Yii::$app->request->post())) {
            if (isset($model->idsCompany) && $model->idsCompany != '0') {
                $companies = array();
                foreach ($model->idsCompany as $key => $company) {
                    if (is_numeric($company)) {
                        $companies[] = $company;
                    }
                }
                $model->idsCompany = $companies;
            }

            $model->times = Yii::$app->request->post('times');
            $model->always = !empty($_POST['Afisha']['always']) ? true : false;
            if (!empty($repeat) and ((int)$model->repeat === Afisha::REPEAT_NONE)) {
                $model->dateEnd = $repeat;
            }
            $saveFilmAttr = [
                'title', 'idsCompany', 'idCategory', 'description', 'image', 'rating', 'isFilm', 'genre', 'year','tags','isChecked',
                'country', 'director', 'actors', 'budget', 'trailer', 'fullText', 'url', 'seo_description', 'dateStart',
                'seo_keywords', 'seo_title', 'dateUpdate', 'sitemap_en', 'sitemap_priority','sitemap_changefreq','order'
            ];
            if ($model->isFilm) {
                $model->checkImage('image');
                if (empty($model->errors) and $model->save(true, $saveFilmAttr)) {
                    $this->saveTegs($model->tags);
                    Log::addAdminLog("afisha[update]  ID: {$model->id}", $model->id, Log::TYPE_AFISHA);

                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } elseif ($model->save()) {
                $this->saveTegs($model->tags);
                Log::addAdminLog("afisha[update]  ID: {$model->id}", $model->id, Log::TYPE_AFISHA);

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'checkCompany' => $checkCompany,
        ]);
    }

    /**
     * Deletes an existing Afisha model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        if ($request->isGet){
            return $this->redirect(Yii::$app->request->referrer);
        }
        Wall::deleteAll(['pid'=>$id, 'type'=>File::TYPE_AFISHA]);
        $model = $this->findModel($id);
        $model->delete();
        Log::addAdminLog("afisha[delete]  ID: {$id}", $id, Log::TYPE_AFISHA);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Afisha model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Afisha the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Afisha::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPublishKino($id, $city = null)
    {
        $cities = is_null($city) ? City::find()->select('id')
            ->where(['not', ['vk_public_id' => null]])
            ->andWhere(['not', ['vk_public_admin_token' => null]])
            ->andWhere(['not', ['vk_public_admin_id' => null]])
            ->column() : [$city];

        if (($id = (int)$id) and ($afisha = Afisha::findOne($id)) and !empty($cities)) {
            foreach ($cities as $city) {
                $wall = Wall::find()->where(['pid' => $id, 'type' => File::TYPE_AFISHA_WITHOUT_SCHEDULE, 'idCity' => $city])->one();
                if (empty($wall)) {
                    $wall = new Wall([
                        'type' => File::TYPE_AFISHA_WITHOUT_SCHEDULE,
                        'pid' => $afisha->id,
                        'url' => $afisha->url,
                        'image' => ($afisha->image) ? Yii::$app->files->getUrl($afisha, 'image') : '',
                        'title' => $afisha->title,
                        'idCity' => $city,
                        'description' => ($afisha->description) ? strip_tags($afisha->description) : strip_tags($afisha->fullText)
                    ]);
                    $wall->save();
                }
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}

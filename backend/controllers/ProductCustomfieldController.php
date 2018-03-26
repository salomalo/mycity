<?php
namespace backend\controllers;

use Exception;
use yii;
use common\models\ProductCustomfield;
use common\models\ProductCustomfieldValue;
use common\models\search\ProductCustomfield as ProductCustomfieldSearch;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\AssetBundle;
use common\models\Product;
use backend\controllers\BaseAdminController;

class MyAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = ['js/addCustomfieldValue.js'];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
//        'backend\assets\MixinsAsset',
    ];
}

/**
 * ProductCustomfieldController implements the CRUD actions for ProductCustomfield model.
 */
class ProductCustomfieldController extends BaseAdminController
{
    /**
     * Lists all ProductCustomfield models.
     * @param null $category
     * @return mixed
     */
    public function actionIndex($category = null)
    {
        $category = (int)$category;
        $searchModel = new ProductCustomfieldSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $customFields = [];

        if ($category) {
            $categoryCustomFields = Product::getCustomfieldsModel($category);

            for ($i = 0; $i < count($categoryCustomFields); $i++) {
                $customFields[$i]['customfieldCategory'] = $categoryCustomFields[$i]->categoryCustomfield ?
                    $categoryCustomFields[$i]->categoryCustomfield->title : '';
                $customFields[$i]['title'] = $categoryCustomFields[$i]->title;
                $customFields[$i]['id'] = $categoryCustomFields[$i]->id;

                if ($categoryCustomFields[$i]->customfieldValue and is_array($categoryCustomFields[$i]->customfieldValue)) {
                    foreach ($categoryCustomFields[$i]->customfieldValue as $value) {
                        $customFields[$i]['value'][] = $value->value;
                    }
                } else {
                    $customFields[$i]['value'][] = '';
                }
            }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'idCat' => $category,
            'customfields' => $customFields,
        ]);
    }

    /**
     * Displays a single ProductCustomfield model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ProductCustomfield model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param int $category
     * @return mixed
     * @internal param null $id
     */
    public function actionCreate($category = null)
    {
        $customfield = new ProductCustomfield();
        
        if ($category) {
            $customfield->idCategory = (int)$category;
        }

        if ($customfield->load(Yii::$app->request->post()) && $customfield->save()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                foreach (Yii::$app->request->post('ProductCustomfieldValue', []) as $customfieldValueInput) {
                    $customfieldValue = null;

                    //If exist customfieldValue object - find it
                    if (isset($customfieldValueInput['id']) and $customfieldValueInput['id']) {
                        $customfieldValue = ProductCustomfieldValue::findOne($customfieldValueInput['id']);
                    }

                    //If empty value - delete it
                    if ($customfieldValue and empty($customfieldValueInput['value'])) {
                        $customfieldValue->delete();
                        continue;
                    }

                    //If not exist customfieldValue object - create it
                    if (!$customfieldValue) {
                        $customfieldValue = new ProductCustomfieldValue();
                    }

                    $customfieldValue->idCustomfield = $customfield->id;
                    $customfieldValue->value = $customfieldValueInput['value'];

                    if (!$customfieldValue->save()) {
                        throw new Exception();
                    }
                }
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollback();
                $customfield->addError('title', $e->getMessage());

                return $this->render('update', ['customfield' => $customfield]);
            }

            return $this->redirect(['view', 'id' => $customfield->id]);
        }

        return $this->render('create', ['customfield' => $customfield]);
    }

    /**
     * Updates an existing ProductCustomfield model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $customfield = $this->findModel($id);
        $customfield->oldAlias = $customfield->alias;
        
        if ($customfield->load(Yii::$app->request->post()) && $customfield->save()) {
            if ($customfield->oldAlias != $customfield->alias) {
                $this->updateCustomfieldProduct($customfield->oldAlias, $customfield->alias, $customfield->idCategory);
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {
                foreach (Yii::$app->request->post('ProductCustomfieldValue', []) as $customfieldValueInput) {
                    $customfieldValue = null;

                    //If exist customfieldValue object - find it
                    if (isset($customfieldValueInput['id']) and $customfieldValueInput['id']) {
                        $customfieldValue = ProductCustomfieldValue::findOne($customfieldValueInput['id']);
                    }

                    //If empty value - delete it
                    if ($customfieldValue and empty($customfieldValueInput['value'])) {
                        $customfieldValue->delete();
                        continue;
                    }

                    //If not exist customfieldValue object - create it
                    if (!$customfieldValue) {
                        $customfieldValue = new ProductCustomfieldValue();
                    }

                    $customfieldValue->idCustomfield = $customfield->id;
                    $customfieldValue->value = $customfieldValueInput['value'];

                    if (!$customfieldValue->save()) {
                        throw new Exception();
                    }
                }
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollback();
                $customfield->addError('title', $e->getMessage());

                return $this->render('update', ['customfield' => $customfield]);
            }

            return $this->redirect(['view', 'id' => $customfield->id]);
        }
        
        return $this->render('update', ['customfield' => $customfield]);
    }
    
    public function updateCustomfieldProduct($oldAlias, $newAlias, $idCategory)
    {
        $collection = Yii::$app->mongodb->getCollection('product');
        
        $collection->update(['idCategory' => (int)$idCategory], ['$rename' => [$oldAlias => $newAlias]]);
    }

    /**
     * Deletes an existing ProductCustomfield model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        ProductCustomfieldValue::deleteAll(['idCustomfield'=>$id]);
        
        $model = $this->findModel($id);
        
        $collection = \Yii::$app->mongodb->getCollection('product');
        $collection->update(['idCategory' => (int)$model->idCategory], [
                '$unset' => [$model->alias => 1]
            ]);
        
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ProductCustomfield model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductCustomfield the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProductCustomfield::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCreateValueAjax($customfield)
    {
        $customfield = (int)$customfield;

        if (!Yii::$app->request->isAjax or !$customfield) {
            throw new HttpException(404);
        }

        $customFieldValue = new ProductCustomfieldValue(['idCustomfield' => $customfield, 'value' => '']);

        if (!$customFieldValue->save()) {
            throw new HttpException(500, implode(PHP_EOL, $customFieldValue->firstErrors));
        }
        
        return $this->renderPartial('_customfield-value', ['customfieldValue' => $customFieldValue]);
    }
}

<?php
namespace common\extensions\NestedSelectCategory\Actions;

use Yii;

class GetCategory extends \yii\base\Action {

    public $model = 'common\models\ProductCategory';

    public function run() {
        $list = [];
         if (Yii::$app->request->post()) {
           $post = Yii::$app->request->post();
          // $model = $this->findOne($post['pid']);
           $class = $this->model;
           $model = $class::findOne(['id' => $post['pid']]); 
           $list = $model->children(1)->asArray()->select(['title','id'])->all();
         }
         return json_encode($list);
    }       
}
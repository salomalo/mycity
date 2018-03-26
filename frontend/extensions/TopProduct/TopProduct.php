<?php

namespace frontend\extensions\TopProduct;

use common\models\Product;
use common\models\CountViews;
use common\models\File;

/**
 * Description of TopProduct
 *
 * @author dima
 */
class TopProduct extends \yii\base\Widget
{
    public $limit = 4;
    public $title = 'популярные товары';
    public $city = '';
    public $template = 'index';
    
    public function run()
    {
        //$models = Product::find()->joinWith('countView')->orderBy('count DESC')->limit($this->limit)->all();
        $modelviews =  CountViews::find()->where(['type'=>File::TYPE_PRODUCT])
            ->orderBy(['count'=>SORT_DESC])
            ->asArray()
            ->limit($this->limit)
            ->all();
        $viewsproduct=[];
        foreach ($modelviews as $key => $value) {
            $viewsproduct[] = $value['pidMongo'];    
        }
        $models = Product::find()
            ->limit($this->limit)
            ->where(['_id'=>$viewsproduct])
            ->with(['category'])
            ->all();
        
        if(!$models){
            return false;
        }
        
        return $this->render($this->template, [
            'models' => $models,
            'title' => $this->title,
            'city' => $this->city
        ]);
    }
}

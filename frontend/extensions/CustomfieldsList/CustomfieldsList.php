<?php

namespace frontend\extensions\CustomfieldsList;

use common\models\Product;

/**
 * Description of CustomfieldsList
 *
 * @author dima
 */
class CustomfieldsList extends \yii\base\Widget
{
    public $model;
    private $attributes;
    public $isFull = true;
    public $template = '';
    
    public function run()
    {
        if (!$this->model) {
            return null;
        }
        
        $this->attributes = Product::getCustomfieldsModel($this->model->idCategory);
        
        return $this->render(($this->isFull) ? 'index' . $this->template : 'index_short'  . $this->template, [
            'model' => $this->model,
            'attributes' => $this->attributes,
        ]);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: nautilus
 * Date: 6/15/14
 * Time: 4:16 PM
 */

namespace office\extensions\BlockListPost;
use yii\helpers\Url;

class BlockListPost extends \yii\base\Widget
{
    public $title;
    public $className;
    public $attribute;
    public $path = 'post/index';

    public function run()
    {
        /**@var $models \yii\db\ActiveRecord[] */
        /**@var $class \yii\db\ActiveRecord */
        $class = $this->className;
        $models = $class::find()->all();  
       
        return $this->render('index', [
            'models' =>$models,
        ]);
    }

    public function createUrl($param)
    {
        return Url::to([$this->path,$this->attribute => $param]);
    }
} 
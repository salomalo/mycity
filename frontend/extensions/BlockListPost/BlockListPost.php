<?php

namespace frontend\extensions\BlockListPost;

use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
use yii\helpers\ArrayHelper;

class BlockListPost extends \yii\base\Widget
{
    public $title;
    public $template;
    public $className;
    public $attribute;
    public $path = 'post/index';
    public $sort = null;
    public $archive = null;
    public $sortTime = null;

    public function run()
    {
        /**@var $class \yii\db\ActiveRecord */
        $class = $this->className;
        $models = $class::find()->select(['title', 'id', 'url'])->all();
        ArrayHelper::multisort($models, ['title'], [SORT_ASC]);

        return $this->render(isset($this->template) ? $this->template : 'index', [
            'models' =>$models,
        ]);
    }

    public function createUrl($param)
    {
        return Url::to([$this->path,$this->attribute => $param, 'time' => $this->sortTime, 'sort'=> $this->sort, 'archive' => $this->archive]);
    }
} 
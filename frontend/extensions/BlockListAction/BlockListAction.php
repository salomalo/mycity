<?php

namespace frontend\extensions\BlockListAction;

use frontend\extensions\UrlWithHttp\UrlWithHttp as Url;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use Yii;
use common\models\ActionCategory;

/**
 * @property integer $filterquery
 */
class BlockListAction extends Widget
{
    public $archive = null;
    public $sortTime = null;
    public $filter = false;
    public $sort;
    private $title;
    private $attribute;
    private $path;
    public $template = 'index';

    public function init()
    {
        $this->title = Yii::t('action', 'Рубрики Акций');
        $this->path = 'action/index';
        $this->attribute = 'pid';
        parent::init();
    }

    public function run()
    {
        /**@var $class \yii\db\ActiveRecord */
        $models = ActionCategory::find()->select(['title', 'id', 'url']);
        if ($this->filter){
            $models = $models->where($this->filterquery);
        }
        $models = $models->all();
        ArrayHelper::multisort($models, ['title'], [SORT_ASC]);

        return $this->render($this->template, [
            'models' =>$models,
            'title' => $this->title,
        ]);
    }

    public function createUrl($param)
    {
        return Url::to([$this->path,$this->attribute => $param, 'time' => $this->sortTime, 'sort'=> $this->sort, 'archive' => $this->archive ? 'archive' : null]);
    }

    public function getFilterQuery()
    {
        $str[] = '"id" IN (
                SELECT DISTINCT "idCategory"
                FROM "action" WHERE';
        if (Yii::$app->params['SUBDOMAINID']) {
            $str[] = '"idCompany" IN (
                    SELECT "id" FROM "business"
                    WHERE "idCity" =';
            $str[] = Yii::$app->params['SUBDOMAINID'];
            $str[] = ') AND';
        }
        $str[] = '"dateEnd"';
        $str[] = ($this->archive) ? '<' : '>=';
        $str[] = '\'';
        $str[] = date('Y-m-d');
        $str[] = '\'';
        $str[] = ')';
        return implode(' ', $str);
    }
} 
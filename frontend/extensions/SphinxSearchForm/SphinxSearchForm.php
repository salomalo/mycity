<?php

namespace frontend\extensions\SphinxSearchForm;

use Yii;
use yii\base\Widget;
use common\models\City;

/**
 * Description of SphinxSearchForm
 *
 * @author dima
 */
class SphinxSearchForm extends Widget
{
    const TYPE_ACTION = 0;
    const TYPE_AFISHA = 1;
    const TYPE_POST = 2;
    const TYPE_BUSINESS = 3;
    const TYPE_WORK_WACANTION = 4;
    const TYPE_ADS = 5;
    
    
    public $controller = null;
    public $action = 'index';
    public $search = null;
    public $id_city = null;
    public $type = null;
    public $page = 1;
    //public $pid = null;

    public function init()
    {
        parent::init();
        if (empty($this->controller)) {
            $this->controller = 'search';
        }
        //Assets::register($this->getView());
    }

    public function run()
    {
        //$model = new SphinxSearch();
        
        return $this->render('index', [
            'url' => '/' . $this->controller . '/' . $this->action,
            's' => $this->search,
            'id_city' => $this->id_city,
            'type' => $this->type,
            'typeList' => $this->getTypes(),
            'page' => $this->page,
            'cityList' => Yii::$app->params['cities'][City::ACTIVE],
            //'pid' => $this->pid
        ]);
    }
    
    /**
     * @brief Типы
     * @param bool|false $id
     * @return array
     */
    public static function getTypes($id=false)
    {
        $data = [
            self::TYPE_ACTION => 'Акции',
            self::TYPE_AFISHA => 'Афиша',
            self::TYPE_POST => 'Новости',
            self::TYPE_BUSINESS => 'Предприятия',
            self::TYPE_WORK_WACANTION => 'Вакансии',
            self::TYPE_ADS => 'Обьявления',
        ];
        if($id !== false) {
            return $data[$id];
        }
        return $data;
    }
}

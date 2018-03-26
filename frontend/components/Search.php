<?php

namespace frontend\components;

use yii\base\Component;
use yii\sphinx\Query;

/**
 * Description of Sphinx
 */
class Search extends Component
{
    const TYPE_BUSINESS     = 'business';
    const TYPE_ACTION       = 'action';
    const TYPE_AFISHA       = 'afisha';
    const TYPE_VACANTION    = 'work_vacantion';
    const TYPE_RESUME       = 'work_resume';

    const TYPE_PRODUCT      = 'product';
    const TYPE_ADS          = 'ads';
    const TYPE_NEWS         = 'news';
    const TYPE_ALL          = 'business, action, afisha, work_vacantion, work_resume';

    protected $_items_per_page = 20;
    /**@var Query*/
    protected $query;

    public function init()
    {
        $this->initNewQuery();
        parent::init();
    }

    private function initNewQuery()
    {
        $this->query = new Query();
        $this->query->from(self::TYPE_ALL);
    }

    public function text($text)
    {
        $this->query->match($text);
        return $this;
    }
    
    public function type($type)
    {
        $this->query->from($type);
        return $this;
    }
    
    public function cityId($id)
    {
        $this->query->andWhere(['id_city' => $id]);
        return $this;
    }
    
    public function categories($categories)
    {
        $this->query->andWhere(['id_category' => $categories]);
        return $this;
    }
    
    public function customfields($customfields)
    {
        $this->query->andWhere(['id_customfields' => $customfields]);
        return $this;
    }
    
    public function page($page)
    {
        $this->query->offset($page);
        return $this;
    }

    public function all()
    {
        $data = $this->query->all();
        $this->initNewQuery();
        return $data;
    }
}

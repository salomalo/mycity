<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Description of SphinxSearchForm
 *
 * @author dima
 */
class SphinxSearchForm extends Model
{
    const TYPE_BUSINESS = 1;
    const TYPE_ACTION = 2;
    const TYPE_AFISHA = 3;
    const TYPE_WORK_WACANTION = 4;
    const TYPE_WORK_RESUME = 5;

    public $s = null;
    public $id_city = null;
    public $type = null;
    
    public function rules()
    {
        return [
            [['s'], 'string'],
            // email has to be a valid email address
            [['type', 'id_city'], 'integer'],
        ];
    }
    
    /**
     * @brief Типы
     * @param bool|false $id
     * @return array
     */
    public static function getTypes($id = false)
    {
        $data = [
            self::TYPE_BUSINESS => 'Предприятия',
            self::TYPE_ACTION => 'Акции',
            self::TYPE_AFISHA => 'Афиша',
            self::TYPE_WORK_WACANTION => 'Вакансии',
            self::TYPE_WORK_RESUME => 'Резюме',
        ];
        if ($id !== false) {
            return $data[$id];
        }
        
        return $data;
    }
}

<?php

namespace common\components;

use Yii;
use \yii\mongodb\ActiveRecord;

/**
 * Description of ActiveRecordMongoMultiLang
 *
 * @author dima
 */
class ActiveRecordMongoMultiLang extends ActiveRecord
{
    public function translateAttributes()
    {
        return [];
    }

    public function __get($name)
    {
        $value = parent::__get($name);
        if ($value && in_array($name, $this->translateAttributes())) {
            $v = json_decode($value, true);
            if (is_array($v)) {
//                $value = $v[Yii::$app->language];
                $value = (!empty($v[Yii::$app->language]))? $v[Yii::$app->language] : array_shift($v);
                while (empty($value) && count($v) > 0) {
                    $value = array_shift($v);
                }
            }
        }
        return $value;
    }

    public function __set($name, $value)
    {
        if (in_array($name, $this->translateAttributes())) {
            $v = parent::__get($name);
            $v = json_decode($v, true);
            if (!is_array($v)) {
                $v = [];
            }
            $v[Yii::$app->language] = $value;
            $value = json_encode($v, JSON_UNESCAPED_UNICODE);
        }
        parent::__set($name, $value);
    }
    
    public function upFirstLetter($str, $encoding = 'UTF-8')
    {
        return mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding)
        . mb_substr($str, 1, null, $encoding);
    }
}

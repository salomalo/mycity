<?php

namespace common\components;

use yii;
use yii\db\ActiveRecord;

class ActiveRecordMultiLang extends ActiveRecord
{
    public $statusOfLangBrute = true;

    public function init()
    {
        $this->statusOfLangBrute = (bool)Yii::$app->params['isShowAnyLangModEnabled'];
        parent::init();
    }

    public function __construct($config = array())
    {
        parent::__construct($config);
        if ($this->hasAttribute('sitemap_en')) {
            $this->setAttribute('sitemap_en', 1);
        }
    }

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
                if ($this->statusOfLangBrute === true) {
                    $value = (!empty($v[Yii::$app->language])) ? $v[Yii::$app->language] : array_shift($v);
                    while (empty($value) && count($v) > 0) {
                        $value = array_shift($v);
                    }
                } else {
                    $value = isset($v[Yii::$app->language]) ? $v[Yii::$app->language] : null;
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
    
    public function transformSearch($str, $attribute, $encoding = 'UTF-8')
    {
        $resultLower = mb_strtolower($str, $encoding);
        return [
                'or',
                ['like', $attribute, $str],
                ['like', $attribute, mb_strtoupper($str, $encoding)],
                ['like', $attribute, $resultLower],
                ['like', $attribute, mb_strtoupper(mb_substr($resultLower, 0, 1, $encoding), $encoding)
                                    . mb_substr($resultLower, 1, null, $encoding)],
        ];
    }
}

<?php

namespace common\models;

use yii;
use \yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * This is the model class for table "lang".
 *
 * @property integer $id
 * @property string $url
 * @property string $local
 * @property string $name
 * @property integer $default
 * @property integer $date_update
 * @property integer $date_create
 */
class Lang extends ActiveRecord
{
    /**
     * Переменная, для хранения текущего объекта языка
     * @var null|Lang $current
     */
    public static $current = null;

    /** @var Lang $alternate */
    public static $alternate = null;

    public static $active = null;

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date_create', 'date_update'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['date_update'],
                ],
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'local', 'name', 'date_update', 'date_create'], 'required'],
            [['default', 'date_update', 'date_create'], 'integer'],
            [['url', 'local', 'name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'local' => 'Local',
            'name' => 'Name',
            'default' => 'Default',
            'date_update' => 'Date Update',
            'date_create' => 'Date Create',
        ];
    }

    //Получение текущего объекта языка
    public static function getCurrent()
    {
        if (is_null(self::$current)) {
            self::$current = self::getDefaultLang();
        }
        return self::$current;
    }

    //Установка текущего объекта языка и локаль пользователя
    public static function setCurrent($url = null)
    {
        $language = self::getLangByUrl($url);
        self::$current = ($language === null) ? self::getDefaultLang() : $language;
        Yii::$app->language = self::$current->local;
    }

    //Получения объекта языка по умолчанию
    public static function getDefaultLang()
    {
        return Lang::find()->where(['default' => 1])->one();
    }

    //Получения объекта языка по буквенному идентификатору
    public static function getLangByUrl($url = null)
    {
        if ($url === null) {
            return null;
        } else {
            $language = Lang::find()->where('url = :url', [':url' => $url])->one();
            if ($language === null) {
                return null;
            } else {
                return $language;
            }
        }
    }

    public static function getAlternate()
    {
        if (is_null(self::$alternate)) {
            self::$alternate = Lang::find()->where(['not', ['id' => self::$current->id]])->one();
        }

        return self::$alternate;
    }
    
    public static function getAlternateUrl()
    {
        $cur_lang = Lang::getCurrent()->url;
        $alt_lang = Lang::getAlternate()->url;
        $canonical = str_replace('/site', '', Url::current([], YII_ENV === 'prod' ? 'https' : 'http'));
        $position = stripos("$canonical/", "/$cur_lang/");

        $alt_canonical = null;
        if ($position !== false) {
            $alt_canonical = substr_replace($canonical, $alt_lang, ($position + 1), 2);
        }

        return $alt_canonical;
    }

    public static function getActive()
    {
        if (is_null(self::$active)) {
            self::$active = Lang::find()->select('url')->column();
        }

        return self::$active;
    }
}

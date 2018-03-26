<?php

namespace common\models;

use yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "file".
 *
 * @property integer $id
 * @property string $type
 * @property integer $pid
 * @property integer $size
 * @property string $name
 * @property string $dateCreate
 * @property string $pidMongo
 * @property string $alias
 */
class File extends ActiveRecord
{
    const TYPE_ACCOUNT                  = 1;
    const TYPE_BUSINESS                 = 2;
    const TYPE_BUSINESS_CATEGORY        = 3;
    const TYPE_POST                     = 4;
    const TYPE_POST_CATEGORY            = 5;
    const TYPE_RESUME                   = 6;
    const TYPE_PRODUCT                  = 7;
    const TYPE_PRODUCT_CATEGORY         = 8;
    const TYPE_PRODUCT_COMPANY          = 9;
    const TYPE_PRODUCT_GALLERY          = 10;
    const TYPE_ADS                      = 11;
    const TYPE_ADS_GALLERY              = 12;
    const TYPE_GALLERY                  = 13;
    const TYPE_AFISHA                   = 14;
    const TYPE_AFISHA_CATEGORY          = 15;
    const TYPE_ACTION                   = 16;
    const TYPE_ACTION_CATEGORY          = 17;
    const TYPE_BUSINESS_PRICE           = 18;
    const TYPE_AFISHA_TRAILER           = 19;
    const TYPE_WORK_VACANTION           = 20;
    const TYPE_ADS_IMAGES               = 21;
    const TYPE_ADVERT                   = 22;
    const TYPE_PAYMENT_TYPE             = 23;
    const TYPE_ADS_COLOR                = 24;
    const TYPE_SCHEDULE_KINO            = 100;
    const TYPE_AFISHA_WITH_SCHEDULE     = 101;
    const TYPE_AFISHA_WITHOUT_SCHEDULE  = 102;
    const TYPE_SUPPORT                  = 200;
    const TYPE_SUPPORT_BUSINESS         = 201;
    const TYPE_SUPPORT_ADS              = 202;
    const TYPE_SUPPORT_ADVERT           = 203;
    const TYPE_BUSINESS_TEMPLATE        = 204;

    public static $typeLabels = [
        self::TYPE_ACCOUNT                  => 'Аккаунт',
        self::TYPE_BUSINESS                 => 'Предприятие',
        self::TYPE_BUSINESS_CATEGORY        => 'Категория предприятия',
        self::TYPE_POST                     => 'Новость',
        self::TYPE_POST_CATEGORY            => 'Категория новости',
        self::TYPE_RESUME                   => 'Резюме',
        self::TYPE_PRODUCT                  => 'Продукт',
        self::TYPE_PRODUCT_CATEGORY         => 'Категория продукта',
        self::TYPE_PRODUCT_COMPANY          => 'Производитель продукта',
        self::TYPE_PRODUCT_GALLERY          => 'Галерея продукта',
        self::TYPE_ADS                      => 'Объявление',
        self::TYPE_ADS_GALLERY              => 'Галерея объявления',
        self::TYPE_GALLERY                  => 'Галерея',
        self::TYPE_AFISHA                   => 'Афиша',
        self::TYPE_AFISHA_CATEGORY          => 'Категория афиши',
        self::TYPE_ACTION                   => 'Акция',
        self::TYPE_ACTION_CATEGORY          => 'Категория Акции',
        self::TYPE_BUSINESS_PRICE           => 'Прайс предприятия',
        self::TYPE_AFISHA_TRAILER           => 'Трейлер афиши',
        self::TYPE_WORK_VACANTION           => 'Вакансия',
        self::TYPE_ADS_IMAGES               => 'Изображения объявления',
        self::TYPE_ADVERT                   => 'Реклама',
        self::TYPE_PAYMENT_TYPE             => 'Тип оплаты',
        self::TYPE_SCHEDULE_KINO            => 'Расписание фильма',
        self::TYPE_AFISHA_WITH_SCHEDULE     => 'Афиша с расписанием',
        self::TYPE_AFISHA_WITHOUT_SCHEDULE  => 'Афиша без расписания',
        self::TYPE_SUPPORT                  => 'Поддержка',
        self::TYPE_SUPPORT_BUSINESS         => 'Поддержка предприятия',
        self::TYPE_SUPPORT_ADS              => 'Поддержка объявления',
        self::TYPE_SUPPORT_ADVERT           => 'Поддержка реклама',
    ];

    public static $typeAliases = [
        self::TYPE_GALLERY                  => 'gallery',
        self::TYPE_BUSINESS                 => 'business',
        self::TYPE_BUSINESS_PRICE           => 'business',
        self::TYPE_BUSINESS_CATEGORY        => 'businesscategory',
        self::TYPE_PRODUCT                  => 'product',
        self::TYPE_PRODUCT_GALLERY          => 'product',
        self::TYPE_PRODUCT_CATEGORY         => 'productcategory',
        self::TYPE_PRODUCT_COMPANY          => 'productcompany',
        self::TYPE_AFISHA                   => 'afisha',
        self::TYPE_AFISHA_TRAILER           => 'afisha',
        self::TYPE_AFISHA_WITH_SCHEDULE     => 'afisha',
        self::TYPE_AFISHA_WITHOUT_SCHEDULE  => 'afisha',
        self::TYPE_AFISHA_CATEGORY          => 'afishacategory',
        self::TYPE_ADS                      => 'ads',
        self::TYPE_ADS_GALLERY              => 'ads',
        self::TYPE_ADS_IMAGES               => 'ads',
        self::TYPE_RESUME                   => 'workresume',
        self::TYPE_WORK_VACANTION           => 'workvacantion',
        self::TYPE_POST                     => 'post',
        self::TYPE_POST_CATEGORY            => 'postcategory',
        self::TYPE_ACTION                   => 'action',
        self::TYPE_ACTION_CATEGORY          => 'actioncategory',
        self::TYPE_ACCOUNT                  => 'account',
        self::TYPE_ADVERT                   => 'advert',
        self::TYPE_PAYMENT_TYPE             => 'paymenttype',
        self::TYPE_SCHEDULE_KINO            => 'schedulekino',
        self::TYPE_SUPPORT                  => 'question',
        self::TYPE_SUPPORT_BUSINESS         => 'question',
        self::TYPE_SUPPORT_ADS              => 'question',
        self::TYPE_SUPPORT_ADVERT           => 'question',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'name'], 'required','message' => 'Поле не может быть пустым'],
            [['size'], 'integer'],
            [['dateCreate'], 'safe'],
            [['pid', 'type', 'name', 'pidMongo'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'pid' => 'Pid',
            'size' => 'Size',
            'name' => 'Name',
            'dateCreate' => 'Date Create',
            'pidMongo' => 'Mongo'
        ];
    }
    
    public function getAlias()
    {
        return isset(self::$typeAliases[$this->type]) ? self::$typeAliases[$this->type] : null;
    }
}

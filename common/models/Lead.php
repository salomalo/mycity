<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lead".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $description
 * @property string $date_create
 * @property string $utm_source
 * @property string $utm_campaign
 * @property integer $status
 */
class Lead extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 1;
    const STATUS_CALL = 2;
    const STATUS_CANCEL = 3;
    const STATUS_TREATED = 4;

    public static $statusTypes = [
        self::STATUS_NEW => 'Новая',
        self::STATUS_CALL => 'Перезвонить',
        self::STATUS_CANCEL => 'Отменена',
        self::STATUS_TREATED => 'Обработана',
    ];

    public static $statusTypesHtml = [
        self::STATUS_NEW => '<div style="color: #53ce2b">Новая</div>',
        self::STATUS_CALL => '<div style="color: #53ce2b">Перезвонить</div>',
        self::STATUS_CANCEL => '<div style="color: #A9A9A9">Отменена</div>',
        self::STATUS_TREATED => 'Обработана',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lead';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'description'], 'required'],
            [['date_create'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 20],
            [['description'], 'string'],
            [['utm_source', 'utm_campaign'], 'string'],
            [['status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'phone' => 'Телефон',
            'description' => 'Описание магазина',
            'date_create' => 'Дата',
            'utm_source' => 'Utm Source',
            'utm_campaign' => 'Utm Campaign',
            'status' => 'Статус',
        ];
    }

    public function getStatusHtml()
    {
        return static::$statusTypesHtml[$this->status];
    }
}

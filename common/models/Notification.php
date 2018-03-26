<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "notification".
 *
 * @property integer $id
 * @property integer $sender_id
 * @property integer $status
 * @property integer $type_js
 * @property string $title
 * @property string $link
 * @property string $created_at
 */
class Notification extends \yii\db\ActiveRecord
{
    const STATUS_VISITED = 1;
    const STATUS_NEW = 0;
    const STATUS_HIDE = 2;

    const TYPE_JS_NONE = 0;
    const TYPE_JS_PAYMENT_RECIEVE = 1;

    public static $listStatus = [
        self::STATUS_NEW => 'Новое',
        self::STATUS_VISITED => 'Просмотренное',
        self::STATUS_HIDE => 'Спрятаное',
    ];

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sender_id', 'status'], 'required'],
            [['sender_id', 'status', 'type_js'], 'integer'],
            [['created_at'], 'safe'],
            [['title', 'link'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sender_id' => 'ID пользователя',
            'status' => 'Тип оповещения',
            'title' => 'Название',
            'link' => 'Ссылка',
            'created_at' => 'Создано',
        ];
    }
}

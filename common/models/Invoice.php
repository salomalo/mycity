<?php

namespace common\models;

use yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\Html;

/**
 * This is the model class for table "invoice".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $object_type
 * @property integer $object_id
 * @property string $paid_from
 * @property string $paid_to
 * @property string $order_id
 * @property string $created_at
 * @property integer $amount
 * @property integer $paid_status
 * @property string $description
 * @property User $user
 * @property string $objectLabel
 */
class Invoice extends ActiveRecord
{
    const PAID_NO = 0;
    const PAID_YES = 1;

    public static $statusPaid = [
        self::PAID_YES => 'Оплачен',
        self::PAID_NO => 'Не оплачен',
    ];

    public function behaviors()
    {
        return [[
            'class' => TimestampBehavior::className(),
            'createdAtAttribute' => 'created_at',
            'updatedAtAttribute' => false,
            'value' => new Expression('NOW()'),
        ]];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'object_type', 'object_id', 'order_id'], 'required'],
            [['user_id', 'object_type', 'object_id', 'amount', 'paid_status'], 'integer'],
            [['description', 'order_id'], 'string'],
            [['paid_from', 'paid_to', 'created_at', 'description'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'object_type' => 'Object Type',
            'object_id' => 'Object ID',
            'amount' => 'Sum',
            'paid_from' => 'Paid From',
            'paid_to' => 'Paid To',
            'created_at' => 'Created At',
            'paid_status' => 'Paid Status',
            'description' => 'Description',
            'order_id' => 'Уникальный id заказа',
        ];
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getObjectLabel()
    {
        switch ($this->object_type) {
            case File::TYPE_BUSINESS:
                $business = Business::findOne($this->object_id);
                return $business ? Html::a($business->title, ['/business/view', 'id' => $this->object_id]) : $this->object_id;
            default:
                return $this->object_id;
        }
    }

    public function getStatusPaid(){
        if ($this->paid_status) {
            return '<div class="business-status-paid">' . self::$statusPaid[$this->paid_status] . '<div>';
        } else {
            return '<div class="business-status-overdue">' . self::$statusPaid[$this->paid_status] . '<div>';
        }
    }
}

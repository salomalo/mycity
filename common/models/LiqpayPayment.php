<?php
namespace common\models;

use yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "liqpay_payment".
 *
 * @property integer $id
 * @property string $status
 * @property string $order_id
 * @property string $action
 * @property string $data
 * @property string $amount
 * @property string $currency
 * @property string $created_at
 * @property string $updated_at
 */
class LiqpayPayment extends ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'liqpay_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['amount'], 'integer'],
            [['status', 'order_id', 'action', 'currency'], 'string', 'max' => 255],
            [['order_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'order_id' => 'Order ID',
            'action' => 'Action',
            'amount' => 'Amount',
            'currency' => 'Currency',
            'data' => 'Data',
        ];
    }
}

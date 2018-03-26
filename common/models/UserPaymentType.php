<?php

namespace common\models;

use yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "user_payment_type".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $payment_type_id
 * @property string $description
 * @property string $created_at
 *
 * @property PaymentType $paymentType
 * @property User $user
 * @property UserPaymentTypeBusiness[] $userPaymentTypeBusinesses
 * @property Business[] $businesses
 */
class UserPaymentType extends ActiveRecord
{
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
        return 'user_payment_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'payment_type_id'], 'required'],
            [['user_id', 'payment_type_id'], 'integer'],
            [['description'], 'string'],
            [['created_at'], 'safe'],
            [['payment_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentType::className(), 'targetAttribute' => ['payment_type_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'payment_type_id' => 'Способы оплаты',
            'description' => 'Описание(Номер счета)',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentType()
    {
        return $this->hasOne(PaymentType::className(), ['id' => 'payment_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPaymentTypeBusinesses()
    {
        return $this->hasMany(UserPaymentTypeBusiness::className(), ['user_payment_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinesses()
    {
        return $this->hasMany(Business::className(), ['id' => 'business_id'])->via('userPaymentTypeBusinesses');
    }

    public static function getAll($user = null)
    {
        $query = self::find()->with('paymentType');
        if ($user) {
            $query->where(['user_id' => $user]);
        }
        /** @var self[] $models */
        $models = $query->all();

        $array = [];
        foreach ($models as $model) {
            $array[$model->id] = $model->paymentType->title;
        }

        return $array;
    }
}

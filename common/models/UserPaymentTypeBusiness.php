<?php

namespace common\models;

use yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_payment_type_business".
 *
 * @property integer $id
 * @property integer $user_payment_type_id
 * @property integer $business_id
 *
 * @property Business $business
 * @property UserPaymentType $userPaymentType
 */
class UserPaymentTypeBusiness extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_payment_type_business';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_payment_type_id', 'business_id'], 'required'],
            [['user_payment_type_id', 'business_id'], 'integer'],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::className(), 'targetAttribute' => ['business_id' => 'id']],
            [['user_payment_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserPaymentType::className(), 'targetAttribute' => ['user_payment_type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_payment_type_id' => 'User Payment Type ID',
            'business_id' => 'Business ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusiness()
    {
        return $this->hasOne(Business::className(), ['id' => 'business_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPaymentType()
    {
        return $this->hasOne(UserPaymentType::className(), ['id' => 'user_payment_type_id']);
    }
}

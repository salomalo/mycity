<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_reg_info".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $utm_source
 * @property string $utm_campaing
 *
 * @property User $user
 */
class UserRegInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_reg_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['utm_source', 'utm_campaing'], 'string'],
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
            'utm_source' => 'Utm Source',
            'utm_campaing' => 'Utm Campaing',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}

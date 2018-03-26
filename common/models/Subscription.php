<?php

namespace common\models;

use yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "subscription".
 *
 * @property integer $id
 * @property string $email
 * @property string $name
 * @property string $created_at
 * @property integer $status
 */
class Subscription extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_NOT_ACTIVE = 0;

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
        return 'subscription';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'name', 'status'], 'required'],
            [['created_at'], 'safe'],
            [['status'], 'integer'],
            [['email', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'name' => 'Name',
            'created_at' => 'Created At',
            'status' => 'Status',
        ];
    }
}

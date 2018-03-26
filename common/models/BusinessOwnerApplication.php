<?php
namespace common\models;

use yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "business_owner_application".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $business_id
 * @property integer $status
 * @property string $token
 * @property string $created_at
 *
 * @property Business $business
 * @property User $user
 * @property string $statusLabel
 */
class BusinessOwnerApplication extends ActiveRecord
{
    const STATUS_OPEN = 1;
    const STATUS_READY = 2;

    public static $statuses = [
        self::STATUS_OPEN => 'Подана заявка',
        self::STATUS_READY => 'Заявка выполнена',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'business_owner_application';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'business_id', 'status'], 'required'],
            [['user_id', 'business_id', 'status'], 'integer'],
            [['created_at'], 'safe'],
            [['token'], 'string', 'max' => 256],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::className(), 'targetAttribute' => ['business_id' => 'id']],
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
            'business_id' => 'Business ID',
            'status' => 'Status',
            'token' => 'Token',
            'created_at' => 'Created At',
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @param $user_id int
     * @return int
     */
    public static function countAllApplications($user_id)
    {
        return self::countApplicationsByStatus($user_id);
    }

    public function getStatusLabel()
    {
        return isset(self::$statuses[$this->status]) ? self::$statuses[$this->status] : null;
    }

    /**
     * @param $user_id int
     * @param null|int $status
     * @return int
     */
    public static function countApplicationsByStatus($user_id, $status = null)
    {
        $query = self::find()->where(['user_id' => $user_id]);
        if ($status) {
            $query->andWhere(['status' => $status]);
        }
        return $query->count();
    }
}

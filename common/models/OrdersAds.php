<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "orders_ads".
 *
 * @property integer $id
 * @property integer $pid
 * @property string $idAds
 * @property integer $countAds
 * @property integer $idBusiness
 * @property integer $status
 * @property integer $idUser
 */
class OrdersAds extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ONCONFIRMATION = 1;    // на подтверждении
    const STATUS_TREATMENT = 2;         // в обработке
    const STATUS_SENT = 3;              // отправлен
    const STATUS_CANCEL = 4;            // отменён
    
    public static $statusList = [
        self::STATUS_ONCONFIRMATION => 'На подтверждении',
        self::STATUS_TREATMENT => 'В обработке',
        self::STATUS_SENT => 'Отправлен',
        self::STATUS_CANCEL => 'Отменён',
        self::STATUS_DELETED => 'Удалён',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders_ads';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'countAds', 'idBusiness', 'status', 'idUser'], 'integer'],
            [['idAds'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'idAds' => 'Название Объявления',
            'countAds' => 'Количество',
            'idBusiness' => 'Предприятия',
            'status' => 'Статус',
            'idUser' => 'Id пользователя',
        ];
    }
    
    public function getBusiness()
    {
        return $this->hasOne(Business::className(), ['id' => 'idBusiness']);
    }
    
    public function getOrder()
    {
        return $this->hasOne(Orders::className(), ['id' => 'pid']);
    }
    
    public function getAds()
    {
        return $this->hasOne(Ads::className(), ['_id' => 'idAds']);
    }
}

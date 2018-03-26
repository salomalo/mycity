<?php

namespace common\models;

use common\models\traits\GetCityTrait;
use Yii;
use yii\db\ActiveRecord;
use common\models\traits\GetUserTrait;
use yii\helpers\Html;

/**
 * This is the model class for table "orders".
 *
 * @property integer $id
 * @property integer $idUser
 * @property integer $idCity
 * @property string $address
 * @property string $phone
 * @property string $fio
 * @property string $description
 * @property integer $paymentType
 * @property integer $dateCreate
 * @property City $city
 * @property User $user
 * @property UserPaymentType $payment
 * @property string $delivery
 * @property string $office
 * @property integer $status
 * @property integer $idSeller
 */
class Orders extends \yii\db\ActiveRecord
{
    use GetUserTrait; //public function getUser()
    use GetCityTrait;

    const STATUS_DELETED = 0;
    const STATUS_ONCONFIRMATION = 1;    // на подтверждении
    const STATUS_TREATMENT = 2;         // в обработке
    const STATUS_SENT = 3;              // отправлен
    const STATUS_CANCEL = 4;            // отменён
    const STATUS_NEW = 5;               //новый
    const STATUS_CONFIR = 6 ;           //подтвержден

    public static $statusList = [
        self::STATUS_ONCONFIRMATION => 'На подтверждении',
        self::STATUS_TREATMENT => 'В обработке',
        self::STATUS_SENT => 'Отправлен',
        self::STATUS_CANCEL => 'Отменён',
        self::STATUS_DELETED => 'Удалён',
        self::STATUS_NEW => 'Новый',
        self::STATUS_CONFIR => 'Подтвержден',
    ];

    public $idRegion;
    public $paymentAccount;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['dateCreate'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idUser', 'idCity', 'office', 'delivery'], 'required'],
            [['idUser', 'paymentType', 'dateCreate', 'idCity', 'idRegion', 'status', 'idSeller'], 'integer'],
            [['address', 'phone','fio','description', 'office', 'delivery', 'paymentAccount'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idUser' => 'Id User',
            'idCity' => 'Город',
            'idRegion' => 'Область',
            'description' => 'Доп. информация',
            'phone' => 'Телефон',
            'fio' => 'Ф.И.О.',
            'address' => 'Адрес',
            'paymentType' => 'Способ оплаты',
            'dateCreate' => 'Дата заказа',
            'delivery' => 'Способ доставки',
            'office' => 'Отделение',
            'status' => 'Статус',
            'idSeller' => 'id продавца',
            'paymentAccount' => 'Номер кредитной карты(кошелька)',
        ];
    }
    
    public function getPayment()
    {
        return $this->hasOne(UserPaymentType::className(), ['id' => 'paymentType']);
    }

    public function beforeDelete()
    {   
        if (parent::beforeDelete()) {
            OrdersAds::deleteAll(['pid'=>$this->id]);
        return true;
        } else {
            return false;
        }
    }
}

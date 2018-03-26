<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 07.11.2016
 * Time: 14:34
 */

namespace frontend\models;

use common\models\PaymentType;
use common\models\UserPaymentType;
use yii\base\Model;

class ShoppingCartForm  extends Model
{
    const PAYMENT_USER_MODEL = 1;
    const PAYMENT_BASIC_MODEL = 2;

    public $idUser;
    public $idCity;
    public $idRegion;
    public $phone;
    public $fio;
    public $address;
    public $paymentType;
    public $paymentTypeModel;
    public $delivery;
    public $office;

    public $username;
    public $email;
    public $password;
    public $password2;
    public $apply;
    //public $verifyCode;

    public function rules()
    {
        return [
            [['idUser', 'idCity', 'office', 'delivery', 'paymentType', 'idRegion', 'phone'], 'required'],
            [['idUser', 'paymentType', 'idCity', 'idRegion', 'paymentTypeModel'], 'integer'],
            [['address', 'phone','fio', 'office', 'delivery'], 'string', 'max' => 255],

            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required','message' => 'Поле не может быть пустым'],
            //['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Это имя пользователя уже занято.'],
            ['username', 'string', 'min' => 2, 'max' => 255, 'message' => 'Длина имени должна быть от 2 до 255 символов.'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required','message' => 'Поле не может быть пустым'],
            ['email', 'email','message' => 'Не правильный формат'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Этот адрес электронной почты уже занят.'],

            [['password', 'password2'], 'required','message' => 'Поле не может быть пустым'],
            ['password2', 'compare', 'message' => 'Пароли не совпадают.', 'compareAttribute'=>'password'],
            [['password', 'password2'], 'string', 'min' => 5,'message' => 'Длина не менее 5 ти символов'],
            [ 'apply', 'required', 'requiredValue' => true, 'message' => 'Вы должны принять условия пользовательского соглашения'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idUser' => 'Id User',
            'idCity' => 'Город',
            'idRegion' => 'Область',
            'phone' => 'Телефон',
            'fio' => 'Ф.И.О.',
            'address' => 'Адрес',
            'paymentType' => 'Способ оплаты',
            'delivery' => 'Cпособ доставки/Cлужба доставки',
            'office' => 'Отделение',

            'username' => 'Логин',
            'email' => 'Email',
            'password' => 'Пароль',
            'password2' => 'Подтверждение пароля',
            'apply' => 'Принимаю условия',
        ];
    }

    public function getPaymentDescription()
    {
        switch ($this->paymentTypeModel) {
            case self::PAYMENT_BASIC_MODEL:
                $paymentType = PaymentType::findOne($this->paymentType);
                return $paymentType ? $paymentType->title : '';
            case self::PAYMENT_USER_MODEL:
                $userPayment = UserPaymentType::findOne($this->paymentType);
                return $userPayment ? ($userPayment->paymentType->title . ' ' . $userPayment->description) : '';
            default:
                return '';
        }
    }
}

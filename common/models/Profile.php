<?php

namespace common\models;

use common\models\traits\GetUserTrait;
use Yii;
use dektrium\user\models\Profile as DektriumProfile;
use yii\helpers\Html;

/**
 * This is the model class for table "profile".
 *
 * @property integer $user_id
 * @property string $name
 * @property string $public_email
 * @property string $gravatar_email
 * @property string $gravatar_id
 * @property string $location
 * @property string $website
 * @property string $bio
 * @property integer $country_id
 * @property integer $birth_city_id
 * @property integer $gender
 * @property string $birth_date
 * @property City $birthCity
 * @property string $address
 * @property string $phone
 * @property string $fio
 *
 * @property User $user
 */
class Profile extends DektriumProfile
{
    use GetUserTrait;

    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    public static function tableName()
    {
        return 'profile';
    }

    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['public_email'], 'email'],
            [['user_id', 'gender', 'birth_city_id', 'country_id'], 'integer'],
            [['bio'], 'string'],
            [['birth_date'], 'validateUserBirthDate'],
            [['birth_date'], 'date', 'format' => 'php:Y-m-d'],
            [['name', 'public_email', 'gravatar_email', 'location', 'website', 'address', 'phone', 'fio'], 'string', 'max' => 255],
            [['gravatar_id'], 'string', 'max' => 32]
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'name' => 'Имя',
            'public_email' => 'Публ. E-mail',
            'gravatar_email' => 'Gravatar Email',
            'gravatar_id' => 'Gravatar ID',
            'location' => 'Location',
            'website' => 'Сайт',
            'bio' => 'Биография',
            'gender' => 'Пол',
            'birth_date' => 'Дата рождения',
            'country_id' => 'Страна',
            'birth_city_id' => 'Город',
            'address' => 'Аддрес',
            'phone' => 'Телефон',
            'fio' => 'ФИО',
        ];
    }

    public function beforeSave($insert)
    {
        $this->name = Html::encode($this->name);
        $this->location = Html::encode($this->location);
        $this->website = Html::encode($this->website);
        $this->bio = Html::encode($this->bio);

        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        $this->name = Html::decode($this->name);
        $this->location = Html::decode($this->location);
        $this->website = Html::decode($this->website);
        $this->bio = Html::decode($this->bio);

        parent::afterFind();
    }

    public static function getCountries()
    {
        return [
            1 => Yii::t('user', 'Украина'),
            2 => Yii::t('user', 'Россия'),
            3 => Yii::t('user', 'Беларусь'),
        ];
    }

    public static function getGenderLabels()
    {
        return [self::GENDER_FEMALE => Yii::t('user', 'Женский'), self::GENDER_MALE => Yii::t('user', 'Мужской')];
    }

    public function getBirthCity()
    {
        return $this->hasOne(City::className(), ['id' => 'birth_city_id']);
    }

    public function validateUserBirthDate($attribute, $params)
    {
        $date = new \DateTime();
        $minAgeDate = date_format($date, 'Y-m-d');
        date_sub($date, date_interval_create_from_date_string('80 years'));
        $maxAgeDate = date_format($date, 'Y-m-d');
        if ($this->$attribute > $minAgeDate || $this->$attribute < $maxAgeDate) {
            $this->addError($attribute, 'Введите дату в диапазоне ' . $maxAgeDate  . ' -- ' . $minAgeDate);
        }
    }
}

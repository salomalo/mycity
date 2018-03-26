<?php

namespace common\models;

use Yii;
use common\components\ActiveRecordMultiLang;

/**
 * This is the model class for table "business_address".
 *
 * @property integer $id
 * @property integer $idBusiness
 * @property double $lat
 * @property double $lon
 * @property string $oldAddress
 * @property string $phone
 * @property string $working_time
 * @property string $street
 * @property string $city
 * @property string $country
 * @property string $address
 */
class BusinessAddress extends ActiveRecordMultiLang
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'business_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idBusiness'], 'required','message' => 'Поле не может быть пустым'],
            [['idBusiness'], 'integer'],
            [['lat', 'lon'], 'number'],
            [[
                'address',
                'oldAddress',
                'working_time',
                'street',
                'city',
                'country',
            ], 'string'],
            [['phone'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idBusiness' => 'Id Business',
            'lat' => 'Lat',
            'lon' => 'Lon',
            'address' => 'Address',
            'oldAddress' => 'Address',
            'phone' => 'Phone',
            'working_time' => 'График работы',
            'street' => 'Улица',
            'city' => 'Город',
            'country' => 'Страна',
        ];
    }
    
    public function translateAttributes()
    {
        return [
            'oldAddress',
            'working_time',
            'street',
            'city',
            'country',
        ];
    }
    
    public function beforeSave($insert) {
       if (parent::beforeSave($insert)) {
            $this->country = 'Украина';
            $this->lat = ($this->lat)? $this->lat : 0;
            $this->lon = ($this->lon)? $this->lon : 0;
           
            return true;
        } else {
            return false;
        }
    }

    public function getAddress(){
        if ($this->street){
            return $this->street;
            //return $this->street . ',' . $this->city . ',' . $this->country;
        } else {
            return $this->oldAddress;
        }
    }
}

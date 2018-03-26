<?php

namespace common\models;

use common\models\traits\GetCityTrait;
use Yii;

/**
 * This is the model class for table "parser_domain".
 *
 * @property integer $id
 * @property integer $idRegion
 * @property integer $idCity
 * @property string $domain
 * @property string $mDomain
 */
class ParserDomain extends \yii\db\ActiveRecord
{
    use GetCityTrait;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parser_domain';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idRegion', 'idCity', 'domain', 'mDomain'], 'required'],
            [['idRegion', 'idCity'], 'integer'],
            [['domain', 'mDomain'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idRegion' => 'Id Region',
            'idCity' => 'Id City',
            'domain' => 'Domain',
            'mDomain' => 'M Domain',
        ];
    }
    
    public function beforeSave($insert) {
       if (parent::beforeSave($insert)) {
           
           $this->domain = $this->clearUrl($this->domain);
           $this->mDomain = $this->clearUrl($this->mDomain);
            return true;
        } else {
            return false;
        }
    }
    
    private function clearUrl($url){
        $arr = explode('//', $url);
        if(!empty($arr[1])){
            return trim($arr[1], ' /');
        }
        return trim($url, ' /');
    }

    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'idRegion']);
    }
}

<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * This is the model class for table "system_log".
 *
 * @property integer $id
 * @property string $dateCreate
 * @property string $description
 * @property string $status
 */
class SystemLog extends ActiveRecord
{
    const STATUS_WARNING = 'warning';
    const STATUS_INFO = 'info';
    const STATUS_ERROR = 'error';

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'value' => new Expression('NOW()'),
                'attributes' => array(
                    ActiveRecord::EVENT_BEFORE_INSERT => 'dateCreate',
                ),
            ],
        ];
    }    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dateCreate'], 'safe'],
            [['description'], 'safe'],
            [['status'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dateCreate' => 'Дата создания',
            'description' => 'Описание',
            'status' => 'Статус'
        ];
    }
    
    public function beforeSave($insert) {
       if (parent::beforeSave($insert)) {
           
            $this->description = Json::encode($this->description);
           
            return true;
        } else {
            return false;
        }
    }
    
    public function afterFind()
    {
        $arr = Json::decode($this->description, true);
        
        $this->description = '';
        foreach ($arr as $key=>$value){
           $this->description .= $key . ': ' . $value . ", "; 
        }
        
    }
}

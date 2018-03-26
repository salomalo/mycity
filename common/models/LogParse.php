<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
/**
 * This is the model class for table "logParse".
 *
 * @property integer $id
 * @property integer $idYandex
 * @property integer $idProduct
 * @property string $message
 * @property integer $isFail
 * @property string $dateCreate
 * @property string $url
 */
class LogParse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'logParse';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['isFail'], 'integer'],
            [['message', 'idProduct', 'url'], 'string'],
            [['dateCreate'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idProduct' => 'Id Product',
            'message' => 'Message',
            'isFail' => 'Is Fail',
            'dateCreate' => 'Date Create',
            'url' => 'Hot Line url'
        ];
    }

    public function behaviors()
    {
        return array(
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'value' => new Expression('NOW()'),
                'attributes' => array(
                    ActiveRecord::EVENT_BEFORE_INSERT => 'dateCreate',
                ),
            ],
        );
    }
}

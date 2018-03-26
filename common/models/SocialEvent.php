<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "social_event".
 *
 * @property integer $id
 * @property integer $idUser
 * @property integer $idLike
 * @property integer $pid
 * @property string $type
 * @property string $typeEvent
 * @property string $dateCreate
 */
class SocialEvent extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'social_event';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idUser', 'idLike', 'pid', 'type', 'typeEvent', 'dateCreate'], 'required','message' => 'Поле не может быть пустым'],
            [['idUser', 'idLike', 'pid'], 'integer'],
            [['type', 'typeEvent'], 'string'],
            [['dateCreate'], 'safe'],
            [['pid', 'type', 'idUser'], 'unique', 'targetAttribute' => ['pid', 'type', 'idUser'], 'message' => 'The combination of Id User, Pid and Type has already been taken.']
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
            'idLike' => 'Id Like',
            'pid' => 'Pid',
            'type' => 'Type',
            'typeEvent' => 'Type Event',
            'dateCreate' => 'Date Create',
        ];
    }
}

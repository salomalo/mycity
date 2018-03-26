<?php

namespace common\models\search;

use Yii;

/**
 * This is the model class for table "account".
 *
 * @property integer $id
 * @property string $nickName
 * @property string $email
 * @property string $password
 * @property integer $type
 * @property string $photoUrl
 * @property string $vkID
 * @property string $vkToken
 * @property string $dateCreate
 */
class SearchAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            [['type'], 'integer'],
            [['dateCreate'], 'safe'],
            [['nickName', 'email', 'password', 'photoUrl', 'vkID', 'vkToken'], 'string', 'max' => 255],
            [['email'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nickName' => 'Nick Name',
            'email' => 'Email',
            'password' => 'Password',
            'type' => 'Type',
            'photoUrl' => 'Photo Url',
            'vkID' => 'Vk ID',
            'vkToken' => 'Vk Token',
            'dateCreate' => 'Date Create',
        ];
    }
}

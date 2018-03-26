<?php

namespace common\models;

use Yii;
use common\models\traits\GetUserTrait;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "friend".
 *
 * @property integer $id
 * @property integer $idUser
 * @property integer $idFriend
 * @property integer $status
 */
class Friend extends ActiveRecord
{
    use GetUserTrait; //public function getUser()

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'friend';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idUser', 'idFriend', 'status'], 'required','message' => 'Поле не может быть пустым'],
            [['idUser', 'idFriend', 'status'], 'integer']
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
            'idFriend' => 'Id Friend',
            'status' => 'Status',
        ];
    }
    
    public function getUserFriend($id)
    {
        $model = User::find()->select(['id', 'username'])->where(['id'=>$id])->one();
        return $model;
    }
}

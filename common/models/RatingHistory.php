<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "rating_history".
 *
 * @property integer $id
 * @property integer $idUser
 * @property integer $idRating
 * @property string $ratio
 */
class RatingHistory extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rating_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idUser', 'idRating', 'ratio'], 'required','message' => 'Поле не может быть пустым'],
            [['idUser', 'idRating'], 'integer'],
            [['ratio'], 'string']
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
            'idRating' => 'Id Rating',
            'ratio' => 'Ratio',
        ];
    }
}

<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "social".
 *
 * @property integer $id
 * @property integer $pid
 * @property string $type
 * @property integer $like
 * @property integer $unlike
 * @property integer $ratingSum
 * @property integer $ratingCount
 * @property string $usersLikeIDs
 * @property string $usersRatingIDs
 */
class Social extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'social';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'type', 'like', 'unlike', 'ratingSum', 'ratingCount'], 'required','message' => 'Поле не может быть пустым'],
            [['pid', 'like', 'unlike', 'ratingSum', 'ratingCount'], 'integer'],
            [['type', 'usersLikeIDs', 'usersRatingIDs'], 'string'],
            [['pid', 'type'], 'unique', 'targetAttribute' => ['pid', 'type'], 'message' => 'The combination of Pid and Type has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'type' => 'Type',
            'like' => 'Like',
            'unlike' => 'Unlike',
            'ratingSum' => 'Rating Sum',
            'ratingCount' => 'Rating Count',
            'usersLikeIDs' => 'Users Like Ids',
            'usersRatingIDs' => 'Users Rating Ids',
        ];
    }
}

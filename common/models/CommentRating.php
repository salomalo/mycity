<?php

namespace common\models;

use yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "comment_rating".
 *
 * @property integer $id
 * @property integer $comment_id
 * @property integer $user_id
 * @property string $ip
 * @property integer $vote
 * @property string $date
 */
class CommentRating extends ActiveRecord
{
    const LIKE = 1;
    const DISLIKE = -1;

    public static $actions = [self::LIKE => 'like', self::DISLIKE => 'unlike'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment_rating';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment_id', 'user_id', 'vote'], 'integer'],
            [['date'], 'safe'],
            [['ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comment_id' => 'Comment ID',
            'user_id' => 'User ID',
            'ip' => 'Ip',
            'vote' => 'Vote',
            'date' => 'Date',
        ];
    }

    /**
     * @param $id integer
     * @return null|self
     */
    public static function getUserRateComment($id)
    {
        $model = self::find()->where(['comment_id' => $id]);
        if (empty(Yii::$app->user->identity)) {
            $model->andWhere(['ip' => $_SERVER['REMOTE_ADDR'], 'user_id' => null]);
        } else {
            $model->andWhere(['user_id' => Yii::$app->user->identity->id]);
        }
        return $model->one();
    }
    
    public function beforeSave($insert)
    {
        $this->ip = $_SERVER['REMOTE_ADDR'];
        if (!empty(Yii::$app->user->identity)) {
            $this->user_id = Yii::$app->user->identity->id;
        }
        $this->date = date('Y-m-d H:i:s');
        return parent::beforeSave($insert);
    }

    /**
     * @return string|null
     */
    public function getAllowAction()
    {
        $action = null;
        switch ($this->vote) {
            case self::LIKE:
                $action = self::DISLIKE;
                break;
            case self::DISLIKE:
                $action = self::LIKE;
                break;
        }
        return ($action and isset(self::$actions[$action])) ? self::$actions[$action] : null;
    }
}

<?php

namespace common\models;

use yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "question".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $conversation_id
 * @property string $text
 * @property string $created_at
 *
 * @property QuestionConversation $conversation
 * @property User $user
 */
class Question extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'conversation_id', 'text'], 'required'],
            [['user_id', 'conversation_id'], 'integer'],
            [['text'], 'string'],
            [['created_at'], 'safe'],
            [['conversation_id'], 'exist', 'skipOnError' => true, 'targetClass' => QuestionConversation::className(), 'targetAttribute' => ['conversation_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('question', 'ID'),
            'user_id' => Yii::t('question', 'User ID'),
            'conversation_id' => Yii::t('question', 'Conversation ID'),
            'text' => Yii::t('question', 'Text'),
            'created_at' => Yii::t('question', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversation()
    {
        return $this->hasOne(QuestionConversation::className(), ['id' => 'conversation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function isOwner($user_id)
    {
        return ($user_id === $this->user_id);
    }
}


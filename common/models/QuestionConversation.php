<?php

namespace common\models;

use yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "question_conversation".
 *
 * @property integer $id
 * @property integer $status
 * @property string $title
 * @property integer $object_type
 * @property string $object_id
 * @property string $created_at
 * @property string $user_id
 *
 * @property Question[] $questions
 * @property User $user
 * @property string $statusLabel
 * @property string $statusLabelHtml
 * @property string $typeLabel
 * @property string $lastQuestionDate
 * @property string $alias
 */
class QuestionConversation extends ActiveRecord
{
    const STATUS_NEW = 1;
    const STATUS_READ = 2;
    const STATUS_REPLIED = 3;
    const STATUS_RECEIVED = 4;
    const STATUS_CLOSED = 5;

    public static $statuses = [
        self::STATUS_NEW => 'В очереди',
        self::STATUS_READ => 'В обработке',
        self::STATUS_REPLIED => 'Есть ответ',
        self::STATUS_RECEIVED => 'Просмотрен',
        self::STATUS_CLOSED => 'Закрыт',
    ];

    public static $statuses_html = [
        self::STATUS_NEW => '<span class="label label-danger">В очереди</span>',
        self::STATUS_READ => '<span class="label label-warning">В обработке</span>',
        self::STATUS_REPLIED => '<span class="label label-success">Есть ответ</span>',
        self::STATUS_RECEIVED => '<span class="label label-info">Просмотрен</span>',
        self::STATUS_CLOSED => '<span>Закрыт</span>',
    ];
    
    public static $object_types = [
        File::TYPE_BUSINESS => 'Предприятие',
        File::TYPE_ADS => 'Объявление',
        File::TYPE_SUPPORT => 'Поддержка',
        File::TYPE_SUPPORT_BUSINESS => 'Поддержка по предприятиям',
        File::TYPE_SUPPORT_ADS => 'Поддержка по объявлениям',
        File::TYPE_SUPPORT_ADVERT => 'Поддержка по рекламе',
    ];

    public static $support_types = [
        File::TYPE_SUPPORT => 'Поддержка',
        File::TYPE_SUPPORT_BUSINESS => 'Поддержка по предприятиям',
        File::TYPE_SUPPORT_ADS => 'Поддержка по объявлениям',
        File::TYPE_SUPPORT_ADVERT => 'Поддержка по рекламе',
    ];

    public static $question_types = [
        File::TYPE_BUSINESS => 'Предприятие',
        File::TYPE_ADS => 'Объявление',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question_conversation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'title', 'object_type', 'user_id'], 'required'],
            [['status', 'object_type', 'user_id', 'owner_id'], 'integer'],
            [['created_at'], 'safe'],
            [['title', 'object_id'], 'string', 'max' => 255],
            [['object_type'], 'checkInArray', 'params' => ['array' => array_keys(self::$object_types)]],
            [['status'], 'checkInArray', 'params' => ['array' => array_keys(self::$statuses)]],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['owner_id' => 'id']],
        ];
    }

    public function checkInArray($attribute, $params)
    {
        if (isset($params['array']) and !in_array($this->$attribute, $params['array'])) {
            $this->addError($attribute, 'Задано неверное значение атрибута.');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('question', 'ID'),
            'status' => Yii::t('question', 'Status'),
            'title' => Yii::t('question', 'Title'),
            'object_type' => Yii::t('question', 'Object Type'),
            'object_id' => Yii::t('question', 'Object ID'),
            'created_at' => Yii::t('question', 'Created At'),
            'user_id' => Yii::t('question', 'User'),
            'owner_id' => Yii::t('question', 'Owner'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::className(), ['conversation_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }

    public function getStatusLabel()
    {
        return isset(self::$statuses[$this->status]) ? self::$statuses[$this->status] : null;
    }

    public function getStatusLabelHtml()
    {
        return isset(self::$statuses_html[$this->status]) ? self::$statuses_html[$this->status] : null;
    }

    public function getTypeLabel()
    {
        return isset(self::$object_types[$this->object_type]) ? self::$object_types[$this->object_type] : null;
    }

    public static function getAll()
    {
        return self::find()->select(['title', 'id'])->indexBy('id')->orderBy('id')->column();
    }

    public function getLastQuestionDate()
    {
        $date = null;
        if ($this->questions) {
            $last = count($this->questions) - 1;
            $date = $this->questions[$last]->created_at;
        }
        return $date;
    }

    public function getAlias()
    {
        switch ($this->object_type) {
            case File::TYPE_BUSINESS:
                return 'business';
            case File::TYPE_ADS:
                return 'ads';
            default:
                return '';
        }
    }
}


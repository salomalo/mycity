<?php

namespace common\models;

use common\models\traits\GetCityTrait;
use yii\db\Expression;
use yii\db\ActiveRecord;
use common\models\traits\GetUserTrait;

/**
 * This is the model class for table "ticket".
 *
 * @property integer $id
 * @property integer $idUser
 * @property integer $idCity
 * @property string $title
 * @property integer $status
 * @property integer $pid
 * @property integer $type
 * @property string $email
 * @property string $dateCreate
 */
class Ticket extends ActiveRecord
{
    use GetUserTrait; //public function getUser()
    use GetCityTrait;

    public $body;

    const STATUS_QUESTION    = 1;
    const STATUS_ANSWER      = 2;
    const STATUS_CLOSED      = 3;
    const STATUS_USERREVIEW  = 4;
    const STATUS_ADMINREVIEW = 5;
    const TYPE_COMMON   = 1;
    const TYPE_COMPANY  = 2;
    const TYPE_ADS      = 3;
    
    public static $statuses = [
        self::STATUS_QUESTION => 'Вопрос',
        self::STATUS_ANSWER   => 'Ответ',
        self::STATUS_CLOSED   => 'Закрыт',
        self::STATUS_USERREVIEW   => 'Прочитано пользователем',
        self::STATUS_ADMINREVIEW   => 'Обрабатывается'
     ];
    
    public static $types = [
        self::TYPE_COMMON => 'Общий',
        self::TYPE_COMPANY   => 'Компании',
        self::TYPE_ADS   => 'Объявления'
     ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ticket';
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['idUser', 'title', 'status', 'email'], 'required'], //'body'
            [['idUser', 'title','type'], 'required','message' => 'Поле не может быть пустым'],
            [['idUser', 'status','type', 'idCity'], 'integer'],
            [['dateCreate', 'status','type','pid'], 'safe'],
            [['title'], 'string', 'max' => 255],
            ['email', 'email'],
            [['body'], 'string'],
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
            'idCity' => 'Город',
            'title' => 'Title',
            'status' => 'Status',
            'email' => 'Email',
            'type' => 'Type',
            'pid' => 'Pid',
            'dateCreate' => 'Date Create',
            'body' => 'Text'
        ];
    }
    
    public function beforeSave($insert) {
       if (parent::beforeSave($insert)) {
            if ($this->isNewRecord)
            {
                $this->status = self::STATUS_QUESTION; 
                
                if($this->idUser != 0){
                    $user = User::find()->select('email')->where(['id'=>  $this->idUser])->one();
                    $this->email = $user->email;
                }
                
            }
            return true;
        } else {
            return false;
        }
    }
    
    public function afterSave($insert, $changedAttributes = null) {
         
        $history = new TicketHistory();
        $history->idTicket =   $this->id;
        $history->body =       $this->body;
        $history->idUser =     $this->idUser;
        $history->dateCreate = $this->dateCreate;
        $history->save();                
          
        parent::afterSave($insert, $changedAttributes);
     }
}

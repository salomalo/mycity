<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Description of TicketForm
 *
 * @author dima
 */
class TicketForm extends Model
{
    public $idUser;
    public $idCity;
    public $title;
    public $email;
    public $body;
    public $verifyCode;
    public $type;
    
    public function rules()
    {
        return [
            [['idUser', 'title', 'email',  'body', 'type', 'idCity'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            [['body'], 'string'],
            [['type', 'idCity'], 'integer'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type' => 'Тип вопроса',
            'idCity' => 'Город',
            'title' => 'Заголовок',
            'email' => 'E-mail',
            'body' => 'Текст вопроса',
            'verifyCode' => 'Проверочный код',
        ];
    }
    
}

<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Description of SuggestionsForm
 *
 * @author dima
 */
class SuggestionsForm extends Model {
    
    public $idCity;
    public $name;
    public $email;
    public $text;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text', 'name', 'idCity'], 'required'],
            [['idCity'], 'integer'],
            ['name', 'string', 'min' => 2, 'max' => 255],
            ['email', 'email','message' => Yii::t('app', 'Wrong_format')],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idCity' => 'Город',
            'name' => 'Имя',
            'email' => 'Email',
            'text' => 'Текст',
        ];
    }
    
     /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
//    public function sendEmail()
//    {
//        
//        if ($this->text) {
//            return \Yii::$app->mail->compose('suggestions', ['text' => $this->text])
//                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
//                ->setTo($this->email)
//                ->setSubject('Жалобы и предложения ' . \Yii::$app->name)
//                ->setTextBody('Активация регистрации')
//                ->send();
//        }
//
//        return false;
//    }
    
    public function sendEmail($model)
    {
        $mail = ($model->email)? $model->email : Yii::$app->params['contactEmail'];
        $name = ($model->name)? $model->name : Yii::$app->name;
       
        return Yii::$app->mail->compose(
            ['html' => 'suggestion'],
            ['name' => $model->name, 'text' => $model->text]
        )
        ->setTo(Yii::$app->params['contactEmail'])
        ->setFrom([$mail => $name])
        ->setSubject(Yii::t('app', 'Complaints_and_suggestions'))
//            ->setTextBody($this->body)
        ->send();
       
    }
    
}

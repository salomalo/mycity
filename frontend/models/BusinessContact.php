<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BusinessContact
 *
 * @author dima
 */
class BusinessContact extends Model
{
    public $name;
    public $tel;
    public $email;
    public $body;
    //public $verifyCode;
    
    public function rules()
    {
        return [
            [['name', 'tel', 'email',  'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            [['body'], 'string'],
            [['name', 'tel'], 'string', 'max' => 100],
            // verifyCode needs to be entered correctly
            //['verifyCode', 'captcha'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            //'verifyCode' => 'Verification Code',
            'name' => 'Имя',
            'tel' => 'Телефон',
            'email' => 'Email',
            'body' => 'Текст'
        ];
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 05.12.2016
 * Time: 22:45
 */

namespace office\models;

use dektrium\user\models\RecoveryForm as Form;
use dektrium\user\models\Token;

class RecoveryForm extends Form
{
    public $password2;

    public function rules()
    {
        $rules = parent::rules();

        $rules['password2Required'] = ['password2', 'required'];
        $rules['password2Length'] = ['password2', 'string', 'max' => 72, 'min' => 6];
        $rules[] =  ['password2', 'compare', 'message' => 'Пароли не совпадают.', 'compareAttribute'=>'password'];
        return $rules;
    }

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['password2' ] = \Yii::t('user', 'Password');

        return $attributeLabels;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_RESET ] = ['password', 'password2'];

        return $scenarios;
    }
}
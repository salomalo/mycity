<?php

namespace common\models;

use yii;
use dektrium\user\models\RegistrationForm as BaseRegistrationForm;

class RegistrationForm extends BaseRegistrationForm{

    public $password2;
    public $apply;
    public $phone;

    public function rules()
    {
        $rules = parent::rules();

        unset($rules['usernameTrim']);
        unset($rules['usernameUnique']);
        unset($rules['usernamePattern']);

        $rules['usernameLength'] = ['username', 'string'];
        $rules['password2Required'] = ['password2', 'required','message' => 'Необходимо заполнить «Пароль»', 'skipOnEmpty' => $this->module->enableGeneratingPassword];
        $rules[] =  ['password2', 'compare', 'message' => 'Пароли не совпадают.', 'compareAttribute'=>'password'];
        $rules['applyRequired'] = ['apply', 'required', 'requiredValue' => 1, 'message' => Yii::t('app', 'Error_required_apply')];
        $rules['phoneSafe'] = ['phone', 'safe'];
        return $rules;
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        return $labels;
    }

    public function register($tariff = null)
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var User $user */
        $user = Yii::createObject(User::className());

        $user->phone = $this->phone ? $this->phone : null;

        $user->setScenario('register');

        $this->loadAttributes($user);

        if (!$user->register($tariff)) {
            return false;
        }

        return true;
    }

    public function registerLanding()
    {
        if (!$this->validate()) {
            return false;
        }
        /** @var User $user */
        $user = Yii::createObject(User::className());
        $user->phone = $this->phone ? $this->phone : null;
        $user->setScenario('register');
        $this->loadAttributes($user);

        if (!$user->registerLanding()) {
            return false;
        }

        return true;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 03.10.2016
 * Time: 10:21
 */

namespace common\models;


use common\models\User;
use dektrium\user\helpers\Password;
use dektrium\user\models\LoginForm;
use Yii;

class SigninForm extends LoginForm
{
    /** @inheritdoc */
    public function rules()
    {
        return [
            'requiredFields' => [['login', 'password'], 'required'],
            'loginTrim' => ['login', 'trim'],
            'passwordValidate' => [
                'password',
                function ($attribute) {
                    if ($this->user === null){

                    } else if (!Password::validate($this->password, $this->user->password_hash)) {
                        $this->addError($attribute, Yii::t('user', 'Invalid login or password'));
                    }
                }
            ],
            'confirmationValidate' => [
                'login',
                function ($attribute) {
                    $userFind = $this->login ? User::findByEmail($this->login) : null;
                    if ($this->user !== null && $userFind) {
                        $confirmationRequired = $this->module->enableConfirmation
                            && !$this->module->enableUnconfirmedLogin;
                        if ($confirmationRequired && !$this->user->getIsConfirmed()) {
                            $this->addError($attribute, Yii::t('user', 'You need to confirm your email address'));
                        }
                        if ($this->user->getIsBlocked()) {
                            $this->addError($attribute, Yii::t('user', 'Your account has been blocked'));
                        }
                    } else {
                        $this->addError($attribute, Yii::t('user', 'User is not found'));
                    }
                }
            ],
            'rememberMe' => ['rememberMe', 'boolean'],
        ];
    }

    public function login()
    {
        if ($this->validate()) {
            if (User::findByEmail($this->login)) {
                return Yii::$app->getUser()->login($this->user, $this->rememberMe ? $this->module->rememberFor : 0);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
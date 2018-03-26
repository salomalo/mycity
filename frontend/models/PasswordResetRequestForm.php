<?php
namespace frontend\models;

use Yii;
use common\models\User;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                //'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => Yii::t('app', 'There_is_no_user_with_such_email')
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /** @var User $user */
        $user = User::findOne([
            //'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if ($user) {
            $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ1234567890#@%&';
            $numChars = strlen($chars);
            $password = '';
            for ($i = 0; $i < 8; $i++) {
                $password .= substr($chars, rand(1, $numChars) - 1, 1);
            }        
            $user->setPassword($password);
            if ($user->save()) {
                return \Yii::$app->mail->compose('passwordResetToken', ['user' => $user,'password'=>$password])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                    ->setTo($this->email)
                    ->setSubject('Сброс пароля для ' . \Yii::$app->name)
                    ->send();
            }
        }

        return false;
    }
}

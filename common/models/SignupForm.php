<?php
namespace common\models;

use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password2;
    public $type;
    public $photoUrl;
    public $vkID;
    public $vkToken;
    public $birthday;
    public $description;
    public $created_at;
    public $isNewRecord = true;
    public $oldAttributes = [];
    public $apply;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            //['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Это имя пользователя уже занято.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Этот адрес электронной почты уже занято.'],

            [['password', 'password2'], 'required'],
            ['password2', 'compare', 'message' => 'Пароли не совпадают.', 'compareAttribute'=>'password'],
            [['password', 'password2'], 'string', 'min' => 5],
            
            [['type', 'apply'], 'integer'],
            [['created_at', 'birthday'], 'safe'],
            [['description'], 'string'],
            [['photoUrl', 'vkID', 'vkToken'], 'string', 'max' => 255],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $this->email = mb_strtolower($this->email);
            return User::create($this->attributes);
        }

        return null;
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Nick Name',
            'email' => 'Email',
            'password' => 'Пароль',
            'password2' => 'Подтверждение пароля',
            'type' => 'Type',
            'photoUrl' => 'Photo Url',
            'vkID' => 'Vk ID',
            'vkToken' => 'Vk Token',
            'birthday' => 'Birthday',
            'description' => 'Description',
            'dateCreate' => 'Date Create',
            'apply' => 'Принимаю условия',
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
            'status' => User::STATUS_SIGNUP,
            'email' => $this->email,
        ]);

        if ($user) {
            return \Yii::$app->mail->compose('accountActivate', ['user' => $user])
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                ->setTo($this->email)
                ->setSubject('Активация регистрации для ' . \Yii::$app->name)
                ->setTextBody('Активация регистрации')
                ->send();
        }

        return false;
    }
}

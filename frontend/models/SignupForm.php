<?php
namespace frontend\models;

use common\models\User;
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
            ['username', 'required','message' => 'Поле не может быть пустым'],
            //['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Это имя пользователя уже занято.'],
            ['username', 'string', 'min' => 2, 'max' => 255, 'message' => 'Длина имени должна быть от 2 до 255 символов.'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required','message' => 'Поле не может быть пустым'],
            ['email', 'email','message' => 'Не правильный формат'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Этот адрес электронной почты уже занят.'],

            [['password', 'password2'], 'required','message' => 'Поле не может быть пустым'],
            ['password2', 'compare', 'message' => 'Пароли не совпадают.', 'compareAttribute'=>'password'],
            [['password', 'password2'], 'string', 'min' => 5,'message' => 'Длина не менее 5 ти символов'],
            [ 'apply', 'required','message' => 'Вы должны принять условия пользовательского соглашения'],
            [['type'], 'integer'],
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
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->photoUrl = $this->photoUrl;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->save();
            return $user;
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
            'username' => 'Логин',
            'email' => 'Email',
            'password' => 'Пароль',
            'password2' => 'Подтверждение пароля',
            'type' => 'Type',
            'photoUrl' => 'Фото',
            'vkID' => 'Vk ID',
            'vkToken' => 'Vk Token',
            'birthday' => 'День рождения',
            'description' => 'Описание',
            'dateCreate' => 'Дата создания',
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

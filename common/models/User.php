<?php
namespace common\models;

use dektrium\user\helpers\Password;
use dektrium\user\models\Token;
use dektrium\user\models\User as BaseUser;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;

/**
 * @property SocialAccount[] social_account
 * @property int role
 * @property string existName
 * @property string $last_activity
 * @property integer $city_id
 * @property City $city
 * @property Profile $profile
 */
class User extends BaseUser implements IdentityInterface
{
    const TYPE_FRIEND_INVITED = 0;
    const TYPE_FRIEND_CONFIRMED = 1;
    const TYPE_FRIEND_REMOVED = 2;
    
    const STATUS_DELETED = 0;
    const STATUS_SIGNUP = 1;
    const STATUS_ACTIVE = 10;
    
    const ROLE_EDITOR = 5;
    const ROLE_USER = 10;
    
    const SCENARIO_BACKEND_UPDATE = 'update_backend';
    const SCENARIO_BACKEND_CREATE = 'create_backend';
    const SCENARIO_REGISTER = 'register';

    public static $statusList = [
        self::STATUS_DELETED => 'Удалён',
        self::STATUS_SIGNUP => 'Не активирован',
        self::STATUS_ACTIVE => 'Активирован',
    ];
    public static $roles = [
        self::ROLE_EDITOR => 'Модератор',
        self::ROLE_USER => 'Пользователь',
    ];
    public static $bScenarios = [
        self::SCENARIO_BACKEND_UPDATE => true,
        self::SCENARIO_BACKEND_CREATE => true,
    ];
    public static $types = [
        self::TYPE_FRIEND_INVITED => 'Приглашён',
        self::TYPE_FRIEND_CONFIRMED => 'В друзьях',
        self::TYPE_FRIEND_REMOVED => 'Удалён'
    ];

    public $photoUrl = '';
    public $ip = '';
    public $name;
    public $location;
    public $public_email;
    public $website;
    public $bio;

    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        $rules = parent::rules();

        unset($rules['usernameMatch']);
        unset($rules['usernameUnique']);

        $rules['usernameLength'] = ['username', 'string'];
        $rules['password2Length'] = $rules['passwordLength'];
        $rules['password2Required'] = $rules['passwordRequired'];
        $rules[] = [['public_email'], 'email'];
        $rules[] = [['name', 'location', 'website', 'bio'], 'string'];
        $rules[] = [['role', 'level'], 'integer'];
        $rules[] = [['last_activity', 'phone'], 'safe'];
        $rules[] = [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']];

        return $rules;
    }
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['default'] = $scenarios['update'];
        $scenarios['create_backend'] = [
            'id', 'username', 'email', 'password_hash', 'confirmed_at', 'role', 'city_id',
            'name', 'location', 'public_email', 'website', 'bio',
        ];
        $scenarios['update_backend'] = [
            'username', 'email', 'password_hash', 'confirmed_at', 'role', 'city_id',
            'name', 'location', 'public_email', 'website', 'bio',
        ];

        return $scenarios;
    }
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['id']                   = 'ID';
        $labels['username']             = 'Имя пользователя';
        $labels['email']                = 'E-mail';
        $labels['password_hash']        = 'Пароль';
        $labels['type']                 = 'Тип';
        $labels['status']               = 'Статус';
        $labels['photoUrl']             = 'Фото';
        $labels['vkID']                 = 'Vk ID';
        $labels['vkToken']              = 'Vk Token';
        $labels['birthday']             = 'День рождения';
        $labels['description']          = 'Описание';
        $labels['confirmed_at']         = 'Подтвержден';
        $labels['created_at']           = 'Регистрация';
        $labels['role']                 = 'Роль';
        $labels['ip']                   = 'Ip';
        $labels['apply']                = 'условия пользовательского соглашения';
        $labels['public_email']         = 'Публичный E-mail';
        $labels['name']                 = 'ФИО';
        $labels['location']             = 'Адрес';
        $labels['website']              = 'Сайт';
        $labels['bio']                  = 'О себе';
        $labels['last_activity']        = 'Последняя активность';
        $labels['city_id']              = 'Город';
        $labels['phone']                = 'Телефон';

        return $labels;
    }
    public function beforeSave($insert)
    {
        $this->username = strip_tags($this->username);
        $this->role = (!$this->role) ? self::ROLE_USER : $this->role;

        if (parent::beforeSave($insert)) {
            return true;
        } else {
            return false;
        }
    }
    public function afterSave($insert, $changedAttributes = null)
    {
        $file = File::find()->where(['name' => $this->photoUrl])->one();
        if ($file) {
            $file->pid = $this->id;
            $file->update();
        }

        if (isset(self::$bScenarios[$this->getScenario()]) and self::$bScenarios[$this->getScenario()]) {
            $pModel = Profile::findOne(['user_id' => $this->id]);
            $pModel = ($pModel) ? $pModel : (new Profile());

            $pModel->user_id = $this->id;
            $pModel->name = $this->name;
            $pModel->location = $this->location;
            $pModel->public_email = $this->public_email;
            $pModel->website = $this->website;
            $pModel->bio = $this->bio;
            $pModel->save();
        }
    }
    public function afterDelete()
    {
        #Получаем и удаляем объект Профиля
        if ($pModel = Profile::findOne(['user_id' => $this->id])) {
            $pModel->delete();
        }
        parent::afterDelete();
    }
    public function bindProfile()
    {
        $this->name = isset($this->profile->name) ? $this->profile->name : '';
        $this->location = isset($this->profile->location) ? $this->profile->location : '';
        $this->public_email = isset($this->profile->public_email) ? $this->profile->public_email : '';
        $this->website = isset($this->profile->website) ? $this->profile->website : '';
        $this->bio = isset($this->profile->bio) ? $this->profile->bio : '';

        return $this;
    }
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    public function getSocial_account()
    {
        return $this->hasMany(SocialAccount::className(), ['user_id' => 'id']);
    }
    public function getProfile()
    {
        return $this->hasOne(Profile::className(),['user_id' => 'id']);
    }
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    public function getExistName()
    {
        //1. Try Profile->name
        $return = isset($this->profile->name) ? (string)$this->profile->name : '';

        if (!$return and is_array($this->social_account) and $this->social_account) {
            //2. Try SocialProfile->username or SocialProfile->data['name\first_name']
            foreach ($this->social_account as $profile) {
                $return = $profile->username ? $profile->username : ($profile->socialName ? $profile->socialName : '');
                if ($return) {
                    break;
                }
            }
        } elseif (!$return) {
            //3. Try User->username
            $return = isset($this->username) ? (string)$this->username : '';
        }
        return $return;
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token, 'role' => self::ROLE_EDITOR]);
        //throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public static function findByEmail($email)
    {
        return static::findOne([
            'email' => $email
        ]);
    }

    public static function findByPasswordResetToken($token)
    {
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }
    public static function getRole($login)
    {
        return User::find()->where(['username' => $login])->select('role')->one();
    }
    public static function getAll()
    {
        return ArrayHelper::map(self::find()->select(['id','username'])->all(),'id','username');
    }
    public function checkCity()
    {
        return ($this->city_id !== null) ? true : false;
    }
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    public function registerLanding()
    {
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        $transaction = $this->getDb()->beginTransaction();

        try {
            $this->confirmed_at = time();

            $this->trigger(self::BEFORE_REGISTER);

            if (!$this->save()) {
                $transaction->rollBack();
                return false;
            }

            /** @var \yii\mail\BaseMailer $mailer */
            $mailer = Yii::$app->mailer;
            $mailer->viewPath = Yii::$app->viewPath . '/user/mail';
            $mailer->getView()->theme = Yii::$app->view->theme;

            if ($this->module->enableConfirmation) {
                /** @var Token $token */
                $token = \Yii::createObject(['class' => Token::className(), 'type' => Token::TYPE_CONFIRMATION]);
                $token->link('user', $this);
            }

            $url = Yii::$app->urlManagerOffice->createUrl(['/user/confirm'], true);
            $url = $url . '/' . $token->user_id . '/' . $token->code . '?redirect_url=' . Yii::$app->urlManagerOffice->createUrl(['/business/pay'], true);

            $mailer->compose(
                ['html' => 'welcome-landing', 'text' => 'text/welcome-landing'],
                ['user' => $this, 'url' => $url, 'module' => $this->module]
            )
                ->setTo($this->email)
                ->setFrom('noreply@citylife.info')
                ->setSubject(Yii::t('user', 'Welcome to {0}', Yii::$app->name))
                ->send();
            
            $this->trigger(self::AFTER_REGISTER);

            $transaction->commit();

            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * This method is used to register new user account. If Module::enableConfirmation is set true, this method
     * will generate new confirmation token and use mailer to send it to the user.
     *
     * @return bool
     */
    public function register($tariff = null)
    {
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        $transaction = $this->getDb()->beginTransaction();

        try {
            $this->confirmed_at = $this->module->enableConfirmation ? null : time();
            $this->password     = $this->module->enableGeneratingPassword ? Password::generate(8) : $this->password;

            $this->trigger(self::BEFORE_REGISTER);

            if (!$this->save()) {
                $transaction->rollBack();
                return false;
            }

            /** @var \yii\mail\BaseMailer $mailer */
            $mailer = Yii::$app->mailer;
            $mailer->viewPath = Yii::$app->viewPath . '/user/mail';
            $mailer->getView()->theme = Yii::$app->view->theme;

            if ($this->module->enableConfirmation) {
                /** @var Token $token */
                $token = \Yii::createObject(['class' => Token::className(), 'type' => Token::TYPE_CONFIRMATION]);
                $token->link('user', $this);
            }

            $token = isset($token) ? $token : null;
            $url = $token->url;

            if ($tariff){
                $url = $url . '?redirect_url=' . Yii::$app->urlManagerOffice->createUrl(['/business/create', 'tariff' => $tariff], true);
            } else{
                $url = $url . '?redirect_url=' . Yii::$app->urlManagerOffice->createUrl(['/'], true);
            }


            $mailer->compose(
                ['html' => 'welcome-landing', 'text' => 'text/welcome-landing'],
                ['user' => $this, 'url' => $url, 'module' => $this->module]
            )
                ->setTo($this->email)
                ->setFrom('noreply@citylife.info')
                ->setSubject(Yii::t('user', 'Welcome to {0}', 'CityLife'))
                ->send();

            //$this->mailer->sendWelcomeMessage($this, isset($token) ? $token : null);
            $this->trigger(self::AFTER_REGISTER);

            $transaction->commit();

            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRegInfo()
    {
        return $this->hasOne(UserRegInfo::className(), ['user_id' => 'id']);
    }

    public function getUserUtmSource(){
        $userRegInfo = \common\models\UserRegInfo::find()->where(['user_id' => $this->id])->one();
        if (isset($userRegInfo->utm_source)){
            return $userRegInfo->utm_source;
        } else {
            return '';
        }
    }

    public function getUserUtmCampaing(){
        $userRegInfo = \common\models\UserRegInfo::find()->where(['user_id' => $this->id])->one();
        if (isset($userRegInfo->utm_source)){
            return $userRegInfo->utm_campaing;
        } else {
            return '';
        }
    }
}

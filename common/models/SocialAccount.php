<?php

namespace common\models;

use common\models\traits\GetUserTrait;
use yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "social_account".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $provider
 * @property string $client_id
 * @property string $data
 * @property string $code
 * @property integer $created_at
 * @property string $email
 * @property string $username
 * @property string $socialName
 * @property string $url
 * @property string $icon
 *
 * @property User $user
 */
class SocialAccount extends ActiveRecord
{
    use GetUserTrait;

    public static function tableName()
    {
        return 'social_account';
    }

    public function rules()
    {
        return [
            [['user_id', 'created_at'], 'integer'],
            [['provider', 'client_id'], 'required'],
            [['data'], 'string'],
            [['provider', 'client_id', 'email', 'username'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 32],
            [['provider', 'client_id'], 'unique', 'targetAttribute' => ['provider', 'client_id'], 'message' => 'The combination of Provider and Client ID has already been taken.'],
            [['code'], 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'provider' => 'Provider',
            'client_id' => 'Client ID',
            'data' => 'Data',
            'code' => 'Code',
            'created_at' => 'Created At',
            'email' => 'Email',
            'username' => 'Username',
        ];
    }

    public static function findModel($id)
    {
        return SocialAccount::find()->where(['id' => $id])->one();
    }

    public function getSocialName()
    {
        $return = '';
        $json = json_decode($this->data);
        switch ($this->provider) {
            case 'vkontakte':
                if (isset($json->first_name)) $return = $json->first_name;
                break;
            case 'facebook':
                if (isset($json->name)) $return = $json->name;
                break;
            case 'twitter':
                if (isset($json->name)) $return = $json->name;
                break;
        }
        return $return;
    }
    
    public function getUrl()
    {
        switch ($this->provider) {
            case 'vkontakte':
                return "http:://vk.com/id{$this->client_id}";
            case 'facebook':
                return "http:://www.facebook.com/profile.php?id={$this->client_id}";
            case 'twitter':
                return "http:://twitter.com/account/redirect_by_id?id={$this->client_id}";
            default:
                return null;
        }
    }

    public function getIcon()
    {
        switch ($this->provider) {
            case 'vkontakte':
                return '/img/icons/vk.png';
            case 'facebook':
                return '/img/icons/fb.png';
            case 'twitter':
                return '/img/icons/tw.png';
            default:
                return null;
        }
    }
}

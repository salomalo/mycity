<?php

namespace common\models;

use common\models\traits\GetUserTrait;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * This is the model class for table "comment".
 *
 * @property integer $id
 * @property integer $idUser
 * @property string $text
 * @property integer $type
 * @property integer $pid
 * @property string $pidMongo
 * @property integer $parentId
 * @property integer $rating
 * @property integer $ratingCount 
 * @property string $lastIpLike
 * @property string $lastIpRating
 * @property integer $like
 * @property integer $unlike
 * @property string $dateCreate
 * @property User $user
 * @property CommentRating $userRating
 * @property Business|Post|Ads|Product|Afisha|WorkResume|Gallery $entity
 * @property string $entityUrl
 * @property integer $rating_business
 * @property integer $business_type
 * @property integer $correct_price
 * @property integer $product_availability
 * @property integer $correct_description
 * @property integer $order_executed_on_time
 * @property integer $rating_callback
 * @property integer $rating_recommend
 */
class Comment extends ActiveRecord
{
    use GetUserTrait;

    const RATING_BUSINESS_VERY_BAD = 1;
    const RATING_BUSINESS_BAD = 2;
    const RATING_BUSINESS_NEUTRALLY = 3;
    const RATING_BUSINESS_GOOD = 4;
    const RATING_BUSINESS_VERY_GOOD = 5;

    const RATING_DO_NOT_REMEMBER = 1;
    const RATING_YES = 2;
    const RATING_NO = 3;

    const RECOMMEND_COMPANY_MAYBE = 1;
    const RECOMMEND_COMPANY_YES = 2;
    const RECOMMEND_COMPANY_NO = 3;

    const CALLBACK_DEFAULT = 1;
    const CALLBACK_MINUTE = 2;
    const CALLBACK_HOUR = 3;
    const CALLBACK_DAY = 4;
    const CALLBACK_NEXT_DAY = 5;
    const CALLBACK_FEW_DAY = 6;
    const CALLBACK_NO = 7;
    const CALLBACK_MYSELF = 8;

    const TYPE_COMMENT_DEFAULT = 1;
    const TYPE_COMMENT_SHOP = 2;

    public static $typesRecommend = [
        self::RECOMMEND_COMPANY_MAYBE => 'Не уверен',
        self::RECOMMEND_COMPANY_YES => 'Да',
        self::RECOMMEND_COMPANY_NO => 'Нет',
    ];

    public static $typesCallback = [
        self::CALLBACK_DEFAULT => 'Не помню',
        self::CALLBACK_MINUTE => 'В течение 30 минут',
        self::CALLBACK_HOUR => 'В течение 2 часов',
        self::CALLBACK_DAY => 'В течение дня',
        self::CALLBACK_NEXT_DAY => 'На следующий день',
        self::CALLBACK_FEW_DAY => 'Спустя несколько дней',
        self::CALLBACK_NO => 'Не связались',
        self::CALLBACK_MYSELF => 'Я звонил сам',
    ];

    public static $typesRatingBusiness = [
        self::RATING_BUSINESS_VERY_GOOD  => 'Отлично',
        self::RATING_BUSINESS_GOOD       => 'Хорошо',
        self::RATING_BUSINESS_NEUTRALLY  => 'Нейтрально',
        self::RATING_BUSINESS_BAD        => 'Плохо',
        self::RATING_BUSINESS_VERY_BAD   => 'Очень плохо',
    ];

    public static $typesRatingProperty = [
        self::RATING_DO_NOT_REMEMBER => 'Не помню',
        self::RATING_YES => 'Да',
        self::RATING_NO => 'Нет',
    ];

    public static $typesComment = [
        self::TYPE_COMMENT_DEFAULT => 'По умолчанию',
        self::TYPE_COMMENT_SHOP => 'Интернет магазин',
    ];

    public static $types = [
        File::TYPE_BUSINESS => 'Предприятия', 
        File::TYPE_POST     => 'Новости', 
        File::TYPE_RESUME   => 'Резюме', 
        File::TYPE_ADS      => 'Объявления',
        File::TYPE_AFISHA   => 'Афиша',
        File::TYPE_PRODUCT  => 'Продукты',
        File::TYPE_WORK_VACANTION  => 'Вакансии',
        File::TYPE_ACTION   => 'Акции',
    ];
    public static $urls = [
        File::TYPE_BUSINESS => '/business',
        File::TYPE_POST     => '/post',
        File::TYPE_RESUME   => '/work-resume',
        File::TYPE_ADS      => '/ads',
        File::TYPE_AFISHA   => '/afisha',
        File::TYPE_PRODUCT  => '/product',
        File::TYPE_WORK_VACANTION  => '/work-vacantion',
        File::TYPE_ACTION   => '/action',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idUser', 'text', 'type'], 'required','message' => 'Поле не может быть пустым'],
            [['idUser', 'type', 'pid', 'parentId', 'ratingCount', 'like', 'unlike'], 'integer'],
            [['text', 'pidMongo'], 'string'],
            [['rating'], 'number'],
            [['lastIpLike', 'lastIpRating'], 'string', 'max' => 15],
            [['dateCreate', 'pidMongo'], 'safe'],
            [['rating_business', 'business_type', 'correct_price', 'product_availability', 'correct_description', 'order_executed_on_time', 'rating_callback', 'rating_recommend'] , 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idUser' => 'Автор',
            'text' => 'Текст',
            'type' => 'Тип',
            'pid' => 'Pid',
            'parentId' => 'Parent ID',
            'rating' => 'Rating',
            'ratingCount' => 'Rating Count', 
            'lastIpRating' => 'Last Ip Rating', 
            'lastIpLike' => 'Last Ip Like', 
            'like' => 'Like',
            'unlike' => 'Unlike',
            'dateCreate' => 'Date Create',
            'pidMongo' => 'PidMongo',
            'rating_business' => 'Общая оценка компании',
            'business_type' => 'Тип предприятия',
            'correct_price' => 'Оценка коректности цены',
            'product_availability' => 'Оценка наличия товара',
            'correct_description' => 'Оценка описания товара',
            'order_executed_on_time' => 'Оценка выполнения заказа в сроки',
            'rating_callback' => 'Оценка как быстро связались',
            'rating_recommend' => 'Рекомендовали бы вы компанию',
        ];
    }
    
    public function getChildrens(){
        return $this->hasMany(self::className(), ['parentId' => 'id'])->orderBy('id DESC');
    }

    public function getType($type)
    {
        return self::$types[$type] ;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->text = strip_tags($this->text);
            if ($this->isNewRecord) {
                $this->dateCreate = date('Y-m-d H:i:s');
            }
            return true;
        } else {
            return false;
        }
    }
    
    public function beforeDelete()
    {   
        if (parent::beforeDelete()) {
            self::deleteAll(['parentId'=>$this->id]);
            CommentRating::deleteAll(['comment_id' => $this->id]);
        return true;
        } else {
            return false;
        }
    }

    public function getUserRating()
    {
        return CommentRating::getUserRateComment($this->id);
    }

    public function getBackendUrlForPid()
    {
        if ($this->pid) {
            $id = $this->pid;
        } elseif ($this->pidMongo) {
            $id = $this->pidMongo;
        } else {
            $id = null;
        }
        return Url::to([self::$urls[$this->type] . '/view', 'id' => $id]);
    }

    public function getBackendUrlForType()
    {
        return Url::to([self::$urls[$this->type] . '/index']);
    }

    public function getEntity()
    {
        $models = [
            File::TYPE_BUSINESS => Business::className(),
            File::TYPE_POST     => Post::className(),
            File::TYPE_RESUME   => WorkResume::className(),
            File::TYPE_ADS      => Ads::className(),
            File::TYPE_PRODUCT  => Product::className(),
            File::TYPE_AFISHA   => Afisha::className(),
            File::TYPE_WORK_VACANTION => WorkVacantion::className(),
            File::TYPE_ACTION   => Action::className(),
        ];
        $attr = $this->pidMongo ? ['_id' => 'pidMongo'] : ['id' => 'pid'];

        return $this->hasOne($models[$this->type], $attr);
    }

    public function getEntityUrl()
    {
        if ($model = $this->entity) {
            $front = Yii::$app->params['appFrontend'];
            return "http://{$model->getSubDomain()}{$front}{$model->getFrontendUrl()}#comments";
        }
        return '';
    }
}

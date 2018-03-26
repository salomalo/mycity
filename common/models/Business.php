<?php
namespace common\models;

use common\behaviors\ImageUpload;
use common\components\ActiveRecordMultiLang;
use common\components\LiqPay\LiqPayCurrency;
use common\components\LiqPay\LiqPayStatuses;
use common\components\LiqPay\models\Order;
use common\models\queries\BusinessQuery;
use common\models\traits\GetUserTrait;
use DateTime;
use yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use common\models\traits\GetCityTrait;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "business".
 *
 * @property integer $id
 * @property string $title
 * @property integer $idUser
 * @property integer[] $idCategories
 * @property integer $idCity
 * @property integer $type
 * @property integer $price_type
 * @property string $description
 * @property string $shortDescription
 * @property string $skype
 * @property string $phone
 * @property string $urlVK
 * @property string $urlFB
 * @property string $urlTwitter
 * @property string $email
 * @property string $image
 * @property string $background_image
 * @property integer[] $idProductCategories
 * @property integer $isChecked
 * @property integer $ratio
 * @property string $dateCreate
 * @property string $tags
 * @property string $url
 * @property string $due_date
 * @property string $sitemap_en
 * @property string $sitemap_priority
 * @property string $sitemap_changefreq
 * @property City $city
 * @property BusinessAddress[] $address
 * @property string $prices
 * @property string $vacantions
 * @property string $seo_description
 * @property string $seo_keywords
 * @property string $seo_title
 * @property integer $total_rating
 * @property integer $quantity_rating
 * @property float $rating
 * @property string $trailer
 * @property int $template_id
 *
 * @property int $commentsCount
 * @property int $isPaid
 * @property int $status
 * @property int $isActive use free template or selected
 * @property BusinessTime[] $times
 * @property BusinessCategory[] $businessCategories
 * @property BusinessCustomFieldValue[] $customFieldValues
 * @property CountViews $countView
 * @property User $user
 * @property Ads[] $ads
 * @property Action[] $actions
 * @property ScheduleKino[] $scheduleKino
 * @property string $site
 * @property UserPaymentType[] $userPaymentType
 * @property Afisha[] $afisha
 * @property Action[] $activeAction
 * @property Afisha[] $activeAfisha
 * @property integer $cinema_id
 * @property integer $status_paid
 * @property string $short_url
 * @property BusinessTime[] $businessTimes
 * @property BusinessTemplate $template
 */
class Business extends ActiveRecordMultiLang
{
    use GetCityTrait;
    use GetUserTrait;

    const TYPE_KINOTHEATER = 1;
    const TYPE_OTHER = 2;
    const TYPE_SHOP = 3;

    const SORT_VIEWS_ASC = 'views';
    const SORT_VIEWS_DESC = '-views';
    const SORT_RATING_ASC = 'rating';
    const SORT_RATING_DESC = '-rating';

    const PRICE_TYPE_FREE = 1;
    const PRICE_TYPE_SIMPLE = 2;
    const PRICE_TYPE_FULL = 3;
    const PRICE_TYPE_FULL_YEAR = 31;

    const STATUS_PAID = 1;
    const STATUS_AWAIT_PAID = 2;
    const STATUS_OVERDUE = 3;

    public static $statusType = [
        self::STATUS_PAID => 'Оплачен',
        self::STATUS_AWAIT_PAID => 'Ожидает оплаты',
        self::STATUS_OVERDUE => 'Просрочено',
    ];

    public static $priceTypes = [
        self::PRICE_TYPE_FREE => 'FREE',
        self::PRICE_TYPE_SIMPLE => 'LIGHT',
        self::PRICE_TYPE_FULL => 'STANDARD',
        self::PRICE_TYPE_FULL_YEAR => 'STANDARD на год',
    ];

    public static $views = [
        self::PRICE_TYPE_FREE => '_free',
        self::PRICE_TYPE_SIMPLE => '_light',
        self::PRICE_TYPE_FULL => '_standard',
        self::PRICE_TYPE_FULL_YEAR => '_standard',
    ];

    public static $types = [
        self::TYPE_SHOP => 'Интернет магазин',
        self::TYPE_KINOTHEATER => 'Кинотеатр',
        self::TYPE_OTHER => 'Другой',
    ];

    private static $cafeCategoryIds = [6256,6255,6253,6252,6279,6251];

    public $price;
    public $karabas_business_id;
    public $user_payments;
    public $status_paid;
    public $listCategory;

    /**
     * @var array
     */
    public static $prices = [
        self::PRICE_TYPE_FREE => 0,
        self::PRICE_TYPE_SIMPLE => 100,
        self::PRICE_TYPE_FULL => 250,
        self::PRICE_TYPE_FULL_YEAR => 2000,
    ];

    public static function tableName()
    {
        return 'business';
    }

    public function rules()
    {
        return [
            [['title', 'idUser', 'idCategories', 'idCity'], 'required', 'message' => 'Поле не может быть пустым'],
            [['idUser', 'idCity', 'type', 'ratio', 'total_rating', 'quantity_rating', 'cinema_id', 'price_type',
            'template_id'], 'integer'],

            [['title', 'description', 'shortDescription','seo_description','seo_keywords','seo_title', 'short_url'], 'string'],
            [['trailer'], 'string','max' => 50, 'min' => 11],
            [['dateCreate', 'idProductCategories', 'price', 'url' , 'dateUpdate', 'sitemap_en', 'sitemap_priority',
                'sitemap_changefreq', 'isChecked', 'tags', 'karabas_business_id', 'due_date'],
                'safe'
            ],
            [['short_url'], 'unique', 'targetClass' => $this],
            ['short_url', 'match', 'pattern' => '/^[a-z0-9_-]+$/', 'message' => 'Допускаются только маленькие буквы, числа, дефис и подчёркивание.'],
            ['user_payments', 'each', 'rule' => ['integer']],
            [['status_paid', 'listCategory'], 'safe'],

            [['site', 'phone', 'urlVK', 'urlFB', 'urlTwitter', 'email'], 'string', 'max' => 255],
            [['image', 'background_image', 'price'], 'file', 'maxSize' => Yii::$app->params['maxSizeUpload'], 'extensions'=>'jpg, gif, png'],
            [['skype'], 'string', 'max' => 100],
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'value' => new Expression('NOW()'),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['dateCreate', 'dateUpdate'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['dateUpdate'],
                ],
            ],
            'slug' => [
                'class' => 'common\behaviors\Slug',
                'in_attribute' => 'title',
                'out_attribute' => 'url',
                'translit' => true,
                'unique' => true,
                'max_len' => 200,
            ],
            'ImageUpload' => [
                'class' => ImageUpload::className(),
                'attribute' => ['image', 'background_image', 'price'],
                'id' => 'id',
                'pid' => 'pid',
            ],
        ];
    }

    /**
     * @return BusinessQuery
     */
    public static function find()
    {
        return Yii::createObject(BusinessQuery::className(), [get_called_class()]);
    }

    /**
     * @return float|int
     */
    public function getRating()
    {
       return empty($this->quantity_rating) ? 0 : round(($this->total_rating / $this->quantity_rating), 1);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'idUser' => 'Id Пользователя',
            'idCategories' => 'Категории бизнеса',
            'idProductCategories' => 'Категории продуктов',
            'idCity' => 'Город',
            'description' => 'Описание',
            'shortDescription' => 'Краткое описание',
            'site' => 'Сайт',
            'phone' => 'Телефон',
            'urlVK' => 'Url Vk',
            'urlFB' => 'Url Fb',
            'urlTwitter' => 'Url Twitter',
            'email' => 'Email',
            'skype' => 'Skype',
            'image' => 'Логотип',
            'dateCreate' => 'Date Create',
            'price' => 'Прайс',
            'seo_description'=>'SEO Description',
            'seo_keywords'=>'SEO Ключевые слова',
            'seo_title' => 'Seo Title',
            'dateUpdate' => 'Дате обновления',
            'sitemap_en' => 'Добавить в sitemap',
            'sitemap_priority' => 'Sitemap приоритет',
            'sitemap_changefreq' => 'Sitemap частота изменения',
            'type' => 'Тип',
            'isChecked' => 'Is Checked',
            'ratio' => 'Рейтинг',
            'tags' => 'Теги',
            'total_rating' => 'Общая оценка',
            'quantity_rating' => 'Проголосовавших',
            'price_type' => 'Тип цены',
            'cinema_id' => 'ID Кинотеатра с http://kino.i.ua/',
            'carabas_business_id' => 'Идентификатор предприятия с  https://karabas.com/',
            'due_date' => 'Оплачено до',
            'short_url' => 'Короткий URL',
            'trailer' => 'Трейлер',
            'listCategory' => 'Список категорий',
            'template_id' => 'ID шаблона',
        ];
    }

    public function translateAttributes()
    {
        return [
            'title',
            'description',
            'shortDescription',
            'seo_description',
            'seo_keywords',
            'seo_title',
            'tags'
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($this->type === self::TYPE_KINOTHEATER) {
            /** @var ParseKino $find_kino */
            $find_kino = ParseKino::find()->where(['local_cinema_id' => $this->id])->one();
            if (isset($find_kino->local_cinema_id)) {
                $find_kino->remote_cinema_id = $this->cinema_id;
                $find_kino->save();
            } else {
                $parse_kino = new ParseKino();
                $parse_kino->local_cinema_id = $this->id;
                $parse_kino->remote_cinema_id = $this->cinema_id;
                $parse_kino->save();
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public function beforeSave($insert)
    {
        $this->seo_title        = strip_tags($this->seo_title);
        $this->seo_description  = strip_tags($this->seo_description);
        $this->seo_keywords     = strip_tags($this->seo_keywords);
        $this->title            = strip_tags($this->title);
        $this->url              = strip_tags($this->url);
        $this->urlFB            = strip_tags($this->urlFB);
        $this->urlTwitter       = strip_tags($this->urlTwitter);
        $this->urlVK            = strip_tags($this->urlVK);
        $this->skype            = strip_tags($this->skype);
        if ($this->short_url != '') {
            $this->short_url = mb_strtolower(strip_tags($this->short_url), 'utf-8');
        } else{
            unset($this->short_url);
        }


        $categories = $this->idCategories;
        if (is_string($categories)) {
            $categories = array($categories);
        }
        $this->idCategories = $this->php_to_postgres_array($categories);

        $this->tags = (is_array($this->tags)) ? implode(', ', $this->tags) : '';

        $categories = $this->idProductCategories;
        if (is_string($categories)) {
            $categories = [$categories];
        }

        $this->idProductCategories = $this->php_to_postgres_array($categories);
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        $this->idCategories = explode(',', trim($this->idCategories, '{}'));
        $this->idProductCategories = explode(',', trim($this->idProductCategories, '{}'));
    }

    public function php_to_postgres_array($phpArray)
    {
        if (empty($phpArray[0])) {
            unset($phpArray[0]);
        }
        return '{' . join(',', $phpArray) . '}';

    }

    /**
     * @return string
     */
    public function getCategories()
    {
        return implode(',', $this->idCategories);
    }

    /**
     * @return string
     */
    public function getProductCategories()
    {
        return implode(',', $this->idProductCategories);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasMany(BusinessAddress::className(), ['idBusiness' => 'id'])->orderBy(['id' => SORT_ASC]);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getTimes()
    {
        return $this->hasMany(BusinessTime::className(), ['idBusiness' => 'id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(BusinessTemplate::className(), ['id' => 'template_id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getActions()
    {
        return $this->hasMany(Action::className(), ['idCompany' => 'id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getVacantions()
    {
        return $this->hasMany(WorkVacantion::className(), ['idCompany' => 'id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getAds()
    {
        return $this->hasMany(Ads::className(), ['idBusiness' => 'id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getPrices()
    {
        return $this->hasMany(File::className(), ['pid' => 'id'])->where(['type' => File::TYPE_BUSINESS_PRICE]);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getScheduleKino()
    {
        /** @var ScheduleQuery $link */
        $link = $this->hasMany(ScheduleKino::className(), ['idCompany' => 'id']);

        return $link->active();
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getFutureScheduleKino()
    {
        /** @var ScheduleQuery $link */
        $link = $this->hasMany(ScheduleKino::className(), ['idCompany' => 'id']);

        return $link->activeAndFuture();
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getCountView()
    {
        return $this->hasOne(CountViews::className(), ['pid' => 'id']);
    }

    /**
     * @return integer
     */
    public function getViews()
    {
        $view_cat = ViewCount::CAT_BUSINESS;
        $count_views = ViewCount::find()
            ->select('view_count.value')
            ->where(['view_count.category' => $view_cat])
            ->andWhere(['view_count.item_id' => $this->id])
            ->andWhere(['view_count.city_id' => $this->idCity])
            ->sum('value');

        return $count_views;
    }

    /**
     * @param array $array
     * @return string
     */
    public function categoryNames($array)
    {
        $list = [];
        if (is_array($array) && $array[0] != '') {
            /** @var BusinessCategory[] $cats */
            $cats = BusinessCategory::find()->select(['title'])->where(['id' => $array])->all();
            foreach ($cats as $item){
                $list[] = $item->title;
            }
        }
        return implode(', ', $list);
    }

    /**
     * @param array $array
     * @return array
     */
    public function categoryList($array)
    {
        $list = [];
        if (is_array($array) && $array[0] != '') {
            foreach ($array as $item) {
                /** @var BusinessCategory $cat */
                $cat = BusinessCategory::find()->select(['id', 'title', 'url'])->where(['id'=>$item])->one();
                if ($cat) {
                    $list[] = ['id' => $cat->id, 'title' => $cat->title, 'url' => $cat->url];
                }
            }
        }
        return $list;
    }

    /**
     * @param $id
     * @return int
     */
    public function getComments($id)
    {
        return Comment::find()->where(['type' => File::TYPE_BUSINESS, 'pid' => $id])->count();
    }

    /**
     * @return ProductCategory[]
     */
    public function productCategory()
    {
        $ids = [];
        if (is_array($this->idProductCategories) and $this->idProductCategories) {
            foreach ($this->idProductCategories as $item) {
                if ((int)$item) {
                    $ids[] = (int)$item;
                }
            }
        }

        if ($ids) {
            return ProductCategory::find()->where(['id' => $ids])->all();
        }
        return [];
    }

    /**
     * @param array $array
     *
     * @return string
     */
    public function productCategoryNames($array)
    {
        $ids = [];
        foreach ($array as $item) {
            $ids[] = (int)$item ? (int)$item : null;
        }
        return implode(', ', ArrayHelper::getColumn(ProductCategory::find()->select(['title'])->where(['id' => $ids])->all(), 'title'));
    }

    /**
     * @return array
     */
    public function getRoute()
    {
        if (empty(Yii::$app->request->city)) {
            return ['business/view', 'alias' => "{$this->id}-{$this->url}", 'subdomain' => $this->city->subdomain];
        }
        return ['business/view', 'alias' => "{$this->id}-{$this->url}"];
    }

    /**
     * @param array|string|null $where
     * @param integer|null $business_id
     * @param int $limit
     *
     * @return array
     */
    public static function getBusinessList($where, $business_id = null, $limit = 100)
    {
        $busy = Business::find()->select(['id', 'title'])->where($where)->limit($limit)->all();
        if ($business_id) {
            $busy[] = Business::find()->select(['id', 'title'])->where(['id' => $business_id])->one();
        }

        return ArrayHelper::map($busy, 'id', 'title');
    }

    /**
     * @return int
     */
    public function getCommentsCount()
    {
        return Comment::find()->where(['type' => File::TYPE_BUSINESS, 'pid' => $this->id])->count();
    }

    /**
     * @return string
     */
    public function getFrontendUrl()
    {
        return '/business/' . $this->id;
    }

    /**
     * @return null|string
     */
    public function getSubDomain()
    {
        return !empty($this->city) ? ($this->city->subdomain . '.') : null;
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getBusinessCategories()
    {
        return $this->hasMany(BusinessCategory::className(), ['id' => 'idCategories']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getCustomFieldValues()
    {
        return $this->hasMany(BusinessCustomFieldValue::className(), ['business_id' => 'id']);
    }

    /**
     * @param User $user
     * @return array
     */
    public function getTariffs(User $user)
    {
        $now = time();

        return [
            self::PRICE_TYPE_FREE => [
                'title' => self::$priceTypes[self::PRICE_TYPE_FREE],
                'order' => new Order(0, LiqPayCurrency::UAH, "Управление предприятием {$this->title} Бесплатный", "{$now}_business_{$this->id}_own_1_from_{$user->id}"),
                'duration' => 1,
            ],
            self::PRICE_TYPE_SIMPLE => [
                'title' => self::$priceTypes[self::PRICE_TYPE_SIMPLE],
                'order' => new Order(100, LiqPayCurrency::UAH, "Оплата управления предприятием {$this->title} Обычный", "{$now}_business_{$this->id}_own_2_from_{$user->id}"),
                'duration' => 1,
            ],
            self::PRICE_TYPE_FULL => [
                'title' => self::$priceTypes[self::PRICE_TYPE_FULL],
                'order' => new Order(250, LiqPayCurrency::UAH, "Оплата владeния предприятием {$this->title} Расширенный", "{$now}_business_{$this->id}_own_3_from_{$user->id}"),
                'duration' => 1,
            ],
            self::PRICE_TYPE_FULL_YEAR => [
                'title' => self::$priceTypes[self::PRICE_TYPE_FULL_YEAR],
                'order' => new Order(2000, LiqPayCurrency::UAH, "Оплата владeния предприятием {$this->title} Расширенный на год", "{$now}_business_{$this->id}_own_31_from_{$user->id}"),
                'duration' => 12,
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPaymentTypeBusinesses()
    {
        return $this->hasMany(UserPaymentTypeBusiness::className(), ['business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPaymentType()
    {
        return $this->hasMany(UserPaymentType::className(), ['id' => 'user_payment_type_id'])->via('userPaymentTypeBusinesses');
    }

    /**
     * @return string
     */
    public function getSearchShortView()
    {
        return '/search/_business_short';
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getActiveAfisha()
    {
        $now = date('Y-m-d H:i:s');
        $day = mb_strtolower(date('D'));

        return Afisha::find()
            ->with(['afisha_category'])
            ->joinWith(['afishaWeekRepeat'])
            ->where(['and',
                ['afisha."isFilm"' => 0],
                ':id = ANY(afisha."idsCompany")',
                ['or',
                    ['>=', 'afisha."dateEnd"', $now],
                    ['afisha.repeat' => Afisha::REPEAT_DAY],
                    ['afisha.repeat' => Afisha::REPEAT_WEEK],
                ]
            ], ['id' => $this->id])
            ->orWhere(['afisha.id' => $this->getFutureScheduleKino()->select('idAfisha')->distinct()]);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getActiveAction()
    {
        $now = date('Y-m-d H:i:s');

        return $this->getActions()
            ->with('category')
            ->andWhere(['>=', 'dateEnd', $now]);
    }

    public function getDueDate(){
        if ($this->due_date){
            $date = new DateTime($this->due_date);
            return $date->format('Y-m-d');
        } else {
            $date = new DateTime();
            return $date->format('Y-m-d');
        }
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getBusinessTimes()
    {
        return $this->hasMany(BusinessTime::className(), ['idBusiness' => 'id']);
    }

    public function getStatusPaid()
    {
        $liqPay = null;
        /** @var Invoice $invoice */
        $invoice = Invoice::find()->where(['object_id' => $this->id, 'object_type' => File::TYPE_BUSINESS])->one();
        if ($invoice) {
            /** @var LiqpayPayment $liqPay */
            $liqPay = LiqpayPayment::find()->where(['order_id' => $invoice->order_id])->one();
        }

        if ($this->price_type == self::PRICE_TYPE_FREE){
            return '';
        } else {
            if ($invoice && $liqPay && ($liqPay->status === LiqPayStatuses::SUCCESS) && (strtotime($this->due_date) > time())) {
                return '<div class="business-status-paid">' . Business::$statusType[Business::STATUS_PAID] . '<div>';
            } else if ($invoice && $liqPay && ($liqPay->status === LiqPayStatuses::SUCCESS) && (strtotime($this->due_date) > (time() - 3600 * 24 * 7))) {
                return '<div class="business-status-await-paid">' . Business::$statusType[Business::STATUS_AWAIT_PAID] . '<div>';
            } else {
                return '<div class="business-status-overdue">' . Business::$statusType[Business::STATUS_OVERDUE] . '<div>';
            }
        }
    }

    public function isCategoryCafe()
    {
        return count(array_intersect($this->idCategories, static::$cafeCategoryIds)) > 0;
    }

    /**
     * @return bool
     */
    public function getIsPaid()
    {
        return $this->status_paid == 1;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        $date = new DateTime($this->due_date);
        $diffDays = $date->diff(new DateTime())->days;
        if ($this->isPaid) {
            if ($diffDays > 0) {
                return self::STATUS_PAID;
            }else {
                return self::STATUS_OVERDUE;
            }
        }else {
            if ($diffDays + 7 > 0) {
                return self::STATUS_AWAIT_PAID;
            }else {
                return self::STATUS_OVERDUE;
            }
        }
    }

    /**
     * Return is active service. Use template free or selected
     * @return bool
     */
    public function getIsActive()
    {
        return $this->status != self::STATUS_OVERDUE;
    }
}

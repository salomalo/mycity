<?php
namespace common\models;

use common\extensions\ChartsWidget;
use DateInterval;
use DateTime;
use yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "logParse".
 *
 * @property integer $id
 * @property string $operation
 * @property string $description
 * @property string $dateCreate
 * @property string $object_id
 * @property string $ip
 * @property integer $type
 * @property integer $user_id
 * @property integer $admin_id
 */
class Log extends ActiveRecord
{
    const TYPE_ADMIN = 11;
    const TYPE_USER = 2;

    const TYPE_BUSINESS = 10;
    const TYPE_POST = 11;
    const TYPE_RESUME = 12;
    const TYPE_ADS = 13;
    const TYPE_AFISHA = 14;
    const TYPE_PRODUCT = 15;
    const TYPE_WORK_VACANTION = 16;
    const TYPE_ACTION = 17;
    const TYPE_COMMENT = 18;
    const TYPE_PAYMENT = 19;
    const TYPE_ACTION_CATEGORY = 20;
    const TYPE_ADMIN_LIST = 21;
    const TYPE_AFISHA_CATEGORY = 22;
    const TYPE_BUSINESS_CATEGORY = 23;
    const TYPE_CITY = 24;
    const TYPE_CUSTOM_FIELD_CATEGORY = 25;
    const TYPE_FRIEND = 26;
    const TYPE_KINO_GENRE = 27;
    const TYPE_LANG = 28;
    const TYPE_NOTIFICATION = 29;
    const TYPE_ORDER_ADS = 30;
    const TYPE_ORDERS = 31;
    const TYPE_PAYMENT_TYPE = 32;
    const TYPE_POST_CATEGORY = 33;
    const TYPE_PRODUCT_CATEGORY = 34;
    const TYPE_PRODUCT_COMPANY = 35;
    const TYPE_REGION = 36;
    const TYPE_SCHEDULE_KINO = 37;
    const TYPE_TICKET = 38;
    const TYPE_ACCOUNT = 39;
    const TYPE_WORK_CATEGORY = 40;

    const TYPE_REGISTER = 1001;
    const TYPE_LOGIN = 1002;

    public static $types = [
        self::TYPE_BUSINESS => 'Предприятия',
        self::TYPE_POST => 'Новости',
        self::TYPE_RESUME => 'Резюме',
        self::TYPE_ADS => 'Объявления',
        self::TYPE_AFISHA => 'Афиша',
        self::TYPE_PRODUCT => 'Продукты',
        self::TYPE_WORK_VACANTION => 'Вакансии',
        self::TYPE_ACTION => 'Акции',
        self::TYPE_COMMENT => 'Комментарии',
        self::TYPE_REGISTER => 'Регистрация',
        self::TYPE_LOGIN => 'Авторизация',
        self::TYPE_PAYMENT => 'Оплата',
        self::TYPE_ACTION_CATEGORY => 'Категории акции',
        self::TYPE_ADMIN_LIST => 'Админы',
        self::TYPE_AFISHA_CATEGORY => 'Категории афиши',
        self::TYPE_BUSINESS_CATEGORY => 'Категории предприятия',
        self::TYPE_CITY => 'Города',
        self::TYPE_CUSTOM_FIELD_CATEGORY => 'Категории Customfield',
        self::TYPE_FRIEND => 'Друзья',
        self::TYPE_KINO_GENRE => 'Жанры кино',
        self::TYPE_LANG => 'Языки',
        self::TYPE_NOTIFICATION => 'Нотификации',
        self::TYPE_ORDER_ADS => 'Содержание заказа',
        self::TYPE_ORDERS => 'Заказы',
        self::TYPE_PAYMENT_TYPE => 'Типы оплаты',
        self::TYPE_POST_CATEGORY => 'Категории новостей',
        self::TYPE_PRODUCT_CATEGORY => 'Категории продуктов',
        self::TYPE_PRODUCT_COMPANY => 'Компании продуктов',
        self::TYPE_REGION => 'Регионы',
        self::TYPE_SCHEDULE_KINO => 'Сеансы кино',
        self::TYPE_TICKET => 'Тикеты',
        self::TYPE_ACCOUNT => 'Пользователи',
        self::TYPE_WORK_CATEGORY => 'Категории работы',
    ];

    public static $urls = [
        self::TYPE_BUSINESS => '/business',
        self::TYPE_POST     => '/post',
        self::TYPE_RESUME   => '/work-resume',
        self::TYPE_ADS      => '/ads',
        self::TYPE_AFISHA   => '/afisha',
        self::TYPE_PRODUCT  => '/product',
        self::TYPE_WORK_VACANTION  => '/work-vacantion',
        self::TYPE_ACTION   => '/action',
        self::TYPE_COMMENT   => '/comment',
        self::TYPE_PAYMENT => '/liqpay-payment',
        self::TYPE_ACTION_CATEGORY => '/action-category',
        self::TYPE_ADMIN_LIST => '/admin',
        self::TYPE_AFISHA_CATEGORY => '/afisha-category',
        self::TYPE_BUSINESS_CATEGORY => '/business-category',
        self::TYPE_CITY => '/city',
        self::TYPE_CUSTOM_FIELD_CATEGORY => '/customfield-category',
        self::TYPE_FRIEND => '/friend',
        self::TYPE_KINO_GENRE => '/kino-genre',
        self::TYPE_LANG => '/lang',
        self::TYPE_NOTIFICATION => '/notification',
        self::TYPE_ORDER_ADS => '/orders-ads',
        self::TYPE_ORDERS => '/orders',
        self::TYPE_PAYMENT_TYPE => '/payment-type',
        self::TYPE_POST_CATEGORY => '/post-category',
        self::TYPE_PRODUCT_CATEGORY => '/product-category',
        self::TYPE_PRODUCT_COMPANY => '/product-company',
        self::TYPE_REGION => '/region',
        self::TYPE_SCHEDULE_KINO => '/schedule-kino',
        self::TYPE_TICKET => '/ticket',
        self::TYPE_ACCOUNT => '/user',
        self::TYPE_WORK_CATEGORY => '/work-category',
    ];

    public static $urlsByObjectType = [
        'afisha' => '/afisha',
        'business' => '/business',
        'schedule kino' => '/schedule-kino',
        'admin' => '/admin',
        'account' => '/account',
        'product category' => '/product-category',
        'business category' => '/business-category',
        'action category' => '/action-category',
        'action' => '/action',
        'ads' => '/ads',
        'afisha category' => '/afisha-category',
        'city' => '/city',
        'comment' => '/comment',
        'customfield category' => '/customfield-category',
        'kino genre' => '/kino-genre',
        'liqpay payment' => '/liqpay-payment',
        'payment type' => '/payment-type',
        'post category' => '/post-category',
        'post' => '/post',
        'product company' => '/product-company',
        'product' => '/product',
        'region' => '/region',
        'work category' => '/work-category',
        'work resume' => '/work-resume',
        'work vacantion' => '/work-vacantion',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_backend';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dateCreate', 'description'], 'safe'],
            [['user_id', 'admin_id', 'type'], 'integer'],
            [['object_id', 'ip'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'description' => 'Описание',
            'dateCreate' => 'Дата',
            'user_id' => 'ID пользователя',
            'admin_id' => 'ID админа',
            'object_id' => 'ID объекта',
            'ip' => 'IP',
            'type' => 'Тип объекта',
        ];
    }

    public function behaviors()
    {
        return array(
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'value' => new Expression('NOW()'),
                'attributes' => array(
                    ActiveRecord::EVENT_BEFORE_INSERT => 'dateCreate',
                ),
            ],
        );
    }

    public static function addUserLog($description = '', $object_id = null, $type = null)
    {
        $log = new self();

        $log->description = $description;
        $log->type = $type;
        $log->ip = (string)self::getRealIp();
        if ($object_id) {
            $log->object_id = (string)$object_id;
        }

        if ($type == self::TYPE_LOGIN || $type == self::TYPE_REGISTER){
            $log->user_id = (int)$object_id;
        } else {
            $log->user_id = Yii::$app->user->id;
        }

        $log->save();
    }

    public static function addAdminLog($description = '', $object_id = null, $type = null)
    {
        $log = new self();

        $log->description = $description;
        $log->type = $type;
        $log->ip = (string)self::getRealIp();
        if ($object_id) {
            $log->object_id = (string)$object_id;
        }

        if ($type == self::TYPE_LOGIN || $type == self::TYPE_REGISTER){
            $log->admin_id = (int)$object_id;
        } else {
            $log->admin_id = Yii::$app->user->getIdentity()->getId();
        }

        $log->save();
    }

    public static function getRealIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function getBackendLinkPid()
    {
        if (isset(self::$urls[$this->type])) {
            $url = Url::to([self::$urls[$this->type] . '/view', 'id' => $this->object_id]);
            $link = Html::a($this->object_id, $url);
            return $link;
        } else {
            $id = (integer)substr($this->description, 3);
            $object_type = stristr($this->operation, '[', true);

            if (array_key_exists($object_type, self::$urlsByObjectType)) {
                $url = Url::to([self::$urlsByObjectType[$object_type] . '/view', 'id' => $id]);
                $link = Html::a($id, $url);
                return $link;
            } else {
                return '';
            }
        }
    }

    /**
     * @param $duration
     * @param $periodFunc
     * @param $betweenFunc
     * @param int $int
     * @param array $andWhere
     * @return array
     */
    public static function getLoginAndRegChart($duration, $periodFunc, $betweenFunc, $int = 1, $andWhere = [])
    {
        return [
            'title' => 'Регистрация и авторизация',
            'type' => ChartsWidget::TYPE_CHART_BASIC_AREA,
            'data' => [
                'values' => Log::getLoginAndRegData($duration, $periodFunc, $betweenFunc, $int, $andWhere),
                'titles' => ['Авторизаций', 'Регистраций']
            ],
            'yAxis' => 'Пользователей',
        ];
    }

    public static function getTotalActivityChart($duration, $periodFunc, $betweenFunc, $int = 1, $andWhere = [])
    {
        return [
            'title' => 'Активность пользователей',
            'type' => ChartsWidget::TYPE_CHART_STACKED_AREA,
            'data' => [
                'values' => Log::getTotalActivityData($duration, $periodFunc, $betweenFunc, $int, $andWhere),
                'titles' => [
                    'Комментирование',
                    'Проставление рейтинга'
                ]
            ],
            'yAxis' => 'Количество операций',
        ];
    }

    public static function getNewCommentChart($duration, $periodFunc, $betweenFunc, $int = 1, $andWhere = [])
    {
        return [
            'title' => 'Количество новых комментариев',
            'type' => ChartsWidget::TYPE_CHART_STACKED_COLUMN,
            'data' => [
                'values' => Log::getNewCommentData($duration, $periodFunc, $betweenFunc, $int, $andWhere),
                'titles' => ['Объявлений', 'Акций', 'Афиш', 'Предприятий', 'Новостей', 'Резюме', 'Вакансий']
            ],
            'yAxis' => 'Количество',
        ];
    }

    public static function getAddContentChart($duration, $periodFunc, $betweenFunc, $int = 1, $andWhere = [])
    {
        return [
            'title' => 'Накопительный график добавления контента',
            'type' => ChartsWidget::TYPE_CHART_STACKED_AREA,
            'data' => [
                'values' => Log::getAddContentData($duration, $periodFunc, $betweenFunc, $int, $andWhere),
                'titles' => ['Объявлений', 'Акций', 'Афиш', 'Предприятий', 'Новостей', 'Резюме', 'Вакансий']
            ],
            'yAxis' => 'Добавлено',
        ];
    }

    public static function getLoginAndRegChartForPeriod($start, $end, $periodFunc, $betweenFunc, $int = 1, $andWhere = [])
    {
        return [
            'title' => 'Регистрация и авторизация',
            'type' => ChartsWidget::TYPE_CHART_BASIC_AREA,
            'data' => [
                'values' => Log::getLoginAndRegDataForPeriod($start, $end, $periodFunc, $betweenFunc, $int, $andWhere),
                'titles' => ['Авторизаций', 'Регистраций']
            ],
            'yAxis' => 'Пользователей',
        ];
    }

    public static function getAddContentChartForPeriod($start, $end, $periodFunc, $betweenFunc, $int = 1, $andWhere = [])
    {
        return [
            'title' => 'Накопительный график добавления контента',
            'type' => ChartsWidget::TYPE_CHART_STACKED_AREA,
            'data' => [
                'values' => Log::getAddContentDataForPeriod($start, $end, $periodFunc, $betweenFunc, $int, $andWhere),
                'titles' => ['Объявлений', 'Акций', 'Афиш', 'Предприятий', 'Новостей', 'Резюме', 'Вакансий']
            ],
            'yAxis' => 'Добавлено',
        ];
    }

    public static function getNewCommentChartForPeriod($start, $end, $periodFunc, $betweenFunc, $int = 1, $andWhere = [])
    {
        return [
            'title' => 'Количество новых комментариев',
            'type' => ChartsWidget::TYPE_CHART_STACKED_COLUMN,
            'data' => [
                'values' => Log::getNewCommentDataForPeriod($start, $end, $periodFunc, $betweenFunc, $int, $andWhere),
                'titles' => ['Объявлений', 'Акций', 'Афиш', 'Предприятий', 'Новостей', 'Резюме', 'Вакансий']
            ],
            'yAxis' => 'Количество',
        ];
    }

    public static function getTotalActivityChartForPeriod($start, $end, $periodFunc, $betweenFunc, $int = 1, $andWhere = [])
    {
        return [
            'title' => 'Активность пользователей',
            'type' => ChartsWidget::TYPE_TIME_SERIES,
            'data' =>  Log::getTotalActivityDataForPeriod($start, $end, $periodFunc, $betweenFunc, $int, $andWhere),
            'yAxis' => 'Количество операций',
        ];
    }

    /**
     * @param $period
     * @param callable $interval
     * @param callable $between
     * @param int $int
     * @param $andWhere array
     * @return array
     */
    public static function getLoginAndRegData($period, callable $interval, callable $between, $int = 1, $andWhere)
    {
        $result = [];
        $date = new DateTime();
        //Откатывается назад, что бы тек. месяц был последним
        $date->sub($interval($period * $int));

        for ($i = 1; $i <= $period; $i++) {
            $date->add($interval($int));
            $w = $between($date);

            $dateStart = new DateTime();
            $dateStart->setTimestamp($w['start']);
            $dateEnd = new DateTime();
            $dateEnd->setTimestamp($w['end']);

            $queryLogin = self::find()->where(['between', 'dateCreate', $dateStart->format('Y-m-d H:i:s'), $dateEnd->format('Y-m-d H:i:s')])
                ->andWhere(['like', 'description', 'Авторизация'])
                ->andWhere($andWhere)
                ->count();
            $queryRegister = self::find()->where(['between', 'dateCreate', $dateStart->format('Y-m-d H:i:s'), $dateEnd->format('Y-m-d H:i:s')])
                ->andWhere(['like', 'description', 'Регистрация'])
                ->andWhere($andWhere)
                ->count();

            $data = [];
            $data[] = $queryLogin;
            $data[] = $queryRegister;
            $result[$w['i']] = $data;
        }

        return $result;
    }

    /**
     * @param $period int
     * @param callable $interval
     * @param callable $between
     * @param int $int
     * @param $andWhere array
     * @return array
     */
    public static function getTotalActivityData($period, callable $interval, callable $between, $int = 1, $andWhere)
    {
        $result = [];
        $date = new DateTime();
        //Откатывается назад, что бы тек. месяц был последним
        $date->sub($interval($period * $int));

        for ($i = 1; $i <= $period; $i++) {
            $date->add($interval($int));
            $w = $between($date);

            $dateStart = new DateTime();
            $dateStart->setTimestamp($w['start']);
            $dateEnd = new DateTime();
            $dateEnd->setTimestamp($w['end']);

            $data = [];
            $data[] = self::find()->where(['between', 'dateCreate', $dateStart->format('Y-m-d H:i:s'), $dateEnd->format('Y-m-d H:i:s')])
                ->andWhere(['like', 'description', 'comment[create]'])
                ->count();
            $data[] = 0;
            $result[$w['i']] = $data;
        }
        return $result;
    }

    /**
     * @param $period int
     * @param callable $interval
     * @param callable $between
     * @param int $int
     * @param $andWhere array
     * @return array
     */
    public static function getNewCommentData($period, callable $interval, callable $between, $int = 1, $andWhere)
    {
        $result = [];
        $date = new DateTime();
        //Откатывается назад, что бы тек. месяц был последним
        $date->sub($interval($period * $int));
        $models = [
            File::TYPE_ADS, File::TYPE_ACTION, File::TYPE_AFISHA,
            File::TYPE_BUSINESS, File::TYPE_POST,
            File::TYPE_RESUME, File::TYPE_WORK_VACANTION,
        ];

        for ($i = 1; $i <= $period; $i++) {
            $date->add($interval($int));
            $w = $between($date);

            $dateStart = new DateTime();
            $dateStart->setTimestamp($w['start']);
            $dateEnd = new DateTime();
            $dateEnd->setTimestamp($w['end']);

            $data = [];
            foreach ($models as $model) {
                $data[] = self::find()->where(['between', 'dateCreate', $dateStart->format('Y-m-d H:i:s'), $dateEnd->format('Y-m-d H:i:s')])
                    ->andWhere(['like', 'description', 'comment[create][' . $model . ']'])
                    ->count();
            }
            $result[$w['i']] = $data;
        }

        return $result;
    }

    /**
     * @param $period int
     * @param callable $interval
     * @param callable $between
     * @param int $int
     * @param $andWhere array
     * @return array
     */
    public static function getAddContentData($period, callable $interval, callable $between, $int = 1, $andWhere)
    {
        $result = [];
        $date = new DateTime();
        //Откатывается назад, что бы тек. месяц был последним
        $date->sub($interval($period * $int));
        $models = [
            'ads[create]', 'action[create]', 'afisha[create]',
            'business[create]', 'post[create]',
            'work resume[create]', 'work vacantion[create]',
        ];

        for ($i = 1; $i <= $period; $i++) {
            $date->add($interval($int));
            $w = $between($date);

            $dateStart = new DateTime();
            $dateStart->setTimestamp($w['start']);
            $dateEnd = new DateTime();
            $dateEnd->setTimestamp($w['end']);

            $data = [];
            foreach ($models as $model) {
                $data[] = self::find()->where(['between', 'dateCreate', $dateStart->format('Y-m-d H:i:s'), $dateEnd->format('Y-m-d H:i:s')])
                    ->andWhere([
                        'or',
                        ['like', 'operation', $model],
                        ['like', 'description', $model]
                    ])
                    ->count();
            }
            $result[$w['i']] = $data;
        }

        return $result;
    }

    public static function getIntervalFunc($step)
    {
        return function ($i = 1) use ($step) {
            return new DateInterval("P{$i}{$step}");
        };
    }

    public static function getBetweenFunc($step)
    {
        switch ($step) {
            case 'D':
                $between = function (DateTime $date) {
                    $day = $date->format('Y-m-d');
                    $i = $date->format('d.m');
                    $start = strtotime($day . ' 00:00:00');
                    $end = strtotime($day . ' 24:00:00');
                    return ['start' => $start, 'end' => $end, 'i' => $i];
                };
                break;
            case 'W':
                $between = function (DateTime $date) {
                    $date->modify('last Monday');
                    $start = strtotime($date->format('Y-m-d 00:00:01'));
                    $date->modify('next Sunday');
                    $end = strtotime($date->format('Y-m-d 23:59:59'));
                    $i = date('d.m-', $start) . date('d.m', $end);
                    return ['start' => $start, 'end' => $end, 'i' => $i];
                };
                break;
            case 'M':
                $between = function (DateTime $date) {
                    $months = [
                        1 => Yii::t('detail-log', 'January'),    Yii::t('detail-log', 'February'),
                        Yii::t('detail-log', 'March'),           Yii::t('detail-log', 'April'),      Yii::t('detail-log', 'May'),
                        Yii::t('detail-log', 'June'),            Yii::t('detail-log', 'July'),       Yii::t('detail-log', 'August'),
                        Yii::t('detail-log', 'September'),       Yii::t('detail-log', 'October'),    Yii::t('detail-log', 'November'),
                        Yii::t('detail-log', 'December'),
                    ];
                    $month = $date->format('m');
                    $year = $date->format('Y');
                    $lastDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $start = strtotime($date->format('Y-m-1 00:00:00'));
                    $end = strtotime($date->format("Y-m-$lastDay 24:00:00"));
                    return ['start' => $start, 'end' => $end, 'i' => $months[(int)$month] . ' \'' . $date->format('y')];
                };
                break;
            case 'HY':
                $between = function (DateTime $date) {
                    $month = $date->format('m');
                    $year = $date->format('Y');
                    $date2 = clone $date;
                    $date2->modify('+5 month');
                    $month1 = $date2->format('m');
                    $year1 = $date2->format('Y');
                    $lastDay = cal_days_in_month(CAL_GREGORIAN, $month1, $year1);
                    $start = strtotime($date->format('Y-m-1 00:00:00'));
                    $end = strtotime($date2->format("Y-m-$lastDay 24:00:00"));
                    return ['start' => $start, 'end' => $end, 'i' => "$month.$year - $month1.$year1"];
                };
                break;
            case 'Y':
                $between = function (DateTime $date) {
                    $start = strtotime($date->format('Y-01-01 00:00:01'));
                    $end = strtotime($date->format('Y-12-31 23:59:59'));
                    $i = $date->format('Y');
                    return ['start' => $start, 'end' => $end, 'i' => $i];
                };
                break;
            default:
                throw new \InvalidArgumentException('Incorrect step type for between function');
        }
        return $between;
    }

    /**
     * @param $start
     * @param $end
     * @param callable $interval
     * @param callable $between
     * @param int $int
     * @param $andWhere array
     * @return array
     * @internal param $period
     */
    public static function getLoginAndRegDataForPeriod($start, $end, callable $interval, callable $between, $int = 1, $andWhere)
    {
        $result = [];
        $date = new DateTime($start);
        while ($date->format('U') <= strtotime($end)) {
            $w = $between($date);

            $dateStart = new DateTime();
            $dateStart->setTimestamp($w['start']);
            $dateEnd = new DateTime();
            $dateEnd->setTimestamp($w['end']);

            $queryLogin = self::find()->where(['between', 'dateCreate', $dateStart->format('Y-m-d H:i:s'), $dateEnd->format('Y-m-d H:i:s')])
                ->andWhere(['like', 'description', 'Авторизация'])
                ->andWhere($andWhere)
                ->count();
            $queryRegister = self::find()->where(['between', 'dateCreate', $dateStart->format('Y-m-d H:i:s'), $dateEnd->format('Y-m-d H:i:s')])
                ->andWhere(['like', 'description', 'Регистрация'])
                ->andWhere($andWhere)
                ->count();

            $data = [];
            $data[] = $queryLogin;
            $data[] = $queryRegister;

            $result[$w['i']] = $data;
            $date->add($interval($int));
        }

        return $result;
    }

    /**
     * @param $start
     * @param $end
     * @param callable $interval
     * @param callable $between
     * @param int $int
     * @param $andWhere array
     * @return array
     * @internal param int $period
     */
    public static function getAddContentDataForPeriod($start, $end, callable $interval, callable $between, $int = 1, $andWhere)
    {
        $result = [];
        $date = new DateTime($start);
        $prev = [];

        $models = [
            'ads[create]', 'action[create]', 'afisha[create]',
            'business[create]', 'post[create]',
            'work resume[create]', 'work vacantion[create]',
        ];

        while ($date->format('U') <= strtotime($end)) {
            $w = $between($date);

            $dateStart = new DateTime();
            $dateStart->setTimestamp($w['start']);
            $dateEnd = new DateTime();
            $dateEnd->setTimestamp($w['end']);

            $data = [];
            foreach ($models as $model) {
                $data[] = self::find()->where(['between', 'dateCreate', $dateStart->format('Y-m-d H:i:s'), $dateEnd->format('Y-m-d H:i:s')])
                    ->andWhere([
                        'or',
                        ['like', 'operation', $model],
                        ['like', 'description', $model]
                    ])
                    ->count();
            }
            $result[$w['i']] = $data;
            $date->add($interval($int));
        }

        return $result;
    }

    /**
     * @param $start
     * @param $end
     * @param callable $interval
     * @param callable $between
     * @param int $int
     * @param $andWhere array
     * @return array
     * @internal param int $period
     */
    public static function getNewCommentDataForPeriod($start, $end, callable $interval, callable $between, $int = 1, $andWhere)
    {
        $result = [];
        $date = new DateTime($start);
        $models = [
            File::TYPE_ADS, File::TYPE_ACTION, File::TYPE_AFISHA,
            File::TYPE_BUSINESS, File::TYPE_POST,
            File::TYPE_RESUME, File::TYPE_WORK_VACANTION,
        ];

        while ($date->format('U') <= strtotime($end)) {
            $w = $between($date);

            $dateStart = new DateTime();
            $dateStart->setTimestamp($w['start']);
            $dateEnd = new DateTime();
            $dateEnd->setTimestamp($w['end']);

            $data = [];
            foreach ($models as $model) {
                $data[] = self::find()->where(['between', 'dateCreate', $dateStart->format('Y-m-d H:i:s'), $dateEnd->format('Y-m-d H:i:s')])
                    ->andWhere(['like', 'description', 'comment[create][' . $model . ']'])
                    ->count();
            }
            $result[$w['i']] = $data;
            $date->add($interval($int));
        }

        return $result;
    }

    /**
     * @param $start
     * @param $end
     * @param callable $interval
     * @param callable $between
     * @param int $int
     * @param $andWhere array
     * @return array
     * @internal param int $period
     */
    public static function getTotalActivityDataForPeriod($start, $end, callable $interval, callable $between, $int = 1, $andWhere)
    {
        $result = [];
        $date = new DateTime($start);

        while ($date->format('U') <= strtotime($end)) {
            $w = $between($date);

            $dateStart = new DateTime();
            $dateStart->setTimestamp($w['start']);
            $dateEnd = new DateTime();
            $dateEnd->setTimestamp($w['end']);

            $count = function () use ($dateStart, $dateEnd, $andWhere) {
                return self::find()->where(['between', 'dateCreate', $dateStart->format('Y-m-d H:i:s'), $dateEnd->format('Y-m-d H:i:s')])->andWhere($andWhere)->count();
            };
            $result[$w['i']] = $count();
            $date->add($interval($int));
        }

        return $result;
    }
}

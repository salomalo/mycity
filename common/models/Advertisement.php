<?php

namespace common\models;

use common\behaviors\ImageUpload;
use common\components\ActiveRecordMultiLang;
use DateTime;
use yii;

/**
 * This is the model class for table "advertisement".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $position
 * @property integer $status
 * @property string $title
 * @property string $image
 * @property string $url
 * @property string $date_start
 * @property string $date_end
 * @property string $created_at
 * @property integer $city_id
 *
 * @property User $user
 * @property City $city
 * @property string $statusLabel
 * @property string $positionLabel
 * @property string $timeStatusLabel
 * @property int $timeStatus
 */
class Advertisement extends ActiveRecordMultiLang
{
    const POS_HEAD_HORIZONTAL = 1;
    const POS_FOOT_HORIZONTAL = 2;
    const POS_SIDE_VERTICAL = 3;
    const POS_SIDE_SQUARE = 4;

    public static $positions = [
        self::POS_HEAD_HORIZONTAL => 'Горизонтальная сверху',
        self::POS_FOOT_HORIZONTAL => 'Горизонтальная снизу',
        self::POS_SIDE_VERTICAL => 'Справа вертикальная',
        self::POS_SIDE_SQUARE => 'Справа квадратная',
    ];

    const STATUS_ACTIVE = 1;
    const STATUS_NOT_ACTIVE = 0;

    public static $statuses = [
        self::STATUS_ACTIVE => 'Активна',
        self::STATUS_NOT_ACTIVE => 'Не активна',
    ];

    public static $sizes = [
        self::POS_HEAD_HORIZONTAL => ['width' => 1197, 'height' => 137],
        self::POS_FOOT_HORIZONTAL => ['width' => 730, 'height' => 90],
        self::POS_SIDE_VERTICAL => ['width' => 337, 'height' => 998],
        self::POS_SIDE_SQUARE => ['width' => 350, 'height' => 350],
    ];

    const IMAGE_WIDTH = 0;
    const IMAGE_HEIGHT = 1;

    const IMAGE_MIME_JPG = 'image/jpeg';
    const IMAGE_MIME_PNG = 'image/png';
    const IMAGE_MIME_GIF = 'image/gif';

    public static $mime_types = [
        self::IMAGE_MIME_JPG,
        self::IMAGE_MIME_PNG,
        self::IMAGE_MIME_GIF,
    ];

    const TIME_STATUS_WAIT = 1;
    const TIME_STATUS_DONE = 2;
    const TIME_STATUS_ACTIVE = 3;

    public static $time_statuses = [
        self::TIME_STATUS_WAIT => '<span class="label label-warning">Ожидает</span>',
        self::TIME_STATUS_DONE => '<span class="label label-primary">Выполнена</span>',
        self::TIME_STATUS_ACTIVE => '<span class="label label-success">Активна</span>',
    ];

    public static $time_statuses_text = [
        self::TIME_STATUS_WAIT => 'Ожидает',
        self::TIME_STATUS_DONE => 'Выполнена',
        self::TIME_STATUS_ACTIVE => 'Активна',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'advertisement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'position', 'status', 'url', 'date_start', 'date_end', 'city_id'], 'required'],
            [['user_id', 'position', 'status', 'city_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['url'], 'url'],
            [['date_start', 'date_end'], 'date', 'format' => 'php:Y-m-d'],
            [['date_start', 'date_end'], 'checkDate', 'skipOnError' => true],
            [['image'], 'checkImage', 'skipOnEmpty' => false],
            [['position'], 'checkInArray', 'params' => ['array' => array_keys(self::$positions)]],
            [['status'], 'checkInArray', 'params' => ['array' => array_keys(self::$statuses)]],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['created_at', 'image'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'ImageUpload' => [
                'class' => ImageUpload::className(),
                'attribute' => 'image',
                'id' => 'id',
                'pid' => 'pid',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('advertisement', 'ID'),
            'user_id' => Yii::t('advertisement', 'User ID'),
            'position' => Yii::t('advertisement', 'Position'),
            'status' => Yii::t('advertisement', 'Status'),
            'title' => Yii::t('advertisement', 'Title'),
            'image' => Yii::t('advertisement', 'Image'),
            'url' => Yii::t('advertisement', 'Url'),
            'date_start' => Yii::t('advertisement', 'Date Start'),
            'date_end' => Yii::t('advertisement', 'Date End'),
            'created_at' => Yii::t('advertisement', 'Created At'),
            'city_id' => Yii::t('advertisement', 'City ID'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function translateAttributes()
    {
        return ['title'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @return string|null
     */
    public function getStatusLabel()
    {
        return isset(self::$statuses[$this->status]) ? self::$statuses[$this->status] : null;
    }

    /**
     * @return string|null
     */
    public function getPositionLabel()
    {
        return isset(self::$positions[$this->position]) ? self::$positions[$this->position] : null;
    }

    public function getDisabledDatesWithoutCurrent($pos, $city)
    {
        $disabled = [];

        if ($this->position) {
            $periods = self::find()
                ->select(['date_start', 'date_end'])
                ->andFilterWhere(['>=', 'date_end', date('Y-m-d')])
                ->andFilterWhere(['not', ['id' => $this->id]])
                ->andWhere(['position' => $pos])
                ->andWhere(['city_id' => $city])
                ->asArray()->all();


            foreach ($periods as $period) {
                $disabled = array_merge($disabled, self::getDatesBetween(new DateTime($period['date_start']), new DateTime($period['date_end'])));
            }
        }

        return $disabled;
    }

    public static function getDisabledDates($pos, $city)
    {
        $periods = self::find()->select(['date_start', 'date_end'])
            ->where(['>=', 'date_end', date('Y-m-d')])->andWhere(['position' => $pos])->andWhere(['city_id' => $city])
            ->asArray()->all();
        $disabled = [];

        foreach ($periods as $period) {
            $disabled = array_merge($disabled, self::getDatesBetween(new DateTime($period['date_start']), new DateTime($period['date_end'])));
        }

        return $disabled;
    }

    public function checkDate($attribute)
    {
        //Проверка наложения интервалов
        $disabled_dates = $this->getDisabledDatesWithoutCurrent($this->position, $this->city_id);
        $input_dates = self::getDatesBetween(new DateTime($this->date_start), new DateTime($this->date_end));

        $incorrect = [];
        foreach ($input_dates as $input_date) {
            if (in_array($input_date, $disabled_dates)) {
                $incorrect[] = $input_date;
            }
        }
        if ($incorrect) {
            $incorrect = implode(', ', $incorrect);
            $this->addError('date_end', "Дни: <b>$incorrect</b> заняты, выберите другой интервал.");
        }

        //Проверка на прошлое(даты до сегодня выбирать нельзя)
        $now = strtotime(date('Y-m-d'));
        $labels = $this->attributeLabels();
        if ($this->isNewRecord and (strtotime($this->$attribute) < $now)) {
            $this->addError($attribute, "$labels[$attribute] находится в прошлом, выберите другой период.");
        }

        //Проверка на date_end > date_start
        if (strtotime($this->date_end) < strtotime($this->date_start)) {
            $this->addError('date_end', "Дата окончания не может быть раньше начала, выберите другой период.");
        }
    }

    public static function getDatesBetween(DateTime $start, DateTime $end)
    {
        $diff = $end->diff($start)->days;
        $dates = [];
        $dates[] = $start->format('Y-m-d');
        for ($i = 1; $i < $diff; $i++) {
            $dates[] = $start->modify('+1 day')->format('Y-m-d');
        }
        $dates[] = $end->format('Y-m-d');

        return $dates;
    }

    public function checkImage($attribute)
    {
        if (!$this->isNewRecord) {
            $this->$attribute = $this->oldAttributes[$attribute];
        }
        if ($filename = $_FILES[$this->getModelName()]['tmp_name'][$attribute]) {
            if (in_array(mime_content_type($filename), self::$mime_types)) {
                $properties = getimagesize($filename);
                if ($properties) {
                    $sizes = self::$sizes[$this->position];
                    if (($sizes['height'] !== $properties[self::IMAGE_HEIGHT]) or ($sizes['width'] !== $properties[self::IMAGE_WIDTH])) {
                        $this->addError($attribute, "Неверный размер изображения - {$properties[self::IMAGE_WIDTH]}*{$properties[self::IMAGE_HEIGHT]} px, должно быть {$sizes['width']}*{$sizes['height']} px.");
                    }
                } else {
                    $this->addError($attribute, 'Невозможно получить параметры изображения.');
                }
            } else {
                $this->addError($attribute, 'Неверный файл.');
            }
        }
    }

    private function getModelName()
    {
        $class = explode('\\', get_class($this), 3);
        return (!empty($class) and isset($class[count($class) - 1])) ? $class[count($class) - 1] : null;
    }

    public function checkInArray($attribute, $params)
    {
        if (isset($params['array']) and !in_array($this->$attribute, $params['array'])) {
            $this->addError($attribute, 'Задано неверное значение атрибута.');
        }
    }

    public function getTimeStatusLabel()
    {
        return isset(self::$time_statuses[$this->timeStatus]) ? self::$time_statuses[$this->timeStatus] : null;
    }

    public function getTimeStatus()
    {
        $start = strtotime("$this->date_start 00:00:01");
        $end = strtotime("$this->date_end 23:59:59");
        $now = time();

        $result = null;
        if ($now < $start) {
            $result = self::TIME_STATUS_WAIT;
        } elseif ($now > $end) {
            $result = self::TIME_STATUS_DONE;
        } else {
            $result = self::TIME_STATUS_ACTIVE;
        }

        return $result;
    }
}

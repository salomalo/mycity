<?php

namespace common\models;

use yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "counter".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $for_office
 * @property integer $for_main
 * @property integer $for_all_cities
 * @property string $created_at
 *
 * @property CounterCity[] $counterCities
 * @property City[] $cities
 * @property string $forOfficeLabel
 * @property string $forOfficeColorLabel
 * @property string $forMainLabel
 * @property string $forMainColorLabel
 * @property string $forAllCitiesLabel
 * @property string $forAllCitiesColorLabel
 * @property integer[] $citiesId
 * @property string $citiesString
 */
class Counter extends ActiveRecord
{
    const ENABLED = 1;
    const DISABLED = 0;

    public static $forOptions = [
        self::ENABLED => 'Включить',
        self::DISABLED => 'Выключить',
    ];

    public static $forOptionsHtml = [
        self::ENABLED => '<p style="color: #00cf00;">Включить</p>',
        self::DISABLED => '<p style="color: #cf0000;">Выключить</p>',
    ];

    /** @var array $cities */
    public $cities_input = [];

    /**
     * @return CounterQuery
     */
    public static function find()
    {
        return new CounterQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'counter';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'title'], 'required'],
            [['content', 'title'], 'string'],
            [['for_office', 'for_main', 'for_all_cities'], 'integer'],
            [['for_office', 'for_main', 'for_all_cities'], 'required'],
            [['created_at'], 'safe'],
            [['cities_input'], 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'content' => 'Содержимое',
            'for_office' => 'Для офиса',
            'for_main' => 'Для главной',
            'for_all_cities' => 'Для всех городов',
            'created_at' => 'Дата создания',
            'cities_input' => 'Города',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCounterCities()
    {
        return $this->hasMany(CounterCity::className(), ['counter_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(City::className(), ['id' => 'city_id'])->via('counterCities');
    }

    /**
     * @return int|string
     */
    public function getForOfficeLabel()
    {
        return isset(self::$forOptions[$this->for_office]) ? self::$forOptions[$this->for_office] : $this->for_office;
    }

    /**
     * @return int|string
     */
    public function getForOfficeColorLabel()
    {
        return isset(self::$forOptionsHtml[$this->for_office]) ? self::$forOptionsHtml[$this->for_office] : $this->for_office;
    }

    /**
     * @return int|string
     */
    public function getForMainLabel()
    {
        return isset(self::$forOptions[$this->for_main]) ? self::$forOptions[$this->for_main] : $this->for_main;
    }

    /**
     * @return int|string
     */
    public function getForMainColorLabel()
    {
        return isset(self::$forOptionsHtml[$this->for_main]) ? self::$forOptionsHtml[$this->for_main] : $this->for_main;
    }

    /**
     * @return int|string
     */
    public function getForAllCitiesLabel()
    {
        return isset(self::$forOptions[$this->for_all_cities]) ? self::$forOptions[$this->for_all_cities] : $this->for_all_cities;
    }

    /**
     * @return int|string
     */
    public function getForAllCitiesColorLabel()
    {
        return isset(self::$forOptionsHtml[$this->for_all_cities]) ? self::$forOptionsHtml[$this->for_all_cities] : $this->for_all_cities;
    }

    public function updateCityLinks()
    {
        $forCreate = [];
        $forDelete = [];

        $exist = $this->getCounterCities()->select(['city_id', 'id'])->indexBy('id')->column();

        if ($this->cities_input) {
            foreach ($exist as $id => $city_id) {
                if (!in_array($city_id, $this->cities_input)) {
                    $forDelete[] = $id;
                }
            }
            foreach ($this->cities_input as $city_id) {
                if (!in_array($city_id, $exist)) {
                    $forCreate[] = $city_id;
                }
            }
        } else {
            $forDelete = array_keys($exist);
        }

        if ($forDelete && !CounterCity::deleteAll(['id' => $forDelete])) {
            throw new Exception('Error while delete old links');
        }

        foreach ($forCreate as $city_id) {
            $this->createLink($city_id);
        }
    }

    public function createCityLinks()
    {
        if ($this->cities_input) {
            foreach ($this->cities_input as $item) {
                $this->createLink($item);
            }
        }
    }

    /**
     * @param integer $city_id
     * @throws Exception
     */
    private function createLink($city_id)
    {
        $link = new CounterCity();
        $link->counter_id = $this->id;
        $link->city_id = $city_id;
        if (!$link->save()) {
            throw new Exception(implode(', ', $link->firstErrors));
        }
    }

    /**
     * @return array
     */
    public function getCitiesId()
    {
        return ArrayHelper::getColumn($this->cities, 'id');
    }

    /**
     * @return string
     */
    public function getCitiesString()
    {
        return implode(', ', ArrayHelper::getColumn($this->cities, 'title'));
    }

    public static function clearCache()
    {
        $cities_id = City::find()->select('id')->where(['not', ['main' => City::CLOSED]])->column();

        $keys = ['counter_frontend_main'];
        foreach ($cities_id as $city) {
            $keys[] = "counter_frontend_{$city}";
        }

        if (Yii::$app->cacheOffice->exists('counter_office')) {
            Yii::$app->cacheOffice->delete('counter_office');
        }
        foreach ($keys as $key) {
            if (Yii::$app->cacheFrontend->exists($key)) {
                Yii::$app->cacheFrontend->delete($key);
            }
        }
    }
}

class CounterQuery extends ActiveQuery
{
    /**
     * @return CounterQuery
     */
    public function office()
    {
        return $this->andWhere(['for_office' => Counter::ENABLED]);
    }

    /**
     * @return CounterQuery
     */
    public function main()
    {
        return $this->andWhere(['for_main' => Counter::ENABLED]);
    }

    /**
     * @return CounterQuery
     */
    public function orAllCities()
    {
        return $this->orWhere(['for_all_cities' => Counter::ENABLED]);
    }
    
    public function city($city_id)
    {
        return $this->joinWith('counterCities')->andWhere(['counter_city.city_id' => $city_id]);
    }

    /**
     * @return CounterQuery
     */
    public function allCities()
    {
        return $this->andWhere(['for_all_cities' => Counter::ENABLED]);
    }

    /**
     * @return string[]
     */
    public function getContents()
    {
        return $this->select('content')->column();
    }
}

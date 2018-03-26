<?php

namespace common\models;

use common\behaviors\WallPost;
use common\models\traits\GetCityTrait;
use yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "schedule_kino".
 *
 * @property integer $id
 * @property integer $idAfisha
 * @property integer $idCompany
 * @property integer $idCity
 * @property string $times
 * @property string $price
 * @property string $dateStart
 * @property string $dateEnd
 * @property string $order
 * @property Business $company
 * @property Afisha $afisha
 */
class ScheduleKino extends ActiveRecord
{
    const FILM = 'normal';
    const FILM_2D = '2D';
    const FILM_3D = '3D';

    use GetCityTrait;
    public $companyTitle;
    public $scheduleCity;
    public $times2D;
    public $times3D;

    public function behaviors()
    {
        return ['WallPost' => WallPost::className()];
    }

    public static function find()
    {
        return new ScheduleQuery(get_called_class());
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'schedule_kino';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idCompany', 'dateStart', 'dateEnd'], 'required','message' => 'Поле не может быть пустым'],
            [['idAfisha'], 'required', 'on' => ['create', 'update']],
            [['idAfisha', 'idCompany', 'idCity'], 'integer'],
            [['dateStart', 'dateEnd', 'times', 'times2D', 'times3D'], 'safe'],
            [['price'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idAfisha' => 'Кино',
            'idCompany' => 'Id Company',
            'times' => 'Время показа',
            'price' => 'Цена',
            'dateStart' => 'Date Start',
            'dateEnd' => 'Date End',
            'idCity' => 'Город',
            'companyTitle' => 'Компания',
            'scheduleCity' => 'Город',
            'times2D' => 'Расписание 2D',
            'times3D' => 'Расписание 3D',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            /** @var Business $model */
            $model = Business::find()->select(['idCity'])->where(['id' => $this->idCompany])->one();
            if ($model) {
                $this->idCity = $model->idCity;
            }
            if ($this->times or $this->times2D or $this->times3D) {
                $times = [
                    self::FILM => $this->times,
                    self::FILM_2D => $this->times2D,
                    self::FILM_3D => $this->times3D,
                ];
                $timesSort = [];
                foreach ($times as $key => $time) {
                    if (is_array($time) and !empty($time)) {
                        $time = array_filter($time);
                        asort($time, SORT_NUMERIC);
                        $timesSort[$key] = implode(',', $time);
                    }
                }
                $this->times = json_encode($timesSort);
            }
            return true;
        } else {
            return false;
        }
        
    }
    
    public function afterFind()
    {
        if ($times = json_decode($this->times, true)) {
            $this->times = null;
            foreach ($times as $key => $time) {
                switch ($key) {
                    case self::FILM:
                        $this->times = $this->explodeTimes($time);
                        break;
                    case self::FILM_2D:
                        $this->times2D = $this->explodeTimes($time);
                        break;
                    case self::FILM_3D:
                        $this->times3D = $this->explodeTimes($time);
                        break;
                    default:
                        continue;
                }
            }
        } else {
            $this->times = null;
        }
    }

    /**
     * @param $time string
     * @return array|null
     */
    private function explodeTimes($time)
    {
        $t = explode(',', $time);
        if (is_array($t)) {
            return $t;
        } elseif (!empty($this->times)) {
            return [$this->times];
        } else {
            return null;
        }
    }

    public function getTimes($times, $sep = ', ')
    {
        return !empty($times) ?
            (is_array($times) ? implode($sep, $times) : ((string)$times ? $times : ''))
            : '';
    }
    
    public function getCompany()
    {
        if ($this->idCompany) {
            return $this->hasOne(Business::className(), ['id' => 'idCompany'])->select([
                'id', 'title', 'image', 'url', 'idCity'
            ]);
        } else {
            return new Business();
        }
    }
    
    public function getAfisha()
    {
        return $this->hasOne(Afisha::className(), ['id' => 'idAfisha']);
    }
}

class ScheduleQuery extends ActiveQuery
{
    /**
     * @param null|string $selDate
     * @return $this
     */
    public function active($selDate = null)
    {
        $date = ($selDate)? date('Y-m-d', strtotime($selDate)) : date('Y-m-d');

        return $this->andWhere(['<=', 'dateStart', "$date 23:59:59"])->andWhere(['>=', 'dateEnd', "$date 00:00:01"]);
    }

    /**
     * @param null|string $selDate
     * @return $this
     */
    public function activeAndFuture($selDate = null)
    {
        $date = $selDate ? date('Y-m-d', strtotime($selDate)) : date('Y-m-d');

        return $this->andWhere(['>=', 'dateEnd', "$date 00:00:01"]);
    }
}

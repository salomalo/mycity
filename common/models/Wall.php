<?php

namespace common\models;

use common\models\traits\GetCityTrait;
use DateTime;
use yii;
use yii\db\Expression;
use yii\db\ActiveRecord;
use common\components\ActiveRecordMultiLang;

/**
 * This is the model class for table "wall".
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $type
 * @property integer $idCity
 * @property string $title
 * @property string $description
 * @property string $url
 * @property string $image
 * @property string $dateCreate
 * @property integer $published
 * @property City $city
 * @property Post|Afisha|Action $mod
 */
class Wall extends ActiveRecordMultiLang
{
    use GetCityTrait;
    public static $types = [
        File::TYPE_POST => 'Новости',
        File::TYPE_ACTION => 'Акции',
        File::TYPE_AFISHA => 'Афиша',
        File::TYPE_AFISHA_WITH_SCHEDULE => 'Кино с расписанием',
        File::TYPE_AFISHA_WITHOUT_SCHEDULE => 'Кино без расписания',
    ];

    public static $typesAlias = [
        File::TYPE_POST => 'post',
        File::TYPE_ACTION => 'action',
        File::TYPE_AFISHA => 'afisha',
        File::TYPE_AFISHA_WITH_SCHEDULE => 'afisha',
        File::TYPE_AFISHA_WITHOUT_SCHEDULE => 'afisha',
    ];
     
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'value' => new Expression('NOW()'),
                'attributes' => array(
                    ActiveRecord::EVENT_BEFORE_INSERT => 'dateCreate',
                ),
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wall';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'type'], 'required'],
            [['pid', 'type', 'idCity', 'published'], 'integer'],
            [['title', 'description'], 'string'],
            [['dateCreate'], 'safe'],
            [['url', 'image'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'type' => 'Тип',
            'title' => 'Заголовок',
            'description' => 'Описание',
            'url' => 'Url',
            'image' => 'Фото',
            'dateCreate' => 'Дата создания',
            'idCity' => 'Город',
            'published' => 'Опубликованно',
        ];
    }
    
    public function translateAttributes()
    {
        return [
            'title',
            'description',
        ];
    }

    public function getMod()
    {
        if ($this->type == File::TYPE_POST) {
            return $this->hasOne(Post::className(), ['id' => 'pid']);
        } elseif ($this->type == File::TYPE_ACTION) {
            return $this->hasOne(Action::className(), ['id' => 'pid']);
        } elseif ($this->type == File::TYPE_AFISHA) {
            return $this->hasOne(Afisha::className(), ['id' => 'pid']);
        }
    }

    /**
     * @param string $idCity
     * @return Wall[]
     */
    public static function getUnpublished($idCity)
    {
        return Wall::find()->where([
            'published' => null,
            'idCity' => (int)$idCity
        ])->orderBy(['dateCreate' => SORT_ASC])->all();
    }

    /**
     * @return Wall[]
     */
    public static function getOldUnpublished()
    {
        $now = date('Y-m-d H:i:s');
        $afisha = Wall::find()->where([
            'published' => null,
            'type' => File::TYPE_AFISHA,
        ]);
        $films = clone $afisha;
        $afisha->leftJoin('afisha', '"afisha"."id" = "wall"."pid"');
        $afisha->andWhere(['<', '"afisha"."dateEnd"', $now]);
        $afisha = $afisha->all();

        $films->leftJoin('afisha', '"afisha"."id" = "wall"."pid"');
        $films->andWhere(['=', '"afisha"."isFilm"', 1]);
        $films->leftJoin('schedule_kino', '"schedule_kino"."idAfisha" = "wall"."pid"');
        $films->andWhere(['<', '"schedule_kino"."dateEnd"', $now]);
        $films = $films->all();

        return array_merge($afisha, $films);
    }

    /**
     * @param string $idCity
     * @return int
     */
    public static function getCountUnpublished($idCity)
    {
        return Wall::find()->where([
            'published' => null,
            'idCity' => (int)$idCity
        ])->count();
    }

    /**
     * @param string $idCity
     * @return Wall
     */
    public static function getUnpublishedOne($idCity)
    {
        $hourAgo = new DateTime();
        $hourAgo = $hourAgo->modify('-1 hour')->format('Y-m-d H:i:s');

        return Wall::find()
            ->where(['published' => null, 'idCity' => (int)$idCity])
            ->andWhere(['<=', 'dateCreate', $hourAgo])
            ->orderBy(['dateCreate' => SORT_ASC])
            ->one();
    }
}

<?php

namespace common\models;

use Yii;
use common\components\ActiveRecordMultiLang;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "city".
 *
 * @property integer $id
 * @property integer $idRegion
 * @property string $title
 * @property string $title_ge
 * @property string $titleAbout
 * @property string $seo_title
 * @property string $seo_description
 * @property string $seo_keywords
 * @property string $code
 * @property string $subdomain
 * @property string $about
 * @property integer $vk_public_id
 * @property integer $vk_public_admin_id
 * @property string $vk_public_admin_token
 *
 * @property CityDetail $detail
 * @property Region $region
 * @property int $main
 * @property string $image
 * @property Counter[] $counters
 * @property CounterCity[] $counterCities
 */
class City extends ActiveRecordMultiLang
{
    const IMAGE_PATH = '/img/gerb/h240/';
    
    const ACTIVE = 2;
    const PENDING = 1;
    const CLOSED = 0;

    public static $status = [
        self::ACTIVE => 'Активные',
        self::PENDING => 'В подготовке',
        self::CLOSED => 'Выключенные',
    ];

    public static $deniedControllers = ['afisha', 'post', 'action', 'ads', 'resume', 'vacantion'];

    public $reg;
    public $regId;
    
    public $titleAbout;
    public $about;
    public $seo_title;
    public $seo_description;
    public $seo_keywords;
    public $google_analytic;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idRegion', 'title', 'title_ge', 'main'], 'required','message' => 'Поле не может быть пустым'],
            [['idRegion', 'main', 'vk_public_id', 'vk_public_admin_id'], 'integer'],
            [['title', 'title_ge', 'titleAbout', 'about', 'seo_title', 'seo_description', 'seo_keywords', 'google_analytic', 'vk_public_admin_token'], 'string'],
            [['image'], 'file', 'maxSize' => Yii::$app->params['maxSizeUpload'], 'extensions'=>'jpg, gif, png'],
            [['subdomain'], 'string', 'max' => 50],
            [['code'], 'integer', 'max' => 9999999]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idRegion' => 'Id Region',
            'title' => 'Title',
            'title_ge' => 'Родительный падеж',
            'code' => 'Code',
            'subdomain' => 'Subdomain',
            
            'titleAbout' => 'Заголовок о городе',
            'about' => 'О городе',
            'seo_description'=>'SEO Description',
            'seo_keywords'=>'SEO Ключевые слова',
            'seo_title' => 'Seo Title',
            'main' => 'Статус',
            'image' => 'Картинка',
            'google_analytic' => 'Google Analytic Code',
            'vk_public_admin_id' => 'id Администратора группы',
            'vk_public_admin_token' => 'Токен администратратора',
            'vk_public_id' => 'id группы ВК',
        ];
    }
    
    public function translateAttributes()
    {
        return ['title', 'title_ge'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'idRegion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetail()
    {
        return $this->hasOne(CityDetail::className(), ['id' => 'id']);
    }

    public static function getAll(array $where = null)
    {
        if ($where) {
            return ArrayHelper::map(self::find()->where($where)->select(['id', 'title'])->orderBy(['main' => SORT_DESC, 'id' => SORT_ASC])->all(), 'id', 'title');
        } else {
            return ArrayHelper::map(self::find()->select(['id', 'title'])->all(), 'id', 'title');
        }
    }

    public function beforeSave($insert)
    {
        $this->code             = substr($this->code, 0, 7);
        $this->subdomain        = strip_tags($this->subdomain);

        $this->seo_title        = strip_tags($this->seo_title);
        $this->seo_description  = strip_tags($this->seo_description);
        $this->seo_keywords     = strip_tags($this->seo_keywords);

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $detail = CityDetail::findOne(['id' => $this->id]);
        $detail = ($detail) ? $detail : (new CityDetail());
        /* @var $detail CityDetail*/
        $detail->id = $this->id;
        $detail->title = $this->titleAbout;
        $detail->about = $this->about;
        $detail->seo_title = $this->seo_title;
        $detail->seo_description = $this->seo_description;
        $detail->seo_keywords = $this->seo_keywords;
        $detail->google_analytic = $this->google_analytic;
        $detail->save();

        parent::afterSave($insert, $changedAttributes);
    }

    public function bindCityInfo()
    {
        $this->about = isset($this->detail->about)                      ? $this->detail->about              : '';
        $this->seo_title = isset($this->detail->seo_title)              ? $this->detail->seo_title          : '';
        $this->seo_description = isset($this->detail->seo_description)  ? $this->detail->seo_description    : '';
        $this->seo_keywords =isset($this->detail->seo_keywords)         ? $this->detail->seo_keywords       : '';
        $this->google_analytic =isset($this->detail->google_analytic)   ? $this->detail->google_analytic       : '';

        return $this;
    }

    public function afterDelete()
    {
        if($detail = CityDetail::findOne(['id' => $this->id])) {
            $detail->delete();
        }
        parent::afterDelete();
    }

    public function getGoogleAnalyticCode()
    {
        return empty($this->detail) || empty($this->detail->google_analytic) ? 'UA-70322908-1' : $this->detail->google_analytic;
    }

    public static function getCityList(){
        $list = [];
        $cities = self::find()
            ->leftJoin('region', '"region"."id" = "city"."idRegion"')
            ->orderBy('title ASC')
            ->all();
        foreach ($cities as $city){
            $list[$city->id] = $city->title . ', ' . $city->region->title;
        }
        return $list;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCounterCities()
    {
        return $this->hasMany(CounterCity::className(), ['city_id' => 'id']);
    }

    /**
     * @return CounterQuery
     */
    public function getCounters()
    {
        return $this->hasMany(Counter::className(), ['id' => 'counter_id'])->via('counterCities');
    }
}

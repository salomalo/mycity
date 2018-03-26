<?php

namespace common\models;

use common\behaviors\ImageUpload;
use common\models\queries\ActionQuery;
use yii;
use common\behaviors\WallPost;
use common\components\ActiveRecordMultiLang;

/**
 * This is the model class for table "action".
 *
 * @property integer $id
 * @property integer $idCompany
 * @property integer $idCategory
 * @property string $title
 * @property string $description
 * @property double $price
 * @property string $dateStart
 * @property string $dateEnd
 * @property string $url
 * @property string $tags
 * @property string $image
 * @property string $dateUpdate
 *
 * @property Business $companyName
 * @property ActionCategory $category
 * @property CountViews $countView
 * @property Comment[] $comment
 */
class Action extends ActiveRecordMultiLang
{
    public $companyTitle;
    public $actionCity;

    const STATUS_WAIT = 1;
    const STATUS_LAUNCH = 2;
    const STATUS_END = 3;

    public static $statusList = [
        self::STATUS_WAIT => 'Ожидает',
        self::STATUS_LAUNCH => 'Запущена',
        self::STATUS_END => 'Закончилась',
    ];

    public $status;
    /**
     * Поле используется при запросе в бд для сохранения кол-ва комментариев
     * @var integer $comment_count
     */
    public $comment_count;

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => 'common\behaviors\Slug',
                'in_attribute' => 'title',
                'out_attribute' => 'url',
                'translit' => true,
                'max_len' => 200,
            ],
            'ImageUpload' => [
                'class' => ImageUpload::className(),
                'attribute' => ['image'],
                'id' => 'id',
                'pid' => 'pid',
            ],
            'WallPost' => WallPost::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'action';
    }

    /**
     * @return ActionQuery
     */
    public static function find()
    {
        return Yii::createObject(ActionQuery::className(), [get_called_class()]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idCompany', 'title', 'description', 'idCategory', 'dateStart', 'dateEnd'], 'required','message' => 'Поле не может быть пустым'],
            [['idCompany', 'idCategory'], 'integer'],
            [['title', 'description','seo_description','seo_keywords','seo_title'], 'string'],
            [['price'], 'number'],
            [['dateStart', 'dateEnd','url','dateUpdate','sitemap_en','sitemap_priority','sitemap_changefreq', 'tags', 'status'], 'safe'],
            [['image'], 'file', 'maxSize' => Yii::$app->params['maxSizeUpload'], 'extensions'=>'jpg, gif, png'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idCompany' => 'Предприятие',
            'idCategory' => 'Категория',
            'title' => 'Название акции',
            'description' => 'Описание',
            'image' => 'Фото',
            'price' => 'Цена',
            'dateStart' => 'Дата начала',
            'dateEnd' => 'Дата окончания',
            'seo_description'=>'SEO Description',
            'seo_keywords'=>'SEO Ключевые слова',
            'seo_title' => 'Seo Title',
            'dateUpdate' => 'Дате обновления',
            'sitemap_en' => 'Добавить в sitemap',
            'sitemap_priority' => 'Sitemap приоритет',
            'sitemap_changefreq' => 'Sitemap частота изменения',
            'companyTitle' => 'Компания',
            'actionCity' => 'Город',
            'tags' => 'Теги',

        ];
    }
    
    public function translateAttributes()
    {
        return [
            'title',
            'description',
            'seo_description',
            'seo_keywords',
            'seo_title',
            'tags',
        ];
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getCompanyName()
    {
        return $this->hasOne(Business::className(), ['id' => 'idCompany']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(ActionCategory::className(), ['id'=>'idCategory']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getComment()
    {
        return $this->hasMany(Comment::className(), ['pid' => 'id'])->andOnCondition(['comment.type' => File::TYPE_ACTION]);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getCountView()
    {
        return $this->hasOne(CountViews::className(), ['pid' => 'id'])->andOnCondition(['count_views.type' => File::TYPE_ACTION]);
    }

    /**
     * @param int $id
     * @return int
     */
    public function getComments($id)
    {
        $count = Comment::find()->where(['type' => File::TYPE_ACTION, 'pid' => $id])->asArray()->count();
        return ($count)? $count : 0;
    }

    public function getRoute()
    {
        if (empty(Yii::$app->request->city)) {
            $subdomain = ($this->companyName and $this->companyName->city) ? $this->companyName->city->subdomain : null;
            return ['action/view', 'alias' => $this->id . '-' . $this->url, 'subdomain' => $subdomain];
        }
        return ['action/view', 'alias' => "{$this->id}-{$this->url}"];
    }

    public function getSubDomain()
    {
        if (empty(Yii::$app->request->city)) {
            $subdomain = ($this->companyName and $this->companyName->city) ? $this->companyName->city->subdomain : null;
            if ($subdomain == null){
                return $subdomain;
            } else {
                return ($subdomain . '.');
            }
        }

        return !empty($this->city) ? ($this->city->subdomain . '.') : null;
    }

    public function getFrontendUrl()
    {
        return '/action/' . $this->id;
    }
    
    public static function getArrayIdCompany($idUser)
    {
        $model = Business::find()->select('id')->where(['idUser'=>$idUser])->all();
        $list = [];
        foreach ($model as $item) {
            $list[] = $item->id;
        }
        return $list;
    }

    public function beforeSave($insert)
    {
        $this->title = strip_tags($this->title);
        if (parent::beforeSave($insert)) {
            $this->dateUpdate = date('Y-m-d');
            $this->tags = (is_array($this->tags)) ? implode(', ', $this->tags) : '';
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $dateStart
     * @param $dateEnd
     * @return array
     */
    public function getRemainingTime($dateStart, $dateEnd)
    {

        $timeZone = new \DateTimeZone(Yii::$app->params['timezone']);
        $start = new \DateTime($dateStart, $timeZone);
        $end = new \DateTime($dateEnd, $timeZone);
        $now = new \DateTime("now", $timeZone);

        $interval = $now->diff($end);

        $style = '';
        $left = '';
        $text = '';
        $leftWithOutText = '';

        if ($interval->m > 0) {   // больше месяца
            $leftTextM = $this->getLeftText($interval->m, 'm');
            $text = $leftTextM[0];
            $left = $leftTextM[0] . ' ' . $interval->m . ' ' . $leftTextM[1];
            $leftWithOutText = $interval->m . ' ' . $leftTextM[1];
            if ($interval->d > 0) {   // есть месяцы и дни
                $leftTextD = $this->getLeftText($interval->d, 'd');
                $leftWithOutText = $leftWithOutText . ' ' . $interval->d . ' ' . $leftTextD[1];
                $left = $left . ' ' . $interval->d . ' ' . $leftTextD[1];
            }

        }

        if ($interval->m == 0 && $interval->d > 0) {  // меньше месяца, интервал в днях

            $leftText = $this->getLeftText($interval->d, 'd');
            $text = $leftText[0];
            $leftWithOutText = $interval->d . ' ' . $leftText[1];
            $left = $leftText[0] . ' ' . $interval->d . ' ' . $leftText[1];

//            if($interval->d == 1){
//                $left = Yii::t('action', 'RemainedDay') . ' ' . $interval->d . ' ' . Yii::t('action', 'day') ;
//            }
//            if($interval->d > 1 && $interval->d < 5){
//                $left = Yii::t('action', 'Left') . ' ' . $interval->d . ' ' . Yii::t('action', 'days');
//            }
//            if($interval->d >= 5){
//                $left = Yii::t('action', 'Left') . ' ' . $interval->d . ' ' . Yii::t('action', 'dayss');
//            }
        }

        if ($interval->m == 0 && $interval->d == 0) {  // меньше дня

            if ($interval->h > 0) {   // интервал в часах
                $leftText = $this->getLeftText($interval->h, 'h');
                $text = $leftText[0];
                $leftWithOutText = $interval->h . ' ' . $leftText[1];
                $left = $leftText[0] . ' ' . $interval->h . ' ' . $leftText[1];
            }

            if ($interval->h < 1) {   // меньше часа, интервал в минутах
                $leftText = $this->getLeftText($interval->i, 'i');
                $text = $leftText[0];
                $leftWithOutText = $interval->i . ' ' . $leftText[1];
                $left = $leftText[0] . ' ' . $interval->i . ' ' . $leftText[1];
            }
        }

        if ($interval->m == 0 && $interval->d <= 3) {  // меньше 3-х дней
            $style = 'ostatok-warning';
        }

        return [
            'start' => $start,
            'end' => $end,
            'now' => $now,
            'interval' => $interval,
            'style' => $style,
            'left' => $left,
            'leftText' => $text,
            'leftWithOutText' => $leftWithOutText,
        ];
    }
    
    private function getLeftText($value, $type)
    {
        if (($value > 20) and (substr($value, -1) !== '0')) $value = substr($value, -1);
        if($value == 0){
            switch ($type){
                default : $arr[] = ''; $arr[] = '';
            }
        }
        
        if($value == 1){
            switch ($type){
                case 'm': $arr[] = Yii::t('action', 'RemainedDay'); $arr[] = Yii::t('action', 'month'); break;
                case 'd': $arr[] = Yii::t('action', 'RemainedDay'); $arr[] = Yii::t('action', 'day'); break;
                case 'h': $arr[] = Yii::t('action', 'RemainedHour'); $arr[] = Yii::t('action', 'h'); break;
                case 'i': $arr[] = Yii::t('action', 'remained'); $arr[] = Yii::t('action', 'minute'); break;
                default : $arr[] = ''; $arr[] = '';
            }
            
        }
        if($value > 1 && $value < 5){
            switch ($type){
                case 'm': $arr[] = Yii::t('action', 'Left'); $arr[] = Yii::t('action', 'months'); break;
                case 'd': $arr[] = Yii::t('action', 'Left'); $arr[] = Yii::t('action', 'days'); break;
                case 'h': $arr[] = Yii::t('action', 'Left'); $arr[] = Yii::t('action', 'hours'); break;
                case 'i': $arr[] = Yii::t('action', 'Left'); $arr[] = Yii::t('action', 'minutes'); break;
                default : $arr[] = ''; $arr[] = '';
            }
            
        }
        if($value >= 5){
            switch ($type){
                case 'm': $arr[] = Yii::t('action', 'Left'); $arr[] = Yii::t('action', 'monthss'); break;
                case 'd': $arr[] = Yii::t('action', 'Left'); $arr[] = Yii::t('action', 'dayss'); break;
                case 'h': $arr[] = Yii::t('action', 'Left'); $arr[] = Yii::t('action', 'hourss'); break;
                case 'i': $arr[] = Yii::t('action', 'Left'); $arr[] = Yii::t('action', 'minutess'); break;
                default : $arr[] = ''; $arr[] = '';
            }
            
        }
        
        return $arr;
    }

    public function getSearchShortView()
    {
        return '/action/_action_short';
    }
}

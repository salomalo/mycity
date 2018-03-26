<?php

namespace common\models;

use yii;
use yii\helpers\ArrayHelper;
use common\behaviors\ImageUpload;
use common\behaviors\Slug;
use common\behaviors\WallPost;
use common\components\ActiveRecordMultiLang;
use frontend\controllers\AfishaController;

/**
 * This is the model class for table "afisha".
 *
 * @property integer $id
 * @property integer $rating
 * @property integer $times
 * @property integer $price
 * @property integer $isFilm
 * @property integer $genre
 * @property integer $year
 * @property integer $repeat
 * @property array $idsCompany
 * @property string $idCategory
 * @property string $title
 * @property string $description
 * @property string $image
 * @property string $dateStart
 * @property string $dateEnd
 * @property string $dateUpdate
 * @property string $country
 * @property string $director
 * @property string $actors
 * @property string $budget
 * @property string $trailer
 * @property string $fullText
 * @property string $tags
 * @property string $url
 * @property string $seo_title
 * @property string $sitemap_en
 * @property string $sitemap_priority
 * @property string $sitemap_changefreq
 * @property Business[] $companys
 * @property integer $isChecked
 * @property AfishaController | \office\controllers\AfishaController | \backend\controllers\AfishaController $context
 * @property AfishaCategory $afisha_category
 * @property ScheduleKino | ScheduleKino[] $schedule_kino
 * @property AfishaCategory $category
 * @property AfishaWeekRepeat $afishaWeekRepeat
 * @property Business[] $business
 * @property KinoGenre[] $genres
 */
class Afisha extends ActiveRecordMultiLang
{
    public $always;
    public $repeatDays;

    const IMAGE_WIDTH = 0;
    const IMAGE_HEIGHT = 1;

    const REPEAT_NONE = 0;
    const REPEAT_WEEK = 1;
    const REPEAT_DAY = 2;

    public static $repeat_type = [
        self::REPEAT_NONE => 'Без повторения',
        self::REPEAT_WEEK => 'Каждую неделю',
        self::REPEAT_DAY => 'Каждый день',
    ];

    const FILM = 'isFilm';
    const EVENT = 'noFilm';

    public function behaviors()
    {
        return [
            'slug' => [
                'class'    => Slug::className(),
                'in_attribute' => 'title',
                'out_attribute' => 'url',
                'translit' => true,
                'max_len' => 200,
            ],
            'ImageUpload' => [
                'class' => ImageUpload::className(),
                'attribute' => 'image',
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
        return 'afisha';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'idCategory'], 'required', 'message' => 'Поле не может быть пустым'],
            [['description'], 'string', 'min' => 50, 'on' => self::EVENT],
            [['description'], 'required', 'on' => self::EVENT],
            [['description'], 'string', 'on' => self::FILM],
            [['image'], 'safe'],
            [['image'], 'file', 'maxSize' => Yii::$app->params['maxSizeUpload'], 'extensions'=>'jpg, gif, png'],
            [['genre'], 'required', 'on' => self::FILM],
            [['idsCompany'], 'required', 'on' => self::EVENT],
            [['title', 'fullText', 'country', 'director', 'actors', 'seo_description', 'seo_keywords', 'seo_title'], 'string'],
            [['dateStart', 'dateEnd', 'trailer', 'url', 'times', 'dateUpdate', 'sitemap_en', 'sitemap_priority', 'sitemap_changefreq', 'isChecked', 'tags', 'repeatDays', 'idsCompany'], 'safe'],
            [['rating', 'idCategory', 'isFilm', 'year', 'order', 'repeat'], 'integer'],
            [['price', 'budget'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                    => 'ID',
            'idsCompany'            => 'Предприятия',
            'idCategory'            => 'Категория',
            'title'                 => 'Заголовок',
            'description'           => 'Описание',
            'image'                 => 'Фото',
            'dateStart'             => 'Дата начала',
            'dateEnd'               => 'Дата окончание',
            'rating'                => 'Рейтинг',
            'times'                 => 'Times',
            'price'                 => 'Цена',
            'isFilm'                => 'Фильм',
            'genre'                 => 'Жанр',
            'year'                  => 'Год выпуска',
            'country'               => 'Страна',
            'director'              => 'Режиссер',
            'actors'                => 'Актеры',
            'budget'                => 'Бюджет',
            'trailer'               => 'Трейлер',
            'fullText'              => 'Подробное онисание',
            'seo_description'       => 'SEO Description',
            'seo_keywords'          => 'SEO Ключевые слова',
            'seo_title'             => 'Seo Title',
            'dateUpdate'            => 'Дате обновления',
            'sitemap_en'            => 'Добавить в sitemap',
            'sitemap_priority'      => 'Sitemap приоритет',
            'sitemap_changefreq'    => 'Sitemap частота изменения',
            'tags'                  => 'Теги',
            'order'                 => 'Порядок сортировки',
            'always'                => 'Круглосуточно',
            'repeat'                => 'Повторение',
            'repeatDays'            => 'Дни повторения',
            'isChecked'             => 'Is Checked',
        ];
    }

    public function translateAttributes()
    {
        return [
            'title', 'description', 'country', 'director', 'actors', 'fullText', 'seo_description', 'seo_keywords',
            'seo_title', 'tags',
        ];
    }

    public function beforeValidate()
    {
        if ($this->scenario != 'default' && !$this->isFilm) {
            if (!is_array($this->times)) {
                $this->times = [];
            }

            if (!is_array($this->idsCompany)) {
                $this->idsCompany = [];
                $this->addError('idsCompany', 'Поле не может быть пустым');
            }
        }

        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        $this->title = strip_tags($this->title);
        if (is_null($this->repeat)) {
            $this->repeat = 0;
        } elseif (in_array((int)$this->repeat, [self::REPEAT_DAY, self::REPEAT_WEEK])) {
            $this->dateEnd = $this->dateStart;
        }

        $this->dateUpdate = date('Y-m-d');

//        if ($this->isNewRecord && $this->isFilm) {
//            $this->idsCompany = [];
//        }

        $companies = is_array($this->idsCompany) ? $this->idsCompany : [$this->idsCompany];
        $this->idsCompany = $this->php_to_postgres_array($companies);

        $this->tags = (is_array($this->tags)) ? implode(', ', $this->tags) : '';
        
        if ($this->isFilm && $this->genre) {
            $this->genre = $this->php_to_postgres_array($this->genre);
        } else {
            $this->genre = null;
            if (!$this->always) {
                $implode = $this->times ? implode(', ', $this->times) : '';
                $implode = str_replace('Круглосуточно', '', $implode);
                $this->times = $implode ? $implode : '';
            } else {
                $this->times = 'always';
            }
        }

        return parent::beforeSave($insert);
    }

    public function php_to_postgres_array($phpArray)
    {
        return '{' . implode(',', $phpArray) . '}';
    }

    public function companyNames($array)
    {
        //return $this->hasOne(BusinessCategory::className(), ['id'=>'idCategories[]']);
        $list = [];
        if (is_array($array) && $array[0] != '') {

            foreach ($array as $item) {
                $cat = Business::find()->select(['title'])->where(['id' => $item])->one();
                $list[] = $cat['title'];
            }
        }

        return implode(', ', $list);
    }

    public function getCompanys()
    {
        $models = [];
        if (!$this->isFilm) {
            if (is_array($this->idsCompany)) {
                foreach ($this->idsCompany as $company) {
                    if ((int)$company) {
                        $models[] = Business::find()->where(['id' => (int)$company])->one();
                    }
                }
            }
        } else {
            if (is_array($this->schedule_kino)) {
                foreach ($this->schedule_kino as $schedule) {
                    $models[] = $schedule->company;
                }
            } else {
                $models[] = isset($this->schedule_kino) ? $this->schedule_kino->company : null;
            }
        }

        return $models;
    }

    public function getComments($id)
    {
        $count = Comment::find()->where(['type' => File::TYPE_AFISHA, 'pid' => $id])->asArray()->count();

        return ($count) ? $count : 0;
    }

    public static function yearList()
    {
        $list = [];
        for ($i = 1900; $i <= (date('Y') + 1); $i++) {
            $list[$i] = $i;
        }

        return array_reverse($list, true);
    }

    public function genreNames($array)
    {
        $list = [];
        if (!empty($array)) {
            if (!is_array($array)) {
                $array = [(int)$array];
            }
            $genres = KinoGenre::getAll();
            foreach ($array as $item) {
                if (!empty($item)) {
                    $list[] = $genres[(int)$item];
                }
            }
        }

        return implode(', ', $list);
    }

    public function afterFind()
    {
        $this->genre = explode(',', trim($this->genre, '{}'));
        $this->idsCompany = explode(',', trim($this->idsCompany, '{}'));
        if ($this->times !== 'always') {
            $this->times = array_filter(explode(',', $this->times));
        } else {
            $this->times = 'Круглосуточно';
            $this->always = true;
        }
        $this->repeatDays = empty($this->afishaWeekRepeat) ? [] : $this->afishaWeekRepeat->arrayOfDays;
    }

    public function getTimes($times, $sep = ', ')
    {
        return is_string($times) ? $times : implode($sep, $times);
    }

    public function getDays($days)
    {
        $sep = ', ';
        $daysTitle = [];
        if (is_array($days)) {
            foreach ($days as $day) {
                $daysTitle[] = isset(AfishaWeekRepeat::$days[$day]) ? AfishaWeekRepeat::$days[$day] : null;
            }
            $days = implode($sep, $daysTitle);
        }
        return (string)$days;
    }

    public static function getCompanyList($idUser)
    {
        $idList = [];
        $business = Business::find()->select(['id'])->where(['idUser' => $idUser])->all();
        foreach ($business as $item) {
            $idList[] = $item->id;
        }

        return '{' . join(',', $idList) . '}';
    }

    public function getCategory()
    {
        return $this->hasOne(AfishaCategory::className(), ['id' => 'idCategory']);
    }

    public function schedule($selDate = null)
    {
        $cities = array();
        if (Yii::$app->params['SUBDOMAINID'] == 0){
            $cities_db = City::find()->where(['main' => '2'])->all();
            foreach ($cities_db as $city_in_db) {
                $cities[] = $city_in_db->id;
            }
        } else {
            $cities[] = Yii::$app->params['SUBDOMAINID'];
        }

        return ScheduleKino::find()
            ->where(['idAfisha' => $this->id, 'idCity' => $cities])
            ->active($selDate)
            ->orderBy('id ASC')
            ->all();
    }

    public function scheduleByBusiness($selDate = null, $selBusy = null)
    {
        if ($selBusy) {
            $return = ScheduleKino::find()
                ->where([
                    'idAfisha' => $this->id,
                    'idCity' => Yii::$app->params['SUBDOMAINID'],
                    'idCompany' => $selBusy,
                ])
                ->andWhere(['>=', 'dateEnd', $selDate])
                ->orderBy('id ASC')->all();
        } else {
            $return = [];
        }

        return $return;
    }

    public function afterSave($insert, $changedAttributes = null)
    {
        $file = File::find()->where(['name' => $this->trailer])->one();
        if ($file) {
            $file->pid = $this->id;
            $file->update();
        }
        AfishaWeekRepeat::saveDays($this);
        
        parent::afterSave($insert, $changedAttributes);
    }

    public function beforeDelete()
    {
        if ($this->isFilm) {
            ScheduleKino::deleteAll(['idAfisha' => $this->id]);
        }
        if ($this->afishaWeekRepeat) {
            AfishaWeekRepeat::deleteAll(['afisha_id' => $this->id]);
        }
        return parent::beforeDelete();
    }

    public static function getLastEndDate($id)
    {
        $schedule = ScheduleKino::find()->where(['idAfisha' => $id])->select(['dateEnd'])->orderBy('dateEnd DESC')->asArray()->one();

        return isset($schedule['dateEnd']) ? $schedule['dateEnd'] : '1990-01-01';
    }

    public function getafisha_category()
    {
        return $this->hasOne(AfishaCategory::className(), ['id' => 'idCategory']);
    }

    public function getSchedule_kino()
    {
        return $this->hasMany(ScheduleKino::className(), ['idAfisha' => 'id']);
    }

    public static function getAll($where)
    {
        return ArrayHelper::map(Afisha::find()->where($where)->all(), 'id', 'title');
    }

    public function getAfishaWeekRepeat()
    {
        return $this->hasOne(AfishaWeekRepeat::className(), ['afisha_id' => 'id']);
    }

    public function getFrontendUrl()
    {
        return '/afisha/' . $this->id;
    }

    public function getSubDomain()
    {
        return !empty($this->companys[0]->city) ? ($this->companys[0]->city->subdomain . '.') : null;
    }

    public function getBusiness()
    {
        return $this->hasMany(Business::className(), ['id' => 'idsCompany']);
    }

    public function getIdCity()
    {
        $result = null;
        if (!$this->isFilm) {
            $result = $this->business[0]->idCity;
        } else {
            $result = $this->schedule_kino[0]->idCity;
        }
        return (int)$result;
    }

    public function getVideo()
    {
        return $this->trailer;
    }

    public function getGenres()
    {
        if (!is_array($this->genre) and (int)$this->genre) {
            $this->genre = [(int)$this->genre];
        }
        $genre = [];
        foreach ($this->genre as $genre) {
            $genre ? $genres[] = (int)$genre : false;
        }
        $this->genre = $genre;
        if ($this->genre) {
            return KinoGenre::find()->where(['id' => $this->genre])->all();
        }
        return null;
    }

    public function checkImage($attribute)
    {
        if (!$this->isNewRecord) {
            $this->$attribute = $this->oldAttributes[$attribute];
        }
        if (isset($_FILES[$this->getModelName()]) and ($filename = $_FILES[$this->getModelName()]['tmp_name'][$attribute])) {
            $properties = @getimagesize($filename);
            if ($properties) {
                if ((350 > $properties[1]) or (260 > $properties[0])) {
                    $this->addError($attribute, "Неверный размер изображения - {$properties[0]}*{$properties[1]} px, должно быть больше 260*350 px.");
                    Yii::$app->files->deleteFile($this, $attribute);
                }
            } else {
                $this->addError($attribute, 'Невозможно получить параметры изображения.');
            }
        }
    }

    private function getModelName()
    {
        $class = explode('\\', get_class($this), 3);
        return (!empty($class) and isset($class[count($class) - 1])) ? $class[count($class) - 1] : null;
    }

    public function getSearchShortView()
    {
        return $this->isFilm ? '/afisha/_afisha_short_film' : '/afisha/_afisha_short';
    }
}

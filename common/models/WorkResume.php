<?php

namespace common\models;

use common\models\traits\GetCityTrait;
use yii;
use common\models\traits\GetUserTrait;
use yii\db\ActiveRecord;
use yii\helpers\HtmlPurifier;

/**
 * This is the model class for table "work_resume".
 *
 * @property integer $id
 * @property integer $idCategory
 * @property integer $idUser
 * @property integer $idCity
 * @property string $title
 * @property string $description
 * @property string $name
 * @property string $year
 * @property string $experience
 * @property integer $male
 * @property string $salary
 * @property integer $isFullDay
 * @property integer $isOffice
 * @property string $phone
 * @property string $email
 * @property string $skype
 * @property string $url
 * @property string $dateCreate
 * @property WorkCategory $category
 * @property City $city
 * @property User $user
 * @property string $photoUrl
 * @property string $education
 * @property string $dateUpdate
 */
class WorkResume extends ActiveRecord
{
    use GetCityTrait;
    use GetUserTrait;

    public $educationList = [
        '2'=>'Среднее', '3'=>'Среднее техническое', '4'=>'Высшее'
    ];

    public static $educations = [
        '2' => 'Среднее',
        '3' => 'Среднее техническое',
        '4' => 'Высшее'
    ];
    
    public function behaviors()
    {
        return [
            'slug' => [
                'class' => 'common\behaviors\Slug',
                'in_attribute' => 'title',
                'out_attribute' => 'url',
                'translit' => true
            ]
        ];
    }

    public static function tableName()
    {
        return 'work_resume';
    }

    public function rules()
    {
        return [
            [
                ['idCategory', 'idUser', 'idCity', 'title', 'description', 'name', 'experience', 'education'],
                'required','message' => 'Поле не может быть пустым'
            ],
            [['idCategory', 'idUser', 'idCity', 'male', 'isFullDay', 'isOffice', 'education'], 'integer'],
            [['description', 'experience'], 'string'],
            [
                [
                 'dateCreate', 'url', 'seo_description', 'seo_keywords', 'seo_title', 'dateUpdate',
                 'sitemap_en', 'sitemap_priority', 'sitemap_changefreq'
                ],
                'safe'
            ],
            [['email'], 'email'],
            [['title', 'name', 'year', 'salary', 'phone', 'skype'], 'string', 'max' => 255],
            [['photoUrl'], 'file', 'maxSize' => Yii::$app->params['maxSizeUpload']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idCategory' => 'Категория',
            'idUser' => 'Пользователь',
            'idCity' => 'Город',
            'title' => 'Заголовок',
            'description' => 'Описание',
            'name' => 'Название',
            'year' => 'Возраст',
            'experience' => 'Опыт',
            'male' => 'Мужской пол',
            'salary' => 'Зарплата',
            'isFullDay' => 'Полный день',
            'isOffice' => 'В офисе',
            'phone' => 'Телефон',
            'email' => 'E-mail',
            'skype' => 'Skype',
            'dateCreate' => 'Добавленно',
            'photoUrl' => 'Фото',
            'education' => 'Образование',
            'seo_description'=>'SEO Description',
            'seo_keywords'=>'SEO Ключевые слова',
            'seo_title' => 'Seo Title',
            'dateUpdate' => 'Дате обновления',
            'sitemap_en' => 'Добавить в sitemap',
            'sitemap_priority' => 'Sitemap приоритет',
            'sitemap_changefreq' => 'Sitemap частота изменения',
        ];
    }

    private function purifyField($text)
    {
        return HtmlPurifier::process($text, [
            'HTML.AllowedElements' => [],
            'HTML.AllowedAttributes' => [],
            'AutoFormat.RemoveEmpty.RemoveNbsp' => true,
            'AutoFormat.RemoveEmpty' => true,
            'Core.EscapeInvalidTags' => true,
        ]);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->title = $this->purifyField($this->title);
            $this->name = $this->purifyField($this->name);
            $this->experience = $this->purifyField($this->experience);
            $this->phone = $this->purifyField($this->phone);
            $this->skype = $this->purifyField($this->skype);
            $this->url = $this->purifyField($this->url);

            $this->dateUpdate = date('Y-m-d');
            if ($this->isNewRecord) {
                $this->dateCreate = date('Y-m-d H:i:s');
            }
            return true;
        } else {
            return false;
        }
    }
    
    public function afterSave($insert, $changedAttributes = null)
    {
        /** @var File $file */
        $file = File::find()->where(['name' => $this->photoUrl])->one();
        if ($file) {
            $file->pid = $this->id;
            $file->update();
        }
        parent::afterSave($insert, $changedAttributes);
    }

    public function getCategory()
    {
        return $this->hasOne(WorkCategory::className(), ['id' => 'idCategory']);
    }

    public function getFrontendUrl()
    {
        return '/resume/' . $this->id;
    }

    public function getSubDomain()
    {
        return !empty($this->city) ? ($this->city->subdomain . '.') : null;
    }
    
    public function getSearchShortView()
    {
        return '/resume/_resume_short';
    }
}

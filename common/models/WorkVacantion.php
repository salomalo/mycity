<?php

namespace common\models;

use common\models\traits\GetCityTrait;
use yii;
use common\models\traits\GetUserTrait;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "work_vacantion".
 *
 * @property integer $id
 * @property integer $idCompany
 * @property integer $idCategory
 * @property integer $idCity
 * @property integer $idUser
 * @property string $title
 * @property string $description
 * @property string $proposition
 * @property string $phone
 * @property string $email
 * @property string $skype
 * @property string $name
 * @property string $salary
 * @property integer $isFullDay
 * @property integer $isOffice
 * @property string $experience
 * @property integer $male
 * @property string $minYears
 * @property string $maxYears
 * @property string $dateCreate
 * @property string $url
 * @property City $city
 * @property WorkCategory $category
 * @property User $user
 * @property Business $company
 * @property CountViews $countView
 */
class WorkVacantion extends ActiveRecord
{
    use GetUserTrait; //public function getUser()
    use GetCityTrait;
    public $educationList = [
        '1' => 'Любое',
        '2' => 'Среднее',
        '3' => 'Среднее техническое',
        '4' => 'Высшее'
    ];

    public static $educations = [
        '1' => 'Любое',
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

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'work_vacantion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idCategory', 'idCity', 'idUser', 'title', 'description', 'proposition', 'name', 'experience', 'education'], 'required', 'message' => 'Поле не может быть пустым'],
            [['idCompany', 'idCategory', 'idCity', 'idUser', 'isFullDay', 'isOffice', 'male', 'education'], 'integer'],
            [['description', 'proposition', 'experience'], 'string'],
            [['dateCreate', 'url', 'seo_description', 'seo_keywords', 'seo_title', 'dateUpdate', 'sitemap_en', 'sitemap_priority', 'sitemap_changefreq'], 'safe'],
            [['email'], 'email'],
            [['title', 'phone', 'skype', 'name', 'salary', 'minYears', 'maxYears'], 'string', 'max' => 255]
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
            'idCity' => 'Город',
            'idUser' => 'Пользователь',
            'title' => 'Заголовок',
            'description' => 'Описание',
            'proposition' => 'Предложение',
            'phone' => 'Телефон',
            'email' => 'E-mail',
            'skype' => 'Skype',
            'name' => 'Имя',
            'salary' => 'Зарплата',
            'isFullDay' => 'Полный день',
            'isOffice' => 'В офисе',
            'experience' => 'Опыт',
            'male' => 'Мужской пол',
            'minYears' => 'Мин. лет',
            'maxYears' => 'Макс. лет',
            'dateCreate' => 'Добавленно',
            'education' => 'Образование',
            'seo_description' => 'SEO Description',
            'seo_keywords' => 'SEO Ключевые слова',
            'seo_title' => 'Seo Title',
            'dateUpdate' => 'Дате обновления',
            'sitemap_en' => 'Добавить в sitemap',
            'sitemap_priority' => 'Sitemap приоритет',
            'sitemap_changefreq' => 'Sitemap частота изменения',
        ];
    }

    public function beforeSave($insert)
    {
        $this->title = strip_tags($this->title);
        $this->phone = strip_tags($this->phone);
        $this->skype = strip_tags($this->skype);
        $this->name = strip_tags($this->name);
        $this->experience = strip_tags($this->experience);
        $this->url = strip_tags($this->url);

        if (parent::beforeSave($insert)) {
            $this->dateUpdate = date('Y-m-d');
            if ($this->isNewRecord) {
                $this->dateCreate = date('Y-m-d H:i:s');
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(WorkCategory::className(), ['id' => 'idCategory']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Business::className(), ['id' => 'idCompany']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getCountView()
    {
        return $this->hasOne(CountViews::className(), ['pid' => 'id'])->andOnCondition(['type' => File::TYPE_WORK_VACANTION]);
    }

    public static function getCompanyList($idUser)
    {
        return ArrayHelper::map(
            Business::find()->where(['idUser' => $idUser])->select(['id', 'title'])->limit(50)->all(),
            'id',
            'title'
        );
    }

    public function getSubDomain()
    {
        return !empty($this->city) ? ($this->city->subdomain . '.') : null;
    }

    public function getFrontendUrl()
    {
        return '/vacantion/' . $this->id;
    }

    public function getSearchShortView()
    {
        return '/vacantion/_vacantion_short';
    }
}

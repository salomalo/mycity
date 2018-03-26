<?php
namespace common\models;

use yii;
use common\components\ActiveRecordMultiLang;

/**
 * This is the model class for table "product_customfield".
 *
 * @property integer $id
 * @property integer $idCategory
 * @property string $title
 * @property string $alias
 * @property integer $type string/dropdown
 * @property integer $isFilter
 * @property CustomfieldCategory $categoryCustomfield
 * @property ProductCategory $category
 * @property ProductCustomfieldValue[] $customfieldValue
 */
class ProductCustomfield extends ActiveRecordMultiLang
{
    const TYPE_STRING = 1;
    const TYPE_DROP_DOWN = 0;

    public $oldAlias;

    public static $types = [self::TYPE_DROP_DOWN => 'Список', self::TYPE_STRING => 'Строка'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_customfield';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idCategory', 'title', 'type', 'alias'], 'required','message' => 'Поле не может быть пустым'],
            [['idCategory', 'type', 'isFilter', 'idCategoryCustomfield', 'order'], 'integer'],
            [['alias'], 'string', 'max' => 255],
            [['title'], 'string'],
            [['alias'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idCategory' => 'Id Category',
            'title' => 'Title',
            'alias' => 'Alias',
            'type' => 'Type',
            'isFilter' => 'Is Filter',
            'idCategoryCustomfield' => 'Категория Customfield',
            'order' => 'Сортировка'
        ];
    }

    public function translateAttributes()
    {
        return ['title'];
    }

    public function getCustomfieldValue()
    {
       return $this->hasMany(ProductCustomfieldValue::className(), ['idCustomfield' => 'id'])->orderBy(['value' => SORT_ASC]);
    }

    public function getCategory()
    {
       return $this->hasOne(ProductCategory::className(), ['id' => 'idCategory']);
    }

    public function getCategoryCustomfield()
    {
       return $this->hasOne(CustomfieldCategory::className(), ['id' => 'idCategoryCustomfield']);
    }
}

<?php

namespace common\models;

use Yii;
use common\components\ActiveRecordMultiLang;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "customfield_category".
 *
 * @property integer $id
 * @property string $title
 */
class CustomfieldCategory extends ActiveRecordMultiLang
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customfield_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'string'],
            [['order'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'order' => 'Сортировка'
        ];
    }
    
    public function translateAttributes()
    {
        return [
            'title',
        ];
    }
    
    public static function getCategoryArray()
    {
        return ArrayHelper::map(CustomfieldCategory::find()->all(), 'id', 'title');
    }
}

<?php

namespace common\models;

use Yii;
use common\components\ActiveRecordMultiLang;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "region".
 *
 * @property integer $id
 * @property string $title
 */
class Region extends ActiveRecordMultiLang
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'region';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required','message' => 'Поле не может быть пустым'],
            [['title'], 'string']
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
        ];
    }
    
    public function translateAttributes()
    {
        return [
            'title',
        ];
    }

    public static function getAll()
    {
        return ArrayHelper::map(self::find()->select(['id','title'])->all(),'id','title');
    }
}

<?php

namespace common\models;

use Yii;
use common\components\ActiveRecordMultiLang;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tag".
 *
 * @property integer $id
 * @property string $title
 */
class Tag extends ActiveRecordMultiLang
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Тег',
        ];
    }
    
    public function translateAttributes()
    {
        return [
            'title',
        ];
    }

    public static function getAll(){
        return ArrayHelper::map(self::find()->select('title')->all(),'title','title');
    }
}

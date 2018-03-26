<?php

namespace common\models;

use Yii;
use common\components\ActiveRecordMultiLang;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "kino_genre".
 *
 * @property integer $id
 * @property string $title
 * @property string $url
 */
class KinoGenre extends ActiveRecordMultiLang
{
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
        return 'kino_genre';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'url' => 'Url',
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
        return ArrayHelper::map(self::find()->select(['id', 'title'])->all(), 'id', 'title');
    }
}

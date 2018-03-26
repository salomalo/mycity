<?php

namespace common\models;

use common\behaviors\ImageUpload;
use Yii;

/**
 * This is the model class for table "business_template".
 *
 * @property integer $id
 * @property string $title
 * @property string $alias
 * @property string $img
 *
 * @property Business[] $businesses
 */
class BusinessTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'business_template';
    }

    public function behaviors()
    {
        return [
            'ImageUpload' => [
                'class' => ImageUpload::className(),
                'attribute' => ['img'],
                'id' => 'id',
                'pid' => 'pid',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'alias'], 'string', 'max' => 255],
            [['img'], 'file', 'maxSize' => Yii::$app->params['maxSizeUpload']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'alias' => 'Алиас',
            'img' => 'Картинка',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinesses()
    {
        return $this->hasMany(Business::className(), ['template_id' => 'id']);
    }
}

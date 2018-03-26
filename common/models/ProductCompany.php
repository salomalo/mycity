<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\sphinx\ActiveQuery;

/**
 * This is the model class for table "product_company".
 *
 * @property integer $id
 * @property string $title
 * @property string $image
 * @property ProductCategoryCategory[] $pсategories
 */
class ProductCompany extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required','message' => 'Поле не может быть пустым'],
            [['title'], 'string', 'max' => 255],
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
            'title' => 'Title',
            'image' => 'Image',
        ];
    }
    
    public function afterSave($insert, $changedAttributes = null) {
         
        $file = File::find()->where(['name' => $this->image])->one();
        if($file){
            $file->pid = $this->id;
            $file->update();
        }
          
        parent::afterSave($insert, $changedAttributes);
     }

    public function getPсategories()
    {
        return $this->hasMany(ProductCategoryCategory::className(), ['ProductCompany' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(ProductCategory::className(), ['id' => 'ProductCategory'])->viaTable('ProductCategoryCategory', ['ProductCompany' => 'id']);
    }
}

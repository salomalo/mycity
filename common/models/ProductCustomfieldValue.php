<?php

namespace common\models;

use yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "product_customfield_value".
 *
 * @property integer $id
 * @property integer $idCustomfield
 * @property string $value
 * @property ProductCustomfield $customField
 */
class ProductCustomfieldValue extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_customfield_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idCustomfield'], 'required','message' => 'Поле не может быть пустым'],
            [['idCustomfield'], 'integer'],
            [['value'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idCustomfield' => 'Id Customfield',
            'value' => 'Value',
        ];
    }

    public function getCustomField()
    {
        return $this->hasOne(ProductCustomfield::className(), ['id' => 'idCustomfield']);
    }
}

<?php

namespace common\models;

use yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "vkWidget_cityPublic".
 *
 * @property integer $id
 * @property integer $city_id
 * @property integer $group_id
 * @property integer $width
 * @property integer $height
 * @property string $network
 * @property City $city
 */
class WidgetCityPublic extends ActiveRecord
{
    const VK = 'vk';
    const FB = 'fb';
    const TW = 'tw';

    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    public static function tableName()
    {
        return 'widget_city_public';
    }

    public function rules()
    {
        return [
            [['city_id', 'group_id', 'network'], 'required'],
            [['city_id', 'width', 'height'], 'integer'],
            [['group_id', 'network'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city_id' => 'Город',
            'group_id' => 'Группа в соцсети.',
            'width' => 'Ширина',
            'height' => 'Высота',
            'network' => 'Соцсеть'
        ];
    }
}

<?php

namespace console\models;

use yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "vk_count_table".
 *
 * @property integer $id
 * @property string $user_id
 * @property string $token
 * @property string $date
 * @property integer $count
 */
class VkCountTable extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vk_count_table';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['token'], 'required'],
            [['date'], 'safe'],
            [['count'], 'integer'],
            [['token'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token' => 'Token',
            'date' => 'Date',
            'count' => 'Count',
        ];
    }

    /**
     * @param $token string
     * @param $count integer
     */
    public static function insertNew($token, $count)
    {
        $item = new VkCountTable();
        $item->token = $token;
        $item->count = $count;
        $item->date = date('Y-m-d G:i:s');
        $item->save();
    }

    /**
     * @param $count integer
     */
    public function updateThis($count)
    {
        $this->count = $count;
        $this->date = date('Y-m-d G:i:s');
        $this->save();
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "parse_kino".
 *
 * @property integer $id
 * @property integer $remote_cinema_id
 * @property integer $local_cinema_id
 */
class ParseKino extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parse_kino';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['remote_cinema_id', 'local_cinema_id'], 'required'],
            [['remote_cinema_id', 'local_cinema_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'remote_cinema_id' => 'ID Кинотеатра с http://kino.i.ua/',
            'local_cinema_id' => 'ID Кинотеатра с http://citylife.info/',
        ];
    }

    public function getBusiness()
    {
        return $this->hasOne(Business::className(), ['id' => 'local_cinema_id']);
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "parse_karabas".
 *
 * @property integer $id
 * @property string $remote_business_id
 * @property integer $local_business_id
 */
class ParseKarabas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parse_karabas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['local_business_id'], 'integer'],
            [['remote_business_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'remote_business_id' => 'Идентификатор предприятия с https://karabas.com/',
            'local_business_id' => 'ID предприятия с http://citylife.info/',
        ];
    }
}

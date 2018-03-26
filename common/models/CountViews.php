<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "count_views".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $pid
 * @property string $pidMongo
 * @property integer $count
 * @property integer $countMonth
 * @property string $lastIp
 */
class CountViews extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'count_views';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'count', 'lastIp'], 'required','message' => 'Поле не может быть пустым'],
            [['type', 'pid', 'count', 'countMonth'], 'integer'],
            [['pidMongo'], 'string', 'max' => 255],
            [['lastIp'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'pid' => 'Pid',
            'pidMongo' => 'Pid Mongo',
            'count' => 'Count',
            'countMonth' => 'Count Month',
            'lastIp' => 'Last Ip',
        ];
    }
}
